<?php

namespace Codersgarden\RolePermissionAssign;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LexofficeServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {     
            // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        // Load Vue components
        $this->loadViewsFrom(__DIR__.'/../../resources', 'mypackage');

        // Publish assets if needed
        $this->publishes([
            __DIR__.'/../../resources/js/Pages' => resource_path('js/Pages'),
        ], 'mypackage-views');
    }
}
