<?php

use App\Enums\Applications;
use App\Enums\DataViews;
use App\Enums\Role;

return [
    'applications' => [
        Applications::TWG_DATABASE->name => [
            'name' => Applications::TWG_DATABASE->value,
            'route' => Applications::TWG_DATABASE_ROUTE->value,
            'route_public' => Applications::TWG_DATABASE_ROUTE_PUBLIC->value,
            'description' => Applications::TWG_DATABASE_DESC->value,
            'logo' => Applications::TWG_DATABASE_LOGO->value,
        ],
        Applications::BREEDERS_MAP->name => [
            'name' => Applications::BREEDERS_MAP->value,
            'route' => Applications::BREEDERS_MAP_ROUTE->value,
            'route_public' => Applications::BREEDERS_MAP_ROUTE_PUBLIC->value,
            'description' => Applications::BREEDERS_MAP_DESC->value,
            'logo' => Applications::BREEDERS_MAP_LOGO->value,
        ],
    ],
    'commodities' => [
        'Rice' => 'Oryza sativa',
        'Corn' => 'Zea mays',
        'Cotton' => 'Gossypium',
        'Tomato' => 'Solanum lycopersicum',
        'Eggplant' => 'Solanum melongena',
        'Rubber' => 'Hevea brasiliensis',
        'Adlay' => 'Coix lacryma-jobi',
        'Mango' => 'Mangifera indica',

        'Quinoa' => 'Chenopodium quinoa',
        'Abaca' => 'Musa textilis',
        'Banana' => 'Musa spp.',
        'Coffee' => 'Coffea arabica',
        'Coconut' => 'Cocos nucifera',
        'Sugarcane' => 'Saccharum officinarum',
        'Cacao' => 'Theobroma cacao',
        'Pineapple' => 'Ananas comosus',

        'Cassava' => 'Manihot esculenta',
        'Sweet Potato' => 'Ipomoea batatas',
        'Peanut' => 'Arachis hypogaea',
        'Papaya' => 'Carica papaya',
        'Durian' => 'Durio zibethinus',
        'Jackfruit' => 'Artocarpus heterophyllus',
        'Soybean' => 'Glycine max',
        'Mungbean' => 'Vigna radiata',
        'Taro' => 'Colocasia esculenta',
        'Yam' => 'Dioscorea alata',
        'Black Pepper' => 'Piper nigrum',
        'Ginger' => 'Zingiber officinale',
        'Onion' => 'Allium cepa',
        'Garlic' => 'Allium sativum'
    ],
    'dataview_guards' => [
        DataViews::PUBLIC->value,
        DataViews::PRIVATE->value,
        //DataViews::INSTITUTIONAL->value,
        DataViews::SYSTEM->value
    ],
    'roles' => [
        Role::ADMIN->value,
        Role::BREEDER->value,
        Role::FOCAL_PERSON->value,
        Role::TWG_MANAGER->value,
        Role::RESEARCHER->value,
    ],
    'paginate_parameters' => [
        'page' => 'sometimes|integer|min:1',
        'per_page' => 'sometimes|string',
        'sort' => 'sometimes|string',
        'order' => 'sometimes|string|in:asc,desc',
        'search' => 'sometimes',
        'filter' => 'sometimes|string',
        'is_exact' => 'sometimes|string|in:true,false,0,1',
        'paginate' => 'sometimes|string|in:true,false,0,1',
    ],
    'filtering_parameters' => [
        'not' => 'sometimes|string',
        'exact' => 'sometimes|string',
        'or' => 'sometimes|string',
        'geo_location_filter' => 'sometimes|string|in:region,province,city,institute',
        'geo_location_value' => 'sometimes|nullable|string',
        'is_exact' => 'sometimes|string|in:true,false,0,1',
        'commodity' => 'sometimes|string',
        'filter_by_parent_id' => 'sometimes|integer',
        'filter_by_parent_column' => 'sometimes|string',
        'scope_by' => 'sometimes|string',
    ],
    'appendable_parameters' => [
        'with' => 'sometimes|string',
        'count' => 'sometimes|string',
    ]
];
