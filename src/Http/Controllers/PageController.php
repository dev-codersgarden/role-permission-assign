<?php 

namespace Codersgarden\RolePermissionAssign\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    public function showCreatePage()
    {
        return Inertia::render('CreatePage'); // Vue.js page name
    }
}