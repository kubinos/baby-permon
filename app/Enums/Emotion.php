<?php

declare(strict_types=1);

namespace App\Enums;

enum Emotion: string
{
    case Happy = 'happy';
    case Sad = 'sad';
    case Angry = 'angry';

    public function toString(): string
    {
        return match ($this) {
            self::Happy => 'Šťastný',
            self::Sad => 'Smutný',
            self::Angry => 'Naštvaný',
        };
    }
}
