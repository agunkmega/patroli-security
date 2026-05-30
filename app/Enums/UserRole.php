<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Supervisor = 'supervisor';
    case Guard = 'guard';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Supervisor => 'Supervisor',
            self::Guard => 'Security Guard',
        };
    }
}
