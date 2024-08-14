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

    case CREATE_BREEDER = "create-breeder";
    case UPDATE_BREEDER = "update-breeder";
    case DELETE_BREEDER = "delete-breeder";
    case READ_BREEDER = "read-breeder";

    case CREATE_COMMODITY = "create-commodity";
    case UPDATE_COMMODITY = "update-commodity";
    case DELETE_COMMODITY = "delete-commodity";
    case READ_COMMODITY = "read-commodity";

    case CREATE_TWG_EXPERT = "create-twg-expert";
    case UPDATE_TWG_EXPERT = "update-twg-expert";
    case DELETE_TWG_EXPERT = "delete-twg-expert";
    case READ_TWG_EXPERT = "read-twg-expert";

    case CREATE_TWG_SERVICE = "create-twg-service";
    case UPDATE_TWG_SERVICE = "update-twg-service";
    case DELETE_TWG_SERVICE = "delete-twg-service";
    case READ_TWG_SERVICE = "read-twg-service";

    case CREATE_TWG_PRODUCT = "create-twg-product";
    case UPDATE_TWG_PRODUCT = "update-twg-product";
    case DELETE_TWG_PRODUCT = "delete-twg-product";
    case READ_TWG_PRODUCT = "read-twg-product";

    case CREATE_TWG_PROJECT = "create-twg-project";
    case UPDATE_TWG_PROJECT = "update-twg-project";
    case DELETE_TWG_PROJECT = "delete-twg-project";
    case READ_TWG_PROJECT = "read-twg-project";

}
