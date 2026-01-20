<?php

namespace App\Repository\API;

use Illuminate\Support\Facades\DB;

class PersonalAccessTokenRepo
{
    public function deleteTokensForUser(int $userId): void
    {
        DB::table('personal_access_tokens')
            ->where('tokenable_type', 'App\\Models\\User')
            ->where('tokenable_id', $userId)
            ->delete();
    }
}
