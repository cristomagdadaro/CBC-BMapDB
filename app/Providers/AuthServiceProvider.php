<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Policies\BreederPolicy;

// Ensure you have an Enum or define permission strings manually

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model-to-policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Add policies if needed
        Breeder::class => BreederPolicy::class,
    ];

    /**
     * Register any authentication/authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // Ensure policies are registered
    }
}
