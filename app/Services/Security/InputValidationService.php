<?php

namespace App\Services\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Input Validation and Sanitization Service
 *
 * Provides comprehensive input validation with fail-fast approach
 * Lightweight schema validation for optimal performance
 */
class InputValidationService
{
    /**
     * Common validation rules for different input types
     */
    private array $commonRules = [
        'email' => 'email:rfc,dns',
        'url' => 'url',
        'ip' => 'ip',
        'alpha' => 'alpha',
        'alpha_num' => 'alpha_num',
        'numeric' => 'numeric',
        'string' => 'string',
        'boolean' => 'boolean',
    ];

    /**
     * Security-focused validation patterns
     */
    private array $securityPatterns = [
        'no_sql_injection' => '/^[^\'";\\\\]*$/',
        'no_xss' => '/^[^<>]*$/',
        'safe_filename' => '/^[a-zA-Z0-9._-]+$/',
        'safe_path' => '/^[a-zA-Z0-9.\/_-]+$/',
    ];

    /**
     * Validate request with security-focused rules
     */
    public function validateRequest(Request $request, array $rules = []): array
    {
        $startTime = microtime(true);

        try {
            // Get all input data
            $data = $this->getAllInputData($request);

            // Apply basic security filters
            $data = $this->applySanitization($data);

            // Validate against rules
            $validatedData = $this->performValidation($data, $rules);

            // Additional security checks
            $this->performSecurityChecks($validatedData);

            return [
                'valid' => true,
                'data' => $validatedData,
                'processing_time' => microtime(true) - $startTime
            ];

        } catch (ValidationException $e) {
            return [
                'valid' => false,
                'errors' => $e->errors(),
                'processing_time' => microtime(true) - $startTime
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'error' => 'Validation service error: ' . $e->getMessage(),
                'processing_time' => microtime(true) - $startTime
            ];
        }
    }

    /**
     * Get all input data from request
     */
    private function getAllInputData(Request $request): array
    {
        return array_merge(
            $request->query(),
            $request->request->all(),
            $request->hasFile('*') ? $this->getFileData($request) : []
        );
    }

    /**
     * Extract file upload information
     */
    private function getFileData(Request $request): array
    {
        $files = [];

        foreach ($request->allFiles() as $key => $file) {
            if (is_array($file)) {
                foreach ($file as $index => $singleFile) {
                    $files["{$key}.{$index}"] = $this->getFileInfo($singleFile);
                }
            } else {
                $files[$key] = $this->getFileInfo($file);
            }
        }

        return $files;
    }

    /**
     * Get safe file information
     */
    private function getFileInfo($file): array
    {
        return [
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'is_valid' => $file->isValid(),
        ];
    }

