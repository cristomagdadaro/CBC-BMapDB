<?php

namespace Modules\PbMap\Repositories;

use App\Enums\DefaultPassword;
use App\Enums\Role;
use App\Enums\Applications as ApplicationsEnum;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

                $photoPaths = $this->storeBreederPhoto($data['photo'] ?? null);
                if ($photoPaths) {
                    $data['photo'] = $photoPaths['breeder'];
                }

                $geolocationData = $data['geolocation'];
                unset($data['geolocation']);

                $breederData = $data;

                if ($geolocationData['type'] === 'city') {
                    $breederData['geolocation'] = $geolocationData['city_id'];
                } else {
                    // Create a custom location point
                    $location = \App\Models\Location::create([
                        'name' => $geolocationData['name'],
                        'type' => 'custom_point',
                        'latitude' => $geolocationData['lat'],
                        'longitude' => $geolocationData['lng'],
                    ]);
                    
                    // Assign the new location ID to geolocation
                    $breederData['geolocation'] = $location->id;
                }

                // Ensure user_id is set appropriately or null
                $breederData['user_id'] = null;
                /** @var \App\Models\User|null $actor */
                $actor = Auth::user();
                if ($actor && !$actor->isAdmin()) {
                    $breederData['user_id'] = $actor->id;
                }

                // Note: Email notification to breeder relies on User account existence.
                // Since User account creation is removed, email notification is skipped.
                // Alternatively, an email could be sent here via Mail facade if enabled:
                // if (env('ENABLE_EMAIL_NOTIFICATIONS', true) && !empty($data['email'])) { ... }

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
