<?php

namespace Modules\PbMap\Enums;

enum Permissions: string
{
    case CREATE_BREEDER = "create-breeder";
    case UPDATE_BREEDER = "update-breeder";
    case DELETE_BREEDER = "delete-breeder";
    case READ_BREEDER = "read-breeder";

    case CREATE_COMMODITY = "create-commodity";
    case UPDATE_COMMODITY = "update-commodity";
    case DELETE_COMMODITY = "delete-commodity";
    case READ_COMMODITY = "read-commodity";

    case CREATE_COMMODITY_REQUEST = "create-commodity-request";
    case READ_COMMODITY_REQUEST = "read-commodity-request";
    case UPDATE_COMMODITY_REQUEST = "update-commodity-request";
    case DELETE_COMMODITY_REQUEST = "delete-commodity-request";
}
