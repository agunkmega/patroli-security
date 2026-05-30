<?php

namespace App\Models;

use App\Enums\PatrolStatus;
use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    protected $fillable = [
        'patrol_number',
        'guard_id',
        'area_id',
        'schedule_id',
        'start_time',
        'end_time',
        'total_checkpoints',
        'scanned_checkpoints',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'status' => PatrolStatus::class,
            'total_checkpoints' => 'integer',
            'scanned_checkpoints' => 'integer',
        ];
    }

    public function guardRel()
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function logs()
    {
        return $this->hasMany(PatrolLog::class);
    }

    public function progressPercent(): float
    {
        if ($this->total_checkpoints === 0) return 0;
        return round(($this->scanned_checkpoints / $this->total_checkpoints) * 100, 1);
    }

    public function isCompleted(): bool
    {
        return $this->status === PatrolStatus::Completed;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }

    public function scopeActive($query)
    {
        return $query->where('status', PatrolStatus::InProgress);
    }
}
