<?php

declare(strict_types=1);

namespace App\Enums;

enum Level: int
{
    case One = 1;
    case Two = 2;
    case Three = 3;

    public function toString(): string
    {
        return match ($this) {
            self::One => 'Jedna',
            self::Two => 'Dva',
            self::Three => 'Tři',
        };
    }
}
