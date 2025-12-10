<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Illuminate\Support\Str;

class GenerateNewsSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for all news that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newsWithoutSlug = News::whereNull('slug')->orWhere('slug', '')->get();

        if ($newsWithoutSlug->isEmpty()) {
            $this->info('All news already have slugs!');
            return 0;
        }

        $this->info("Found {$newsWithoutSlug->count()} news without slugs. Generating...");

        $bar = $this->output->createProgressBar($newsWithoutSlug->count());
        $bar->start();

        foreach ($newsWithoutSlug as $news) {
            $slug = Str::slug($news->title);
            $originalSlug = $slug;
            $count = 1;

            while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $news->slug = $slug;
            $news->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully generated slugs for all news!');

        return 0;
    }
}
