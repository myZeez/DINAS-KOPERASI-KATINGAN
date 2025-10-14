<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes, LogsActivity;
    /**
     * Status constants to avoid magic strings.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'content',
        'image',
        'status',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where('published_at', '<=', now());
    }

    /**
     * Helper to know if the news is currently visible publicly.
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED && $this->published_at && $this->published_at->lte(now());
    }
}
