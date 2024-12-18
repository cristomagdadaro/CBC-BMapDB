<?php

namespace App\Enums;

enum Applications: string
{
    case BREEDERS_MAP = 'Plant Breeders Map';
    case BREEDERS_MAP_ROUTE = 'projects.twg.index';

    case TWG_DATABASE = 'Biotech TWG Database';
    case TWG_DATABASE_ROUTE = 'projects.breedersmap.index';
}
