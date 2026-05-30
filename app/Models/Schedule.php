<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'name',
        'guard_id',
        'area_id',
        'shift',
        'start_time',
        'end_time',
        'date',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'date' => 'date',
            'is_active' => 'boolean',
            'guard_id' => 'integer',
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

    public function checkpoints()
    {
        return $this->belongsToMany(Checkpoint::class, 'schedule_checkpoints')
            ->withPivot('order')
            ->orderBy('order');
    }
}
