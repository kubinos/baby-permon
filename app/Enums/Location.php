<?php

declare(strict_types=1);

namespace App\Enums;

enum Location: string
{
    case ColorWorld = 'color-world';
    case UnderWorld = 'under-world';
    case KineticActivity = 'kinetic-activity';
    case PictureActivity = 'picture-activity';
}
