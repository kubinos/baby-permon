<?php

declare(strict_types=1);

namespace App\Enums;

enum Difficulty: int
{
    case Beginner = 1;
    case Easy     = 2;
    case Medium   = 3;
    case Hard     = 4;
    case Expert   = 5;

    public function toString(): string
    {
        return match ($this) {
            self::Beginner => '1 Nejjednodušší',
            self::Easy => '2 Jednoduchý',
            self::Medium => '3 Střední',
            self::Hard => '4 Těžký',
            self::Expert => '5 Nejtěžší',
        };
    }
}
