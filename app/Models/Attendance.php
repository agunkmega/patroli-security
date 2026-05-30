<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'guard_id',
        'date',
        'check_in_time',
        'check_out_time',
        'check_in_lat',
        'check_in_lng',
        'check_out_lat',
        'check_out_lng',
        'check_in_photo',
        'check_out_photo',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in_time' => 'datetime',
            'check_out_time' => 'datetime',
            'check_in_lat' => 'decimal:8',
            'check_in_lng' => 'decimal:8',
            'check_out_lat' => 'decimal:8',
            'check_out_lng' => 'decimal:8',
        ];
    }

    public function guardRel()
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }
}
