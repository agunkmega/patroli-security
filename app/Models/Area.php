<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'radius',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'radius' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function activeCheckpoints()
    {
        return $this->hasMany(Checkpoint::class)->where('is_active', true);
    }
}
