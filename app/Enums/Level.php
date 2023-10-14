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
            self::One => 'Maláčci (3+ do 4 let)',
            self::Two => 'Předškoláčci (4+ do 6 let)',
            self::Three => 'Školáci (6+ let)',
        };
    }
}
