<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyReport extends Model
{
    protected $fillable = [
        'report_number',
        'guard_id',
        'patrol_id',
        'type',
        'description',
        'latitude',
        'longitude',
        'photo',
        'status',
        'responded_at',
        'responded_by',
        'response_notes',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'responded_at' => 'datetime',
        ];
    }

    public function guardRel()
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    public function patrol()
    {
        return $this->belongsTo(Patrol::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}
