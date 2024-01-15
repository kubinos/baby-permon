<?php

declare(strict_types=1);

namespace App\Enums;

enum Emotion: string
{
    case Angry = 'angry';
    case Sad = 'sad';
    case Happy = 'happy';
    case Rostak = 'rostak';
    case Zadumanek = 'zadumanek';
    case Bojanek = 'bojanek';

    public function toString(): string
    {
        return match ($this) {
            self::Angry => 'Zlobílek',
            self::Sad => 'Uplakánek',
            self::Rostak => 'Rošťáček',
            self::Happy => 'Pohodáček',
            self::Zadumanek => 'Zadumánek',
            self::Bojanek => 'Bojánek',
        };
    }
}
