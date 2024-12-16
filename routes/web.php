<?php
use Illuminate\Support\Facades\Route;
use Codersgarden\RolePermissionAssign\Http\Controllers\PageController;

Route::group(function () {
    Route::get('/open-page', [PageController::class, 'showCreatePage']);
});
