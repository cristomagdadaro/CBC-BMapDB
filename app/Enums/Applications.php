<?php

namespace App\Enums;

enum Applications: string
{
    case BREEDERS_MAP = 'Plant Breeders Map';
    case BREEDERS_MAP_ROUTE = 'projects.breedersmap.index';

    case BREEDERS_MAP_ROUTE_PUBLIC = 'projects.breedersmap.public';

    case BREEDERS_MAP_DESC = 'Centralized repository for crop biotechnology commodities, providing essential data and resources in one accessible platform. It includes comprehensive information on germplasm, genetic traits, NSIC registrations, plant variety protections, and GM regulatory approvals. This tool empowers breeders and researchers to drive innovation, improve crop productivity, and promote sustainable agriculture.';

    case BREEDERS_MAP_LOGO = '/img/logos/pbmap.png';

    case TWG_DATABASE = 'Biotech TWG Database';
    case TWG_DATABASE_ROUTE = 'projects.twg.index';

    case TWG_DATABASE_ROUTE_PUBLIC = 'projects.twgdb.public';

    case TWG_DATABASE_DESC = 'A robust system designed to manage and organize biotechnology-related projects efficiently. It serves as a centralized hub for storing, tracking, and accessing essential data from various technical working groups. This database enhances collaboration, streamlines project monitoring, and supports informed decision-making in the field of biotechnology.';

    case  TWG_DATABASE_LOGO = '/img/logos/biotwg.png';
}
