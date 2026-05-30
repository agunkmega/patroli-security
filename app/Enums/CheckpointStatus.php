<?php

namespace App\Enums;

enum CheckpointStatus: string
{
    case Safe = 'safe';
    case Unsafe = 'unsafe';
    case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Safe => 'Aman',
            self::Unsafe => 'Tidak Aman',
            self::Pending => 'Pending',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Safe => 'green',
            self::Unsafe => 'red',
            self::Pending => 'yellow',
        };
    }
}
