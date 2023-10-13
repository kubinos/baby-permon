<?php

declare(strict_types=1);

namespace App\Enums;

enum Color: string
{
    case Red = 'red';
    case Green = 'green';
    case Blue = 'blue';
    case Yellow = 'yellow';

    public function toString(): string
    {
        return match ($this) {
            self::Red => 'Červená',
            self::Green => 'Zelená',
            self::Blue => 'Modrá',
            self::Yellow => 'Žlutá',
        };
    }
}
