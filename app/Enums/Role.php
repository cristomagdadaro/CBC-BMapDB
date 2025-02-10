<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'Administrator';

    case FOCAL_PERSON = 'Focal Person'; // for monitoring the breeders and commodities under its responsibility agency or institute

    case BREEDER = 'Breeder';

    case RESEARCHER = 'Researcher';

    case PUBLIC = 'Public';

    case TWG_MANAGER = 'TWG Manager'; // for TWG Database role
}
