<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SimpleImageCompressionService
{
    /**
     * Compress and save uploaded image using PHP built-in functions
     * Target size: 100KB - 250KB
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
        $filename = time() . '_' . Str::random(10) . '.jpg';
        $path = $directory . '/' . $filename;
        
        // Get image info
        $imageInfo = getimagesize($file->getRealPath());
        if (!$imageInfo) {
            throw new \Exception("Invalid image file");
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $imageType = $imageInfo[2];
        
        // Create image resource based on type
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($file->getRealPath());
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($file->getRealPath());
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($file->getRealPath());
                break;
            default:
                throw new \Exception("Unsupported image type");
        }
        
        if (!$source) {
            throw new \Exception("Failed to create image resource");
        }
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        if ($ratio > 1) {
            $ratio = 1; // Don't upscale
        }
        
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);
        
        // Create new image
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        
        // Handle transparency for PNG
        if ($imageType == IMAGETYPE_PNG) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefill($destination, 0, 0, $transparent);
        }
        
        // Resize image
        imagecopyresampled(
            $destination, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );
        
        // Start with quality 85 and adjust based on file size
        $quality = 85;
        $targetSizeBytes = $targetSizeKB * 1024;
        $minQuality = 60;
        $maxQuality = 95;
        
        $attempts = 0;
        $maxAttempts = 10;
        
        do {
            // Capture output
            ob_start();
            imagejpeg($destination, null, $quality);
            $imageData = ob_get_contents();
            ob_end_clean();
            
            $currentSize = strlen($imageData);
            
            // If size is within target range (100KB - 250KB), save it
            if ($currentSize <= $targetSizeBytes && $currentSize >= (100 * 1024)) {
                break;
            }
            
            // If too large, reduce quality
            if ($currentSize > $targetSizeBytes) {
                $quality -= 5;
            } 
            // If too small and quality can be increased
            elseif ($currentSize < (100 * 1024) && $quality < $maxQuality) {
                $quality += 5;
            } else {
                break;
            }
            
            $attempts++;
            
        } while ($quality >= $minQuality && $quality <= $maxQuality && $attempts < $maxAttempts);
        
        // Save to storage
        Storage::disk('public')->put($path, $imageData);
        
        // Clean up memory
        imagedestroy($source);
        imagedestroy($destination);
        
        return $path;
    }

    /**
     * Compress existing image file
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

        $fullPath = Storage::disk('public')->path($existingPath);
        
        // Get image info
        $imageInfo = getimagesize($fullPath);
        if (!$imageInfo) {
            throw new \Exception("Invalid image file: " . $existingPath);
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $imageType = $imageInfo[2];
        
        // Create image resource
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($fullPath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($fullPath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($fullPath);
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($fullPath);
                break;
            default:
                throw new \Exception("Unsupported image type for: " . $existingPath);
        }
        
        if (!$source) {
            throw new \Exception("Failed to create image resource for: " . $existingPath);
        }
        
        // Resize if too large
        $maxWidth = 1200;
        $maxHeight = 800;
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        if ($ratio > 1) {
            $ratio = 1; // Don't upscale
        }
        
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);
        
        // Create new image
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        
        // Resize image
        imagecopyresampled(
            $destination, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );
        
        // Compress with target size
        $quality = 85;
        $targetSizeBytes = $targetSizeKB * 1024;
        $minQuality = 60;
        $maxQuality = 95;
        
        $attempts = 0;
        $maxAttempts = 10;
        
        do {
            ob_start();
            imagejpeg($destination, null, $quality);
            $imageData = ob_get_contents();
            ob_end_clean();
            
            $currentSize = strlen($imageData);
            
            if ($currentSize <= $targetSizeBytes && $currentSize >= (100 * 1024)) {
                break;
            }
            
            if ($currentSize > $targetSizeBytes) {
                $quality -= 5;
            } elseif ($currentSize < (100 * 1024) && $quality < $maxQuality) {
                $quality += 5;
            } else {
                break;
            }
            
            $attempts++;
            
        } while ($quality >= $minQuality && $quality <= $maxQuality && $attempts < $maxAttempts);
        
        // Save compressed image
        Storage::disk('public')->put($existingPath, $imageData);
        
        // Clean up memory
        imagedestroy($source);
        imagedestroy($destination);
        
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
     * Check if GD extension is available
     *
     * @return bool
     */
    public function isGdAvailable(): bool
    {
        return extension_loaded('gd') && function_exists('gd_info');
    }
}