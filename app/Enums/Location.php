<?php

declare(strict_types=1);

namespace App\Enums;

enum Location: string
{
    case ColorWorld = 'color-world';
    case UnderWorld = 'under-world';
    case KineticActivity = 'kinetic-activity';
    case PictureActivity = 'picture-activity';

    public function toString(): string
    {
        return match ($this) {
            self::ColorWorld => 'Barvosvět',
            self::UnderWorld => 'Podzemí',
            self::KineticActivity => 'Kinetická aktivita',
            self::PictureActivity => 'Obrázková aktivita',
        };
    }
}
