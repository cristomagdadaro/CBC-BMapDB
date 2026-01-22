<?php

namespace Modules\PbMap\Repositories;

use App\Enums\DefaultPassword;
use App\Enums\Role;
use App\Enums\Applications as ApplicationsEnum;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\PbMap\Requests\CreateBreederRequest;

class BreederCreationRepo
{
    public function createBreederData(CreateBreederRequest $request): array
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

                $photoPaths = $this->storeBreederPhoto($data['photo'] ?? null);
                if ($photoPaths) {
                    $breederUser->update([
                        'profile_photo_path' => $photoPaths['user'],
                    ]);
                    $data['photo'] = $photoPaths['breeder'];
                }

                $breederUser->assignRole(Role::BREEDER->value);

                $app = Application::firstOrCreate([
                    'name' => ApplicationsEnum::BREEDERS_MAP->value,
                ], [
                    'description' => ApplicationsEnum::BREEDERS_MAP_DESC->value,
                    'url' => ApplicationsEnum::BREEDERS_MAP_ROUTE->value,
                    'icon' => ApplicationsEnum::BREEDERS_MAP_LOGO->value,
                    'status' => true,
                ]);
                $appId = $app->id;

                $breederUser->accounts()->create([
                    'app_id' => $appId,
                    'approved_at' => now(),
                ]);

                if (!$breederUser->hasVerifiedEmail()) {
                    try {
                        $breederUser->sendEmailVerificationViaFocalPersonNotification();
                    } catch (\Throwable $mailError) {
                        Log::warning('Breeder verification email failed to send', [
                            'user_id' => $breederUser->id,
                            'error' => $mailError->getMessage(),
                        ]);
                    }
                }

                $breederData = array_merge($data, ['user_id' => $breederUser->id]);

                // Only override user_id if the current user is NOT an admin
                $actor = auth()->user();
                if ($actor && !$actor->isAdmin()) {
                    $breederData['user_id'] = $actor->id;
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

    private function storeBreederPhoto(?string $photoData): ?array
    {
        if (!$photoData) {
            return null;
        }

        if (filter_var($photoData, FILTER_VALIDATE_URL)) {
            return [
                'breeder' => $photoData,
                'user' => $photoData,
            ];
        }

        $normalized = ltrim($photoData, '/');
        if (str_starts_with($normalized, 'storage/')) {
            return [
                'breeder' => $normalized,
                'user' => preg_replace('#^storage/#', '', $normalized),
            ];
        }

        if (str_starts_with($normalized, 'data:image/')) {
            if (!preg_match('/^data:image\/(\w+);base64,/', $normalized, $matches)) {
                return null;
            }

            $extension = strtolower($matches[1] ?? 'jpg');
            $base64 = substr($normalized, strpos($normalized, ',') + 1);
            $binary = base64_decode($base64, true);

            if ($binary === false) {
                return null;
            }

            $filename = 'profile-photos/breeders/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($filename, $binary);

            return [
                'breeder' => 'storage/' . $filename,
                'user' => $filename,
            ];
        }

        return [
            'breeder' => $normalized,
            'user' => $normalized,
        ];
    }
}
