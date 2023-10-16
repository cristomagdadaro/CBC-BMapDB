<?php

use App\Enums\SystemVariable;

return [
    'access_levels' => [
        SystemVariable::ADMIN,
        SystemVariable::USER,
        SystemVariable::PUBLIC,
    ],
    'permissions' => [
        SystemVariable::CREATE,
        SystemVariable::READ,
        SystemVariable::UPDATE,
        SystemVariable::DELETE,
    ],
];
