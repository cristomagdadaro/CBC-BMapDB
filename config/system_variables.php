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
];
