<?php

declare(strict_types=1);

namespace App\Enums;

enum Shape: string
{
    case Star = 'star';
    case Circle = 'circle';
    case Square = 'square';
    case Rectangle = 'rectangle';

    public function toString(): string
    {
        return match ($this) {
            self::Star => 'Hvězda',
            self::Circle => 'Kruh',
            self::Square => 'Čtverec',
            self::Rectangle => 'Obdelník',
        };
    }
}
