<?php

namespace App\Services\Security;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * File Upload Security Service
 *
 * Provides comprehensive file upload security including:
 * - MIME type validation
 * - Magic bytes verification
 * - Virus scanning integration
 * - Safe file storage
 */
class FileUploadSecurityService
{
    /**
     * Magic bytes signatures for common file types
     */
    private array $magicBytes = [
        // Images
        'image/jpeg' => [
            "\xFF\xD8\xFF\xE0",
            "\xFF\xD8\xFF\xE1",
            "\xFF\xD8\xFF\xE8",
            "\xFF\xD8\xFF\xDB"
        ],
        'image/png' => ["\x89\x50\x4E\x47\x0D\x0A\x1A\x0A"],
        'image/gif' => [
            "\x47\x49\x46\x38\x37\x61",
            "\x47\x49\x46\x38\x39\x61"
        ],
        'image/webp' => ["\x52\x49\x46\x46", "\x57\x45\x42\x50"],

        // Documents
        'application/pdf' => ["\x25\x50\x44\x46"],
        'application/msword' => ["\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1"],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => [
            "\x50\x4B\x03\x04"
        ],

        // Archives
        'application/zip' => [
            "\x50\x4B\x03\x04",
            "\x50\x4B\x05\x06",
            "\x50\x4B\x07\x08"
        ],
        'application/x-rar-compressed' => ["\x52\x61\x72\x21\x1A\x07"],

        // Text
        'text/plain' => [], // Variable, skip magic byte check
    ];

    /**
     * Dangerous file extensions
     */
    private array $dangerousExtensions = [
        'php', 'php3', 'php4', 'php5', 'phtml', 'asp', 'aspx', 'jsp',
        'exe', 'bat', 'cmd', 'com', 'scr', 'vbs', 'js', 'jar',
        'sh', 'py', 'rb', 'pl', 'cgi'
    ];

    /**
     * Maximum file size per type (bytes)
     */
    private array $maxFileSizes = [
        'image/*' => 5 * 1024 * 1024,     // 5MB for images
        'application/pdf' => 10 * 1024 * 1024, // 10MB for PDFs
        'text/plain' => 1 * 1024 * 1024,  // 1MB for text files
        'default' => 5 * 1024 * 1024      // 5MB default
    ];

    /**
     * Validate uploaded file security
     */
    public function validateFile(UploadedFile $file): array
    {
        $startTime = microtime(true);
        $issues = [];

        try {
            // Basic file validation
            if (!$file->isValid()) {
                $issues[] = [
                    'severity' => 'high',
                    'type' => 'invalid_upload',
                    'message' => 'File upload failed or corrupted'
                ];
                return $this->buildResult(false, $issues, $startTime);
            }

            // File size validation
            $sizeIssues = $this->validateFileSize($file);
            $issues = array_merge($issues, $sizeIssues);

            // Extension validation
            $extensionIssues = $this->validateExtension($file);
            $issues = array_merge($issues, $extensionIssues);

            // MIME type validation
            $mimeIssues = $this->validateMimeType($file);
            $issues = array_merge($issues, $mimeIssues);

            // Magic bytes validation
            $magicBytesIssues = $this->validateMagicBytes($file);
            $issues = array_merge($issues, $magicBytesIssues);

            // Content scanning
            $contentIssues = $this->scanFileContent($file);
            $issues = array_merge($issues, $contentIssues);

            // Filename security
            $filenameIssues = $this->validateFilename($file);
            $issues = array_merge($issues, $filenameIssues);

            $hasHighSeverityIssues = collect($issues)->contains('severity', 'high');

            return $this->buildResult(!$hasHighSeverityIssues, $issues, $startTime);

        } catch (\Exception $e) {
            Log::error('File validation error', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);

            return $this->buildResult(false, [[
                'severity' => 'high',
                'type' => 'validation_error',
                'message' => 'File validation failed'
            ]], $startTime);
        }
    }

