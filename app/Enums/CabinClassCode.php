<?php

namespace App\Enums;

enum CabinClassCode: string
{
    case Economy        = 'Y';
    case PremiumEconomy = 'W';
    case Business       = 'J';
    case First          = 'F';

    public function label(): string
    {
        return match($this) {
            self::Economy        => 'Economy',
            self::PremiumEconomy => 'Premium Economy',
            self::Business       => 'Business',
            self::First          => 'First',
        };
    }
}
