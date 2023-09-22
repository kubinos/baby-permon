<?php

declare(strict_types=1);

namespace App\Enums;

enum Difficulty: int
{
    case Beginner = 1;
    case Easy     = 2;
    case Medium   = 3;
    case Hard     = 4;
    case Expert   = 5;

    public function toString(): string
    {
        return str_repeat('*', $this->value);
    }
}
