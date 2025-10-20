<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BasicImageCompressionService
{
    /**
     * Save uploaded image with basic compression
     * Simply stores the file and returns the path
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $targetSizeKB
     * @return string
     */
    public function compressAndSave(
        UploadedFile $file, 
        string $directory = 'images', 
        int $maxWidth = 1200, 
        int $maxHeight = 800,
        int $targetSizeKB = 200
    ): string {
        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        $path = $directory . '/' . $filename;
        
        // Store the file
        $file->storeAs($directory, $filename, 'public');
        
        return $path;
    }

    /**
     * "Compress" existing image (placeholder - just returns path)
     *
     * @param string $existingPath
     * @param int $targetSizeKB
     * @return string
     */
    public function compressExisting(string $existingPath, int $targetSizeKB = 200): string
    {
        if (!Storage::disk('public')->exists($existingPath)) {
            throw new \Exception("File not found: " . $existingPath);
        }

        // For now, just return the existing path
        // TODO: Implement actual compression when GD extension is available
        return $existingPath;
    }

    /**
     * Get file size in KB
     *
     * @param string $path
     * @return float
     */
    public function getFileSizeKB(string $path): float
    {
        if (!Storage::disk('public')->exists($path)) {
            return 0;
        }
        
        return Storage::disk('public')->size($path) / 1024;
    }

    /**
     * Batch process images (placeholder)
     *
     * @param string $directory
     * @param int $targetSizeKB
     * @return array
     */
    public function batchCompress(string $directory = 'images', int $targetSizeKB = 200): array
    {
        $files = Storage::disk('public')->files($directory);
        $results = [];
        
        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $originalSize = $this->getFileSizeKB($file);
                
                $results[] = [
                    'file' => $file,
                    'original_size' => round($originalSize, 2),
                    'new_size' => round($originalSize, 2),
                    'saved' => 0,
                    'status' => 'skipped (compression disabled - GD extension required)'
                ];
            }
        }
        
        return $results;
    }

    /**
     * Check if compression is available
     *
     * @return bool
     */
    public function isCompressionAvailable(): bool
    {
        return false; // Always false for basic service
    }
}