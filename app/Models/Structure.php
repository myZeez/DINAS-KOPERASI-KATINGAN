<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Structure extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'position',
        'name',
        'nip',
        'rank',
        'level',
        'parent_id',
        'sort_order',
        'photo',
        'is_active',
        'is_plt',
        'plt_from_structure_id',
        'plt_name',
        'plt_nip',
        'plt_rank',
        'plt_start_date',
        'plt_end_date',
        'plt_notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_plt' => 'boolean',
        'plt_start_date' => 'date',
        'plt_end_date' => 'date'
    ];

    // Relationship untuk parent structure
    public function parent()
    {
        return $this->belongsTo(Structure::class, 'parent_id');
    }

    // Relationship untuk child structures
    public function children()
    {
        return $this->hasMany(Structure::class, 'parent_id')->orderBy('sort_order');
    }

    // Relationship untuk PLT dari struktur lain
    public function pltFromStructure()
    {
        return $this->belongsTo(Structure::class, 'plt_from_structure_id');
    }

    // Relationship untuk struktur yang dijabat sebagai PLT
    public function structuresAsPlt()
    {
        return $this->hasMany(Structure::class, 'plt_from_structure_id');
    }

    // Scope untuk struktur aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Scope berdasarkan level
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Scope untuk root elements (tanpa parent)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // Get hierarchy tree
    public static function getHierarchyTree()
    {
        return static::with('children.children.children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
    }
}
