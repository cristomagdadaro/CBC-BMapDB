<?php

namespace App\Repository\API;

use Illuminate\Support\Facades\DB;

class OpenAiQueryRepo
{
    public function logQuery(string $query, string $model, string $response): void
    {
        DB::table('openai_queries')->insert([
            'query' => $query,
            'model' => $model,
            'response' => $response,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
