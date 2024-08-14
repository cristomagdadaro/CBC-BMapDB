<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'Administrator';

    case BREEDER = 'Breeder';

    case RESEARCHER = 'Researcher';

    case PUBLIC = 'Public';
}
