<?php

declare(strict_types=1);

namespace App\Enums;

enum Shape: string
{
    case Star = 'star';
    case Circle = 'circle';
    case Square = 'square';
    case Rectangle = 'rectangle';
}
