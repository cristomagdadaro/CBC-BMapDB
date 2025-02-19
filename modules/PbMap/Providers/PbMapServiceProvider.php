<?php

namespace Modules\PbMap\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class PbMapServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            // Load the API routes for the PbMap module
            Route::middleware('api')
                ->group(base_path('Modules/PbMap/Routes/BreedersMapRoutes.php'));
        });
    }

    public function register()
    {
        // Register module services if needed
    }
}
