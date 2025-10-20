<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BasicImageCompressionService;
use Illuminate\Support\Facades\Storage;

class CompressExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:compress 
                            {directory=images : The directory to compress images in}
                            {--target-size=200 : Target file size in KB}
                            {--dry-run : Show what would be compressed without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress existing images in storage to reduce file sizes';

    protected $imageCompressionService;

    public function __construct(BasicImageCompressionService $imageCompressionService)
    {
        parent::__construct();
        $this->imageCompressionService = $imageCompressionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = $this->argument('directory');
        $targetSize = $this->option('target-size');
        $dryRun = $this->option('dry-run');

        $this->info("Scanning directory: {$directory}");
        $this->info("Target size: {$targetSize}KB");
        
        if ($dryRun) {
            $this->warn("DRY RUN MODE - No files will be modified");
        }

        // Get all files in directory
        $allFiles = Storage::disk('public')->allFiles($directory);
        $imageFiles = array_filter($allFiles, function($file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        if (empty($imageFiles)) {
            $this->warn("No image files found in directory: {$directory}");
            return;
        }

        $this->info("Found " . count($imageFiles) . " image files");

        $processedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;
        $totalSaved = 0;

        $progressBar = $this->output->createProgressBar(count($imageFiles));
        $progressBar->start();

        foreach ($imageFiles as $file) {
            try {
                $originalSize = $this->imageCompressionService->getFileSizeKB($file);
                
                if ($originalSize > $targetSize) {
                    if (!$dryRun) {
                        $this->imageCompressionService->compressExisting($file, $targetSize);
                        $newSize = $this->imageCompressionService->getFileSizeKB($file);
                        $saved = $originalSize - $newSize;
                        $totalSaved += $saved;
                    } else {
                        $saved = 0;
                        $newSize = $originalSize;
                    }
                    
                    $processedCount++;
                    
                    if ($this->output->isVerbose()) {
                        $this->line("");
                        $this->info("Processed: {$file}");
                        $this->line("  Original: " . round($originalSize, 2) . "KB");
                        if (!$dryRun) {
                            $this->line("  New: " . round($newSize, 2) . "KB");
                            $this->line("  Saved: " . round($saved, 2) . "KB");
                        }
                    }
                } else {
                    $skippedCount++;
                    
                    if ($this->output->isVerbose()) {
                        $this->line("");
                        $this->comment("Skipped (already optimized): {$file}");
                        $this->line("  Size: " . round($originalSize, 2) . "KB");
                    }
                }
            } catch (\Exception $e) {
                $errorCount++;
                
                if ($this->output->isVerbose()) {
                    $this->line("");
                    $this->error("Error processing {$file}: " . $e->getMessage());
                }
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line("");
        $this->line("");

        // Summary
        $this->info("=== COMPRESSION SUMMARY ===");
        $this->line("Total files scanned: " . count($imageFiles));
        $this->line("Files processed: {$processedCount}");
        $this->line("Files skipped: {$skippedCount}");
        $this->line("Errors: {$errorCount}");
        
        if (!$dryRun && $totalSaved > 0) {
            $this->line("Total space saved: " . round($totalSaved, 2) . "KB (" . round($totalSaved / 1024, 2) . "MB)");
        }

        if ($dryRun && $processedCount > 0) {
            $this->warn("Run without --dry-run to actually compress the files");
        }

        if ($processedCount > 0) {
            $this->info("✅ Compression completed successfully!");
        } else {
            $this->comment("ℹ️  No files needed compression");
        }
    }
}