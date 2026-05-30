<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guard extends Model
{
    protected $table = 'guards';

    protected $fillable = [
        'user_id',
        'guard_number',
        'full_name',
        'nik',
        'phone',
        'address',
        'photo',
        'gender',
        'birth_date',
        'join_date',
        'emergency_contact',
        'emergency_name',
        'blood_type',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'join_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patrols()
    {
        return $this->hasMany(Patrol::class);
    }

    public function patrolLogs()
    {
        return $this->hasMany(PatrolLog::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function emergencyReports()
    {
        return $this->hasMany(EmergencyReport::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function activePatrol()
    {
        return $this->hasOne(Patrol::class)->where('status', 'in_progress');
    }
}
