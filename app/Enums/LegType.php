<?php

namespace App\Enums;

enum LegType: string
{
    case Outbound = 'outbound';
    case Return   = 'return';
}
