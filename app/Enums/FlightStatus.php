<?php

namespace App\Enums;

enum FlightStatus: string
{
    case Scheduled = 'scheduled'; // Flight created and planned
    case Delayed   = 'delayed';   // Flight pushed back from its original schedule
    case Departed  = 'departed';  // Flight has taken off
    case Completed = 'completed'; // Flight has landed
    case Cancelled = 'cancelled'; // Flight was canceled

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
