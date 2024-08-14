<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'Administrator';

    case TWG_ADMIN = 'TWG Admin';

    case BREEDER = 'Breeder';

    case RESEARCHER = 'Researcher';

    case PUBLIC = 'Public';
}
