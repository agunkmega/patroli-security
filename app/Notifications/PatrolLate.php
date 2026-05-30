<?php

namespace App\Notifications;

use App\Models\Guard;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PatrolLate extends Notification
{
    use Queueable;

    public function __construct(
        public Guard $guard,
        public string $scheduleTime
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Patroli Terlambat',
            'message' => "Guard {$this->guard->full_name} terlambat patroli dari jadwal {$this->scheduleTime}",
            'guard_name' => $this->guard->full_name,
            'time' => now()->toDateTimeString(),
        ];
    }
}
