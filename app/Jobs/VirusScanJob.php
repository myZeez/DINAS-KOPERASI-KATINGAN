<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Virus Scanning Background Job
 *
 * Performs asynchronous virus scanning of uploaded files
 * Integrates with ClamAV or cloud-based scanning services
 */
class VirusScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 60;

    public function __construct(
        private string $filePath
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $scanResult = $this->scanFile($this->filePath);

            if (!$scanResult['clean']) {
                $this->handleInfectedFile($scanResult);
            } else {
                $this->markFileAsClean();
            }

        } catch (\Exception $e) {
            Log::error('Virus scan job failed', [
                'file_path' => $this->filePath,
                'error' => $e->getMessage()
            ]);

            // Mark file as potentially unsafe
            $this->markFileAsUnsafe($e->getMessage());
        }
    }

    /**
     * Scan file for viruses
     */
    private function scanFile(string $filePath): array
    {
        $fullPath = Storage::disk('local')->path($filePath);

        // Check if file exists
        if (!file_exists($fullPath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        // Try ClamAV first
        if ($this->isClamAvAvailable()) {
            return $this->scanWithClamAv($fullPath);
        }

        // Fallback to basic pattern scanning
        return $this->basicPatternScan($fullPath);
    }

    /**
     * Check if ClamAV is available
     */
    private function isClamAvAvailable(): bool
    {
        $output = shell_exec('which clamscan 2>/dev/null');
        return !empty($output);
    }

    /**
     * Scan with ClamAV
     */
    private function scanWithClamAv(string $filePath): array
    {
        $command = "clamscan --no-summary --infected {$filePath} 2>&1";
        $output = shell_exec($command);
        $exitCode = 0;

        // Parse ClamAV output
        if (str_contains($output, 'FOUND')) {
            $virusName = $this->extractVirusName($output);
            return [
                'clean' => false,
                'engine' => 'clamav',
                'threat_name' => $virusName,
                'output' => $output
            ];
        }

        return [
            'clean' => true,
            'engine' => 'clamav',
            'output' => $output
        ];
    }

    /**
     * Basic pattern-based scanning for common threats
     */
    private function basicPatternScan(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $threats = [];

        // Define basic threat patterns
        $patterns = [
            'eicar' => '/X5O!P%@AP\[4\\PZX54\(P\^\)7CC\)7\}\$EICAR-STANDARD-ANTIVIRUS-TEST-FILE!\$H\+H\*/',
            'php_backdoor' => '/(eval|base64_decode|gzinflate|str_rot13)\s*\(\s*[\'"]/i',
            'suspicious_php' => '/\<\?php\s+(eval|system|exec|shell_exec|passthru)/i',
            'javascript_obfuscated' => '/eval\s*\(\s*unescape/i',
            'malicious_vbs' => '/WScript\.Shell|Shell\.Application/i'
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $content)) {
                $threats[] = $name;
            }
        }

        if (!empty($threats)) {
            return [
                'clean' => false,
                'engine' => 'pattern_scanner',
                'threat_name' => implode(', ', $threats),
                'threats' => $threats
            ];
        }

        return [
            'clean' => true,
            'engine' => 'pattern_scanner'
        ];
    }

    /**
     * Extract virus name from ClamAV output
     */
    private function extractVirusName(string $output): string
    {
        if (preg_match('/:\s*(.+?)\s+FOUND/', $output, $matches)) {
            return $matches[1];
        }
        return 'Unknown threat';
    }

    /**
     * Handle infected file
     */
    private function handleInfectedFile(array $scanResult): void
    {
        Log::critical('Virus detected in uploaded file', [
            'file_path' => $this->filePath,
            'threat_name' => $scanResult['threat_name'] ?? 'Unknown',
            'scan_engine' => $scanResult['engine'] ?? 'Unknown'
        ]);

        // Delete infected file
        $this->quarantineFile();

        // Mark in database as infected (if you have a files table)
        $this->markFileAsInfected($scanResult);

        // Alert security team
        $this->alertSecurityTeam($scanResult);
    }

    /**
     * Quarantine/delete infected file
     */
    private function quarantineFile(): void
    {
        try {
            $fullPath = Storage::disk('local')->path($this->filePath);

            // Create quarantine directory if it doesn't exist
            $quarantinePath = storage_path('quarantine');
            if (!is_dir($quarantinePath)) {
                mkdir($quarantinePath, 0700, true);
            }

            // Move file to quarantine
            $quarantineFile = $quarantinePath . '/' . basename($this->filePath) . '.quarantine.' . time();
            rename($fullPath, $quarantineFile);

            Log::info('File quarantined', [
                'original_path' => $this->filePath,
                'quarantine_path' => $quarantineFile
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to quarantine file', [
                'file_path' => $this->filePath,
                'error' => $e->getMessage()
            ]);

            // If quarantine fails, delete the file
            Storage::disk('local')->delete($this->filePath);
        }
    }

    /**
     * Mark file as clean in database
     */
    private function markFileAsClean(): void
    {
        // Update file record in database
        // This is a placeholder - implement according to your file storage schema
        Log::info('File scan completed - clean', [
            'file_path' => $this->filePath
        ]);
    }

    /**
     * Mark file as infected in database
     */
    private function markFileAsInfected(array $scanResult): void
    {
        // Update file record in database
        // This is a placeholder - implement according to your file storage schema
        Log::warning('File marked as infected', [
            'file_path' => $this->filePath,
            'scan_result' => $scanResult
        ]);
    }

    /**
     * Mark file as unsafe due to scan error
     */
    private function markFileAsUnsafe(string $reason): void
    {
        Log::warning('File marked as unsafe due to scan error', [
            'file_path' => $this->filePath,
            'reason' => $reason
        ]);
    }

    /**
     * Alert security team about infected file
     */
    private function alertSecurityTeam(array $scanResult): void
    {
        // Send alert to security team
        // Implement integration with your alerting system

        Log::critical('SECURITY ALERT: Infected file detected', [
            'file_path' => $this->filePath,
            'threat_details' => $scanResult,
            'timestamp' => now(),
            'requires_investigation' => true
        ]);

        // TODO: Send to Slack, email, PagerDuty, etc.
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Virus scan job failed permanently', [
            'file_path' => $this->filePath,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts
        ]);

        // Mark file as unsafe due to scan failure
        $this->markFileAsUnsafe('Scan job failed: ' . $exception->getMessage());
    }
}
