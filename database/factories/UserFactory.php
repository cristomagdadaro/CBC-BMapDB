<?php

namespace Database\Factories;

use App\Models\Accounts;
use App\Models\Application;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fname' => $this->faker->name(),
            'mname' => $this->faker->lastName(),
            'lname' => $this->faker->lastName(),
            'suffix' => $this->faker->randomElement(['','','','Jr.','','Sr.','','','','I','','','II','','','III','','','','','IV']),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile_no' => $this->faker->phoneNumber(),
            'affiliation' => $this->faker->randomElement(['Crop Biotechnology Center','Philippine Rice Research Institute', 'Central Luzon State University', 'Visayas State University', 'Mindanao State University', 'Philippine Rubber Research Institute']),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role' => 1,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }

    /**
     * Indicate that the user should be approved by the admin.
     */
    public function approved($id = null): static
    {
        if (!$id)
            $id = Application::all()->random()->id;
        return $this->has(
            Accounts::factory()
                ->state(fn (array $attributes, User $user) => [
                    'user_id' => $user->id,
                    'app_id' => $id,
                    'approved_at' => now(),
                ])
        );
    }

}
