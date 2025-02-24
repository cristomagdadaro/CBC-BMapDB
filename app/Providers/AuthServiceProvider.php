<?php

namespace App\Providers;

use App\Models\Accounts;
use App\Models\Application;
use App\Models\Role;
use App\Models\User;
use App\Policies\AccountPolicy;
use App\Policies\ApplicationPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;
use Modules\PbMap\Policies\BreederPolicy;
use Modules\PbMap\Policies\CommodityPolicy;

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
        Commodity::class => CommodityPolicy::class,
        User::class => UserPolicy::class,
        Accounts::class => AccountPolicy::class,
        Role::class => RolePolicy::class,
        Application::class => ApplicationPolicy::class,
    ];

    /**
     * Register any authentication/authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // Ensure policies are registered
    }
}
