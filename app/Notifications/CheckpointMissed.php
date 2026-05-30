<?php

namespace App\Notifications;

use App\Models\Checkpoint;
use App\Models\Guard;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CheckpointMissed extends Notification
{
    use Queueable;

    public function __construct(
        public Checkpoint $checkpoint,
        public Guard $guard
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Checkpoint Terlewat',
            'message' => "Checkpoint {$this->checkpoint->name} oleh {$this->guard->full_name} belum discan",
            'checkpoint_id' => $this->checkpoint->id,
            'guard_name' => $this->guard->full_name,
            'time' => now()->toDateTimeString(),
        ];
    }
}
