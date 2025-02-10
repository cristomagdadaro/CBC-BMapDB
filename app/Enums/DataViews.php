<?php

namespace App\Enums;

enum DataViews: string
{
    case ONLYME = 'onlyme'; // only the owner
    case PUBLIC = 'public'; // public domain
    case INSTITUTIONAL = 'institutional';  // within institution only
    case SYSTEM = 'system'; // system-wide across any  databases
}
