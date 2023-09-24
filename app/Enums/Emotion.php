<?php

declare(strict_types=1);

namespace App\Enums;

enum Emotion: string
{
    // todo: make a proper enum

    case Happy = 'happy';
    case Sad = 'sad';
    case Angry = 'angry';
}
