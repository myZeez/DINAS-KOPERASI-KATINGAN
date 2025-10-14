<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'user_id'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user that performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        $class = $this->model_type;
        if (class_exists($class)) {
            return $class::withTrashed()->find($this->model_id);
        }
        return null;
    }

    /**
     * Get formatted description
     */
    public function getFormattedDescriptionAttribute(): string
    {
        $modelName = class_basename($this->model_type);
        $action = $this->action;

        switch ($action) {
            case 'create':
                return "Menambahkan {$modelName} baru";
            case 'update':
                return "Memperbarui {$modelName}";
            case 'delete':
                return "Menghapus {$modelName}";
            case 'restore':
                return "Memulihkan {$modelName}";
            case 'force_delete':
                return "Menghapus permanen {$modelName}";
            default:
                return $this->description ?: "Melakukan {$action} pada {$modelName}";
        }
    }

    /**
     * Get action badge class
     */
    public function getActionBadgeClassAttribute(): string
    {
        switch ($this->action) {
            case 'create':
                return 'bg-success';
            case 'update':
                return 'bg-primary';
            case 'delete':
                return 'bg-warning';
            case 'restore':
                return 'bg-info';
            case 'force_delete':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }
}
