<?php

namespace App\Enums;

enum SystemVariable: string
{
    case PUBLIC = 'public';

    case USER = 'user';

    case ADMIN = 'admin';
}
