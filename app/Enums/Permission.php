<?php

namespace App\Enums;

enum Permission: string
{
    case CREATE_BREEDER = "create-breeder";
    case UPDATE_BREEDER = "update-breeder";
    case DELETE_BREEDER = "delete-breeder";
    case READ_BREEDER = "read-breeder";

    case CREATE_COMMODITY = "create-commodity";
    case UPDATE_COMMODITY = "update-commodity";
    case DELETE_COMMODITY = "delete-commodity";
    case READ_COMMODITY = "read-commodity";
}
