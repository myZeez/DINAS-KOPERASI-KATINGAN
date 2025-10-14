<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // Cache untuk head of office data
    protected $headOfOfficeCache;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'vision',
        'mission',
        'detail',
        'quotes',
        'tujuan',
        'tentang',
        'tugas_pokok',
        'peran',
        'fokus_utama',
        'logo',
        'latitude',
        'longitude',
        'operating_hours'
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Get the first (main) profile
    public static function getMain()
    {
        return static::first() ?: new static();
    }

    // Get the head of office from structure table
    public function getHeadOfOffice()
    {
        if (!isset($this->headOfOfficeCache)) {
            $this->headOfOfficeCache = \App\Models\Structure::where('position', 'like', '%kepala dinas%')
                                       ->orWhere('position', 'like', '%kepala%')
                                       ->whereNotNull('photo') // Prioritas yang ada foto
                                       ->orderBy('created_at', 'desc')
                                       ->first();

            // Jika tidak ada yang punya foto, ambil yang manapun
            if (!$this->headOfOfficeCache) {
                $this->headOfOfficeCache = \App\Models\Structure::where('position', 'like', '%kepala dinas%')
                                           ->orWhere('position', 'like', '%kepala%')
                                           ->orderBy('created_at', 'desc')
                                           ->first();
            }
        }

        return $this->headOfOfficeCache;
    }

    // Get head name dynamically
    public function getHeadNameAttribute()
    {
        $headStructure = $this->getHeadOfOffice();
        return $headStructure ? $headStructure->name : null;
    }

    // Get head position dynamically
    public function getHeadPositionAttribute()
    {
        $headStructure = $this->getHeadOfOffice();
        return $headStructure ? $headStructure->position : null;
    }

    // Get head photo dynamically
    public function getHeadPhotoAttribute()
    {
        $headStructure = $this->getHeadOfOffice();
        return $headStructure && $headStructure->photo ? $headStructure->photo : null;
    }
}
