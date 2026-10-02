<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Creator = 'creator';
    case User = 'user';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Creator => 'Creator Studio',
            self::User => 'Standard User',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::SuperAdmin => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
            self::Creator => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            self::User => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
        };
    }
}