    /**
     * Apply basic sanitization to input data
     */
    private function applySanitization(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->applySanitization($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value, $key);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize string input based on context
     */
    private function sanitizeString(string $value, string $key): string
    {
        // Basic length check
        if (strlen($value) > config('security.validation.max_input_length', 4096)) {
            throw new ValidationException(
                Validator::make([], []),
                [$key => ['Input too long']]
            );
        }

        // Remove null bytes
        $value = str_replace("\0", '', $value);

        // Context-specific sanitization
        if (str_contains($key, 'email')) {
            return filter_var($value, FILTER_SANITIZE_EMAIL);
        }

        if (str_contains($key, 'url')) {
            return filter_var($value, FILTER_SANITIZE_URL);
        }

        // General HTML sanitization for display fields
        if (in_array($key, ['name', 'title', 'description', 'comment'])) {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

        return trim($value);
    }

    /**
     * Perform validation against rules
     */
    private function performValidation(array $data, array $rules): array
    {
        // Merge with security rules
        $securityRules = $this->getSecurityRules();
        $allRules = array_merge($securityRules, $rules);

        $validator = Validator::make($data, $allRules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Get default security validation rules
     */
    private function getSecurityRules(): array
    {
        return [
            '*' => [
                function ($attribute, $value, $fail) {
                    if (is_string($value) && $this->containsSecurityThreats($value)) {
                        $fail("The {$attribute} contains potentially dangerous content.");
                    }
                }
            ]
        ];
    }

    /**
     * Check for security threats in input
     */
    private function containsSecurityThreats(string $value): bool
    {
        // Quick SQL injection check
        if (preg_match('/\b(union|select|insert|update|delete|drop)\b.*\b(from|where|into)\b/i', $value)) {
            return true;
        }

        // Quick XSS check
        if (preg_match('/<script[^>]*>|javascript:|on\w+\s*=/i', $value)) {
            return true;
        }

        // Path traversal check
        if (preg_match('/\.\.\//i', $value)) {
            return true;
        }

        // Template injection check
        if (preg_match('/\$\{.*?\}/i', $value)) {
            return true;
        }

        return false;
    }

    /**
     * Perform additional security checks
     */
    private function performSecurityChecks(array $data): void
    {
        // Check for suspicious patterns in combined input
        $combinedInput = json_encode($data);

        if ($this->containsAdvancedThreats($combinedInput)) {
            throw new ValidationException(
                Validator::make([], []),
                ['security' => ['Advanced security threat detected']]
            );
        }
    }

    /**
     * Check for advanced security threats
     */
    private function containsAdvancedThreats(string $content): bool
    {
        $advancedPatterns = [
            '/\${jndi:(ldap|rmi|dns):/i', // Log4j
            '/<%.*?%>/i',                 // Server-side template injection
            '/\{\{.*?\}\}/i',             // Template injection
            '/\[.*?\]/i',                 // Potential LDAP injection
        ];

        foreach ($advancedPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate file uploads
     */
    public function validateFileUpload($file, array $rules = []): array
    {
        $startTime = microtime(true);

        try {
            // Basic file validation
            if (!$file || !$file->isValid()) {
                throw new ValidationException(
                    Validator::make([], []),
                    ['file' => ['Invalid file upload']]
                );
            }

            // Size check
            $maxSize = config('security.validation.max_file_size', 5242880); // 5MB
            if ($file->getSize() > $maxSize) {
                throw new ValidationException(
                    Validator::make([], []),
                    ['file' => ['File size exceeds limit']]
                );
            }

            // MIME type validation
            $allowedTypes = config('security.validation.allowed_file_types', []);
            if (!empty($allowedTypes) && !in_array($file->getMimeType(), $allowedTypes)) {
                throw new ValidationException(
                    Validator::make([], []),
                    ['file' => ['File type not allowed']]
                );
            }

            // Magic bytes validation
            if (!$this->validateMagicBytes($file)) {
                throw new ValidationException(
                    Validator::make([], []),
                    ['file' => ['File content does not match extension']]
                );
            }

            // Filename security check
            $filename = $file->getClientOriginalName();
            if (!preg_match($this->securityPatterns['safe_filename'], $filename)) {
                throw new ValidationException(
                    Validator::make([], []),
                    ['file' => ['Unsafe filename']]
                );
            }

            return [
                'valid' => true,
                'file_info' => $this->getFileInfo($file),
                'processing_time' => microtime(true) - $startTime
            ];

        } catch (ValidationException $e) {
            return [
                'valid' => false,
                'errors' => $e->errors(),
                'processing_time' => microtime(true) - $startTime
            ];
        }
    }

    /**
     * Validate file magic bytes against MIME type
     */
    private function validateMagicBytes($file): bool
    {
        $mimeType = $file->getMimeType();
        $content = file_get_contents($file->getPathname());
        $header = substr($content, 0, 16);

        $magicBytes = [
            'image/jpeg' => ["\xFF\xD8\xFF"],
            'image/png' => ["\x89\x50\x4E\x47"],
            'image/gif' => ["\x47\x49\x46\x38"],
            'application/pdf' => ["\x25\x50\x44\x46"],
            'application/zip' => ["\x50\x4B\x03\x04", "\x50\x4B\x05\x06"],
        ];

        if (!isset($magicBytes[$mimeType])) {
            return true; // Allow unknown types (can be configured)
        }

        foreach ($magicBytes[$mimeType] as $magic) {
            if (substr($header, 0, strlen($magic)) === $magic) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create validation rules for specific routes
     */
    public function getRulesForRoute(string $routeName): array
    {
        $routeRules = [
            'login' => [
                'email' => 'required|email:rfc,dns|max:255',
                'password' => 'required|string|min:6|max:255',
            ],
            'register' => [
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email:rfc,dns|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]/',
            ],
            'contact' => [
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email:rfc,dns|max:255',
                'message' => 'required|string|max:1000',
            ],
            'file-upload' => [
                'file' => 'required|file|max:5120', // 5MB
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]
        ];

        return $routeRules[$routeName] ?? [];
    }
}
