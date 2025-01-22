<?php

use App\Enums\Applications;
use App\Enums\SystemVariable;

return [
    'applications' => [
        Applications::BREEDERS_MAP->name => [
            'name' => Applications::BREEDERS_MAP->value,
            'route' => Applications::BREEDERS_MAP_ROUTE->value,
            'route_public' => Applications::BREEDERS_MAP_ROUTE_PUBLIC->value,
            'description' => Applications::BREEDERS_MAP_DESC->value,
        ],
        Applications::TWG_DATABASE->name => [
            'name' => Applications::TWG_DATABASE->value,
            'route' => Applications::TWG_DATABASE_ROUTE->value,
            'route_public' => Applications::TWG_DATABASE_ROUTE_PUBLIC->value,
            'description' => Applications::TWG_DATABASE_DESC->value,
        ],
    ],
    'access_levels' => [
        SystemVariable::ADMIN->value,
        SystemVariable::USER->value,
        SystemVariable::PUBLIC->value,
    ],
    'permissions' => [
        SystemVariable::CREATE->value => [
            'roles' => [
                SystemVariable::ADMIN->value,
                SystemVariable::USER->value,
            ],
        ],
        SystemVariable::READ->value => [
            'roles' => [
                SystemVariable::ADMIN->value,
                SystemVariable::USER->value,
                SystemVariable::PUBLIC->value,
            ],
        ],
        SystemVariable::UPDATE->value => [
            'roles' => [
                SystemVariable::ADMIN->value,
                SystemVariable::USER->value,
            ],
        ],
        SystemVariable::DELETE->value => [
            'roles' => [
                SystemVariable::ADMIN->value,
            ],
        ]
    ],
    'paginate_parameters' => [
        'page' => 'sometimes|integer|min:1',
        'per_page' => 'sometimes|integer|min:1',
        'sort' => 'sometimes|string',
        'order' => 'sometimes|string|in:asc,desc',
        'search' => 'sometimes',
        'filter' => 'sometimes|string',
        'is_exact' => 'sometimes|string|in:true,false',
    ],
    'filtering_parameters' => [
        'geo_location_filter' => 'sometimes|string|in:region,province,city,affiliation',
        'geo_location_value' => 'sometimes|string',
        'is_exact' => 'sometimes|string|in:true,false',
        'commodity' => 'sometimes|string',
        'filter_by_parent_id' => 'sometimes|integer',
        'filter_by_parent_column' => 'sometimes|string',
    ],
    'appendable_parameters' => [
        'with' => 'sometimes|string',
        'count' => 'sometimes|string',
    ]
];
