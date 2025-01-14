<?php

use App\Enums\PriorityCommodities;
use App\Enums\SystemVariable;

return [
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
        'search' => 'sometimes|string',
        'filter' => 'sometimes|string',
        'is_exact' => 'sometimes|string|in:true,false',
    ],
    'filtering_parameters' => [
        'geo_location_filter' => 'sometimes|string|in:region,province,city',
        'geo_location_value' => 'sometimes|string',
        'is_exact' => 'sometimes|string|in:true,false',
        'commodity' => 'sometimes|string',
        'filter_by_parent_id' => 'sometimes|integer',
        'filter_by_parent_column' => 'sometimes|string',
    ]
];
