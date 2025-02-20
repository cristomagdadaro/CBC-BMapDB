<?php

namespace App\Enums;

enum Permission: string
{
    case CREATE_USER = "create-user";
    case UPDATE_USER = "update-user";
    case DELETE_USER = "delete-user";
    case READ_USER = "read-user";

    case CREATE_APP_ACCOUNT = "create-app-account";
    case UPDATE_APP_ACCOUNT = "update-app-account";
    case DELETE_APP_ACCOUNT = "delete-app-account";
    case READ_APP_ACCOUNT = "read-app-account";

    case CREATE_APP = "create-app";
    case UPDATE_APP = "update-app";
    case DELETE_APP = "delete-app";
    case READ_APP = "read-app";

}
