<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes, LogsActivity;
    protected $fillable = [
        'name',
        'email',
        'rating',
        'comment',
        'is_visible',
        'is_verified',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
        'is_verified' => 'boolean',
    ];

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
