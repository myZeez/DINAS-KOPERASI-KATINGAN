<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class CheckNewsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check news data in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== CEK DATA NEWS DI MYSQL ===');

        // Total news
        $totalNews = News::count();
        $this->info("Total news: " . $totalNews);

        // Published news
        $publishedNews = News::published()->count();
        $this->info("Published news: " . $publishedNews);

        // All news with details
        $this->info("\n=== DETAIL NEWS ===");
        $allNews = News::all();
        foreach ($allNews as $news) {
            $this->line("ID: " . $news->id);
            $this->line("Title: " . $news->title);
            $this->line("Status: " . $news->status);
            $this->line("Published At: " . $news->published_at);
            $this->line("Created At: " . $news->created_at);
            $this->line("---");
        }

        // Test published scope
        $this->info("\n=== PUBLISHED NEWS DENGAN SCOPE ===");
        $publishedNewsList = News::published()->get();
        foreach ($publishedNewsList as $news) {
            $this->line("Published: " . $news->title . " - " . $news->published_at);
        }

        $this->info("\nSelesai!");

        return 0;
    }
}
