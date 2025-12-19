<?php

namespace Modules\PbMap\Actions;

use App\Enums\DefaultPassword;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\PbMap\Requests\CreateBreederRequest;

class CreateBreederAction
{
    public function execute(CreateBreederRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();

                $breederUser = User::create([
                    'fname' => $data['fname'] ?? null,
                    'mname' => $data['mname'] ?? null,
                    'lname' => $data['lname'] ?? null,
                    'suffix' => $data['suffix'] ?? null,
                    'mobile_no' => $data['mobile_no'] ?? null,
                    'email' => $data['email'] ?? null,
                    'affiliation' => $data['affiliation'] ?? null,
                    'password' => bcrypt(DefaultPassword::Value->value),
                ]);

                $breederUser->assignRole(Role::BREEDER->value);

                $breederUser->accounts()->create([
                    'app_id' => 2, // Assuming 2 is for PbMap
                    'approved_at' => now(),
                ]);

                if (!$breederUser->hasVerifiedEmail()) {
                    $breederUser->sendEmailVerificationViaFocalPersonNotification();
                }

                $breederData = array_merge($data, ['user_id' => $breederUser->id]);

                // Only override user_id if the current user is NOT an admin
                if (!auth()->user()->isAdmin()) {
                    $breederData['user_id'] = auth()->id();
                }

                return $breederData;
            });
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Failed to create breeder user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new \Exception('Breeder creation failed. Please try again later.');
        }
    }
}

