<?php

declare(strict_types=1);

namespace App\Enums;

enum GameType: string
{
    case Player = 'player';
    case Accompaniment = 'accompaniment';
    case Playground = 'playground';
    case Operator = 'operator';

    function toString(): string
    {
        return match ($this) {
            self::Player => 'Hráč',
            self::Accompaniment => 'Doprovod',
            self::Playground => 'Přístup do herny',
            self::Operator => 'Operátor',
        };
    }
}
