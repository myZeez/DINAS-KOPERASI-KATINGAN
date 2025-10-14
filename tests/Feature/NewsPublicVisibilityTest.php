<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class NewsPublicVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for ownership
        User::factory()->create();
    }

    public function test_guest_can_see_published_news()
    {
        $published = News::create([
            'title' => 'Published News',
            'content' => 'Konten published',
            'status' => News::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
            'user_id' => 1,
        ]);

        $draft = News::create([
            'title' => 'Draft News',
            'content' => 'Konten draft',
            'status' => News::STATUS_DRAFT,
            'published_at' => null,
            'user_id' => 1,
        ]);

        $future = News::create([
            'title' => 'Future News',
            'content' => 'Konten future',
            'status' => News::STATUS_PUBLISHED,
            'published_at' => now()->addDay(),
            'user_id' => 1,
        ]);

        $res = $this->get('/public/berita');
        $res->assertStatus(200);
        $res->assertSee('Published News');
        $res->assertDontSee('Draft News');
        $res->assertDontSee('Future News');
    }
}
