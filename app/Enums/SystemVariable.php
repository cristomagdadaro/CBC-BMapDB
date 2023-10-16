<?php

namespace App\Enums;

enum SystemVariable: string
{
    case PUBLIC = 'public';

    case USER = 'user';

    case ADMIN = 'admin';

    case CREATE = 'create';

    case READ = 'read';

    case UPDATE = 'update';

    case DELETE = 'delete';
}
