<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PermissionGroupsController extends Controller
{
    
    public function index()
    {
        $user = PermissionGroup::all();

        dd($user);
    }

   

    public function create()
    {    
        return Inertia::render('RolePermission');
    }


   
}
