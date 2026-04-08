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

    /*
    |--------------------------------------------------------------------------
    | Chat Endpoint Security
    |--------------------------------------------------------------------------
    |
    | These settings help reduce abuse when the public site proxies requests to
    | an internal OpenAI-compatible model endpoint.
    */

    'allowed_origins' => array_values(array_filter(array_map(
        static fn (string $origin): string => rtrim(trim($origin), '/'),
        explode(',', (string) env('OPENAI_ALLOWED_ORIGINS', rtrim((string) env('APP_URL', ''), '/')))
    ))),
    'rate_limit_per_minute' => (int) env('OPENAI_RATE_LIMIT_PER_MINUTE', 10),
    'rate_limit_per_hour' => (int) env('OPENAI_RATE_LIMIT_PER_HOUR', 100),
    'log_queries' => filter_var(env('OPENAI_LOG_QUERIES', false), FILTER_VALIDATE_BOOL),
];
