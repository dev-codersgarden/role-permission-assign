<?php

namespace Codersgarden\RolePermissionAssign;


use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RoleServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {     

        
        $this->publishes([
           
            __DIR__ . '/../routes/Role.php' => base_path('routes/RolePermission.php'),

            __DIR__ . '/../resources/js/Pages/Roles/' => resource_path('js/Pages/Roles'),
            __DIR__ . '/../resources/js/Pages/Permissions/' => resource_path('js/Pages/Permissions'),
            __DIR__ . '/../resources/js/Pages/PermissionGroup/' => resource_path('js/Pages/PermissionGroup'),

            __DIR__ . '/../controller/PermissionGroupsController.php' => app_path('Http/Controllers/PermissionGroupController.php'),
            __DIR__ . '/../controller/PermissionsController.php' => app_path('Http/Controllers/PermissionController.php'),
            __DIR__ . '/../controller/RoleController.php' => app_path('Http/Controllers/RoleController.php'),
            __DIR__ . '/../controller/RolePermissionController.php' => app_path('Http/Controllers/RolePermissionController.php'),

            __DIR__ . '/../Middleware/CheckPermission.php' => app_path('Http/Middleware/CheckPermission.php'),

            __DIR__ . '/../models/PermissionGroup.php' => app_path('Models/PermissionGroup.php'),
            __DIR__ . '/../models/Permission.php' => app_path('Models/Permission.php'),
            __DIR__ . '/../models/Role.php' => app_path('Models/Role.php'),
            __DIR__ . '/../models/RolePermission.php' => app_path('Models/RolePermission.php'),

            __DIR__ . '/../migrations/2024_09_17_070759_create_roles_table.php' => database_path('migrations/2024_09_17_070759_create_roles_table.php'),
            __DIR__ . '/../migrations/2024_09_17_070949_create_permission_groups_table.php' => database_path('migrations/2024_09_17_070949_create_permission_groups_table.php'),
            __DIR__ . '/../migrations/2024_09_17_071110_create_permissions_table.php' => database_path('migrations/2024_09_17_071110_create_permissions_table.php'),
            __DIR__ . '/../migrations/2024_09_17_071243_create_role_permissions_table.php' => database_path('migrations/2024_09_17_071243_create_role_permissions_table.php'),
        ], 'RolePermission');

        $this->ensureRouteFileIsRequired();


        $this->ensureComposableFolderAndPublishFile();



        // php artisan vendor:publish --tag=RolePermission
    }



    protected function ensureComposableFolderAndPublishFile()
    {
        $composablePath = resource_path('js/composable');

        $roleJsSourcePath = __DIR__ . '/../composable/role.js';

        $roleJsTargetPath = $composablePath . '/role.js';

        // Create the composable folder if it doesn't exist
        if (!File::exists($composablePath)) {
            File::makeDirectory($composablePath, 0755, true);
        }

        if (File::exists($roleJsSourcePath)) {
            File::copy($roleJsSourcePath, $roleJsTargetPath);
        } else {
            throw new \Exception("Source file not found: {$roleJsSourcePath}");
        }
    }



    protected function ensureRouteFileIsRequired()
    {

        $webFilePath = base_path('routes/web.php');
        $routeFilePath = base_path('routes/RolePermission.php');
        $routeLine = "require __DIR__.'/RolePermission.php';";
        if (file_exists($routeFilePath) && file_exists($webFilePath) && strpos(file_get_contents($webFilePath), $routeLine) === false) {
            file_put_contents($webFilePath, PHP_EOL . $routeLine, FILE_APPEND);
        }
    }
}
