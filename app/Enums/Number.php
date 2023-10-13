<?php

declare(strict_types=1);

namespace App\Enums;

enum Number: string
{
    case One = '1';
    case Two = '2';
    case Three = '3';
    case Four = '4';

    public function toString(): string
    {
        return match ($this) {
            self::One => 'Číslo 1',
            self::Two => 'Číslo 2',
            self::Three => 'Číslo 3',
            self::Four => 'Číslo 4',
        };
    }
}