    /**
     * Validate file size
     */
    private function validateFileSize(UploadedFile $file): array
    {
        $issues = [];
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Get max size for this MIME type
        $maxSize = $this->getMaxSizeForMimeType($mimeType);

        if ($size > $maxSize) {
            $issues[] = [
                'severity' => 'high',
                'type' => 'file_too_large',
                'message' => "File size ({$this->formatBytes($size)}) exceeds limit ({$this->formatBytes($maxSize)})"
            ];
        }

        // Minimum size check (empty files)
        if ($size < 10) {
            $issues[] = [
                'severity' => 'medium',
                'type' => 'file_too_small',
                'message' => 'File appears to be empty or too small'
            ];
        }

        return $issues;
    }

    /**
     * Validate file extension
     */
    private function validateExtension(UploadedFile $file): array
    {
        $issues = [];
        $extension = strtolower($file->getClientOriginalExtension());

        // Check for dangerous extensions
        if (in_array($extension, $this->dangerousExtensions)) {
            $issues[] = [
                'severity' => 'high',
                'type' => 'dangerous_extension',
                'message' => "File extension '.{$extension}' is not allowed for security reasons"
            ];
        }

        // Check for double extensions
        $filename = $file->getClientOriginalName();
        if (preg_match('/\.[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/', $filename)) {
            $issues[] = [
                'severity' => 'medium',
                'type' => 'double_extension',
                'message' => 'Files with double extensions are suspicious'
            ];
        }

        return $issues;
    }

    /**
     * Validate MIME type
     */
    private function validateMimeType(UploadedFile $file): array
    {
        $issues = [];
        $mimeType = $file->getMimeType();
        $allowedTypes = config('security.validation.allowed_file_types', []);

        // Check if MIME type is allowed
        if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
            $issues[] = [
                'severity' => 'high',
                'type' => 'invalid_mime_type',
                'message' => "MIME type '{$mimeType}' is not allowed"
            ];
        }

        // Check for suspicious MIME types
        $suspiciousMimes = [
            'application/x-php',
            'application/x-executable',
            'application/x-msdownload',
            'text/x-php'
        ];

        if (in_array($mimeType, $suspiciousMimes)) {
            $issues[] = [
                'severity' => 'high',
                'type' => 'suspicious_mime_type',
                'message' => "MIME type '{$mimeType}' is potentially dangerous"
            ];
        }

