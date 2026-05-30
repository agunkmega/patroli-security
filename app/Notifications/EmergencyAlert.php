<?php

namespace App\Notifications;

use App\Models\EmergencyReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmergencyAlert extends Notification
{
    use Queueable;

    public function __construct(
        public EmergencyReport $report
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Emergency: ' . strtoupper($this->report->type),
            'message' => "Guard {$this->report->guardRel->full_name} melaporkan {$this->report->type}",
            'report_id' => $this->report->id,
            'type' => $this->report->type,
            'latitude' => $this->report->latitude,
            'longitude' => $this->report->longitude,
            'time' => $this->report->created_at->toDateTimeString(),
        ];
    }
}
