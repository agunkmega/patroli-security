<?php

namespace App\Enums;

enum PatrolStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Missed = 'missed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
            self::Missed => 'Missed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::InProgress => 'yellow',
            self::Completed => 'green',
            self::Missed => 'red',
            self::Cancelled => 'gray',
        };
    }
}
