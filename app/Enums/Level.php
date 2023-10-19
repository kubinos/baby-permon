<?php

declare(strict_types=1);

namespace App\Enums;

enum Level: int
{
    case One = 1;
    case Two = 2;
    case Three = 3;

    public function toString(): string
    {
        return match ($this) {
            self::One => 'Maláčci (3+ do 4 let)',
            self::Two => 'Předškoláčci (4+ do 6 let)',
            self::Three => 'Školáci (6+ let)',
        };
    }

    public function rules(): array
    {
        return match ($this) {
            self::One => [
                Location::UnderWorld->value => 1,
                Location::KineticActivity->value => 1,
                Location::PictureActivity->value => 1,
                Location::ColorWorld->value => 1,
            ],
            self::Two => [
                Location::UnderWorld->value => 2,
                Location::KineticActivity->value => 1,
                Location::PictureActivity->value => 1,
                Location::ColorWorld->value => 1,
            ],
            self::Three => [
                Location::UnderWorld->value => 3,
                Location::KineticActivity->value => 1,
                Location::PictureActivity->value => 1,
                Location::ColorWorld->value => 1,
            ],
        };
    }
}
