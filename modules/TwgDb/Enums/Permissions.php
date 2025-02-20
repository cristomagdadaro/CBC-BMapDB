<?php

namespace Modules\TwgDb\Enums;

enum Permissions: string
{
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