        return $issues;
    }

    /**
     * Validate magic bytes
     */
    private function validateMagicBytes(UploadedFile $file): array
    {
        $issues = [];
        $mimeType = $file->getMimeType();

        // Skip validation for text files
        if (str_starts_with($mimeType, 'text/')) {
            return $issues;
        }

        // Get expected magic bytes for this MIME type
        if (!isset($this->magicBytes[$mimeType])) {
            $issues[] = [
                'severity' => 'low',
                'type' => 'unknown_mime_type',
                'message' => 'Magic byte validation not available for this file type'
            ];
            return $issues;
        }

        $expectedMagicBytes = $this->magicBytes[$mimeType];
        if (empty($expectedMagicBytes)) {
            return $issues; // Skip validation
        }

        // Read file header
        $handle = fopen($file->getPathname(), 'rb');
        $header = fread($handle, 16);
        fclose($handle);

        // Check against expected magic bytes
        $validMagicBytes = false;
        foreach ($expectedMagicBytes as $magic) {
            if (substr($header, 0, strlen($magic)) === $magic) {
                $validMagicBytes = true;
                break;
            }
        }

        if (!$validMagicBytes) {
            $issues[] = [
                'severity' => 'high',
                'type' => 'magic_bytes_mismatch',
                'message' => 'File content does not match the declared file type'
            ];
        }

        return $issues;
    }

    /**
     * Scan file content for threats
     */
    private function scanFileContent(UploadedFile $file): array
    {
        $issues = [];

        // Skip scanning for large files to avoid performance issues
        if ($file->getSize() > 1024 * 1024) { // 1MB
            return $issues;
        }

        $content = file_get_contents($file->getPathname());

        // Check for embedded scripts
        $scriptPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/<\?php.*?\?>/is',
            '/<%.*?%>/is',
            '/javascript:/i',
            '/vbscript:/i'
        ];

        foreach ($scriptPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $issues[] = [
                    'severity' => 'high',
                    'type' => 'embedded_script',
                    'message' => 'File contains potentially malicious script content'
                ];
                break;
            }
        }

        // Check for suspicious URLs
        if (preg_match('/https?:\/\/[^\s]+/i', $content)) {
            $issues[] = [
                'severity' => 'medium',
                'type' => 'embedded_url',
                'message' => 'File contains embedded URLs'
            ];
        }

        return $issues;
    }

    /**
     * Validate filename security
     */
    private function validateFilename(UploadedFile $file): array
    {
        $issues = [];
        $filename = $file->getClientOriginalName();

        // Check for path traversal attempts
        if (str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
            $issues[] = [
                'severity' => 'high',
                'type' => 'path_traversal',
                'message' => 'Filename contains path traversal characters'
            ];
        }

        // Check for control characters
        if (preg_match('/[\x00-\x1F\x7F]/', $filename)) {
            $issues[] = [
                'severity' => 'high',
                'type' => 'control_characters',
                'message' => 'Filename contains control characters'
            ];
        }

        // Check filename length
        if (strlen($filename) > 255) {
            $issues[] = [
                'severity' => 'medium',
                'type' => 'filename_too_long',
                'message' => 'Filename is too long'
            ];
        }

        return $issues;
    }

    /**
     * Securely store uploaded file
     */
    public function secureStore(UploadedFile $file, string $directory = 'uploads'): array
    {
        try {
            // Generate secure filename
            $secureFilename = $this->generateSecureFilename($file);

            // Store outside web root
            $path = Storage::disk('local')->putFileAs(
                "secure/{$directory}",
                $file,
                $secureFilename
            );

            // Schedule virus scan (background job)
            $this->scheduleVirusScan($path);

            return [
                'success' => true,
                'path' => $path,
                'secure_filename' => $secureFilename,
                'original_filename' => $file->getClientOriginalName()
            ];

        } catch (\Exception $e) {
            Log::error('Secure file storage failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);

            return [
                'success' => false,
                'error' => 'File storage failed'
            ];
        }
    }

    /**
     * Generate secure filename
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $hash = substr(hash('sha256', $file->getClientOriginalName() . time()), 0, 8);

        return "{$safeName}_{$hash}.{$extension}";
    }

    /**
     * Schedule virus scan for uploaded file
     */
    private function scheduleVirusScan(string $filePath): void
    {
        // Dispatch background job for virus scanning
        dispatch(new \App\Jobs\VirusScanJob($filePath))->onQueue('security');
    }

    /**
     * Get maximum file size for MIME type
     */
    private function getMaxSizeForMimeType(string $mimeType): int
    {
        foreach ($this->maxFileSizes as $pattern => $size) {
            if ($pattern === 'default') continue;

            if (fnmatch($pattern, $mimeType)) {
                return $size;
            }
        }

        return $this->maxFileSizes['default'];
    }

    /**
     * Format file size for human reading
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = min(floor(log($bytes) / log(1024)), count($units) - 1);

        return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }

    /**
     * Build validation result
     */
    private function buildResult(bool $valid, array $issues, float $startTime): array
    {
        return [
            'valid' => $valid,
            'issues' => $issues,
            'processing_time' => microtime(true) - $startTime,
            'high_severity_count' => collect($issues)->where('severity', 'high')->count(),
            'medium_severity_count' => collect($issues)->where('severity', 'medium')->count(),
            'low_severity_count' => collect($issues)->where('severity', 'low')->count()
        ];
    }
}
