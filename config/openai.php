<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are used when talking to the official OpenAI API.
    | For OpenAI-compatible local deployments such as LLMama, use the
    | base_url / compat_api_key settings below instead.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI-Compatible Endpoint
    |--------------------------------------------------------------------------
    |
    | The chat module can target an OpenAI-compatible endpoint. By default it
    | points to the local LLMama deployment requested for this project.
    */

    'base_url' => rtrim(env('OPENAI_BASE_URL', 'http://192.168.36.10:1234'), '/'),
    'compat_api_key' => env('OPENAI_COMPAT_API_KEY', 'llmama'),
    'chat_model' => env('OPENAI_CHAT_MODEL', env('OPENAI_MODEL', 'qwen/qwen3.5-9b')),
    'provider_name' => env('OPENAI_PROVIDER_NAME', 'LLMama'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout may be used to specify the maximum number of seconds to wait
    | for a response. By default, the client will time out after 30 seconds.
    */

    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 120),
];
