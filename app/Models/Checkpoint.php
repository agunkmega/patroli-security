<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Checkpoint extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'area_id',
        'latitude',
        'longitude',
        'radius',
        'qr_code',
        'qr_path',
        'order',
        'notes',
        'instruction',
        'require_photo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'radius' => 'decimal:2',
            'require_photo' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function patrolLogs()
    {
        return $this->hasMany(PatrolLog::class);
    }

    public function qrCodeUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->qr_path ? asset('storage/' . $this->qr_path) : null,
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
