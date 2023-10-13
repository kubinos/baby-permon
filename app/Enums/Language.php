<?php

declare(strict_types=1);

namespace App\Enums;

enum Language: string
{
    case Czech   = 'cs';
    case English = 'en';
    case German  = 'de';

    public function toString(): string
    {
        return match ($this) {
            self::Czech   => 'Čeština',
            self::English => 'English',
            self::German  => 'Deutsch',
        };
    }
}
