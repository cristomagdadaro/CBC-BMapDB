<?php

namespace Database\Factories;

use App\Models\BreederProfile;
use App\Models\User;
use App\Models\Institute;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreederProfileFactory extends Factory
{
    protected $model = BreederProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'institute_id' => Institute::factory(),
            'specialization' => $this->faker->jobTitle,
            'biography' => $this->faker->paragraph,
            'photo_path' => null,
        ];
    }
}
