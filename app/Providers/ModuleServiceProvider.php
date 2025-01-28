<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modulesPath = app_path('Modules');
        $modules = array_filter(glob($modulesPath . '/*'), 'is_dir');

        foreach ($modules as $module) {
            $moduleConfigPath = $module . '/module.json';

            if (file_exists($moduleConfigPath)) {
                $moduleConfig = json_decode(file_get_contents($moduleConfigPath), true);

                if ($moduleConfig['active']) {
                    // Load Web Routes
                    if (!empty($moduleConfig['web_routes']) && file_exists($module . '/' . $moduleConfig['web_routes'])) {
                        $this->loadRoutesFrom($module . '/' . $moduleConfig['web_routes']);
                    }

                    // Load API Routes
                    if (!empty($moduleConfig['api_routes']) && file_exists($module . '/' . $moduleConfig['api_routes'])) {
                        $this->loadRoutesFrom($module . '/' . $moduleConfig['api_routes']);
                    }

                    // Load Views
                    if (is_dir($module . '/Views')) {
                        $this->loadViewsFrom($module . '/Views', $moduleConfig['name']);
                    }

                    // Load Migrations
                    if (is_dir($module . '/Database/Migrations')) {
                        $this->loadMigrationsFrom($module . '/Database/Migrations');
                    }
                }
            }
        }
    }
}
