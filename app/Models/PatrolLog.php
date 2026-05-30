<?php

namespace App\Models;

use App\Enums\CheckpointStatus;
use Illuminate\Database\Eloquent\Model;

class PatrolLog extends Model
{
    protected $table = 'patrol_logs';

    protected $fillable = [
        'patrol_id',
        'checkpoint_id',
        'guard_id',
        'scan_time',
        'latitude',
        'longitude',
        'distance',
        'photo',
        'notes',
        'status',
        'condition',
        'device_info',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'scan_time' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'distance' => 'decimal:2',
            'status' => CheckpointStatus::class,
        ];
    }

    public function patrol()
    {
        return $this->belongsTo(Patrol::class);
    }

    public function checkpoint()
    {
        return $this->belongsTo(Checkpoint::class);
    }

    public function guardRel()
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scan_time', today());
    }
}
