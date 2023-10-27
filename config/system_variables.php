<?php

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
    ]
];
