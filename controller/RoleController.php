<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Str;

class RoleController extends Controller
{



    public function index(Request $request)
    {    

        
        try {
            $sortBy = $request->sort ?? 'id';
            $sortAs = $request->direction ?? 'desc';

            $roles = Role::select('*')
                ->when($request->search, function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->orderBy($sortBy, $sortAs)
                ->paginate(10)
                ->withQueryString();

            return Inertia::render('Roles/Index', [
                'roles' => $roles,
                'request' => $request->all(),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with(['status' => "error", "message" => trans("messages.error.error")]);
        }
    }

    public function create()
    {   

        // return Inertia::render('CreatePage');
        return Inertia::render('Roles/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:200",
        ]);

        try {

            $role = new Role();
            $role->name = $request->name;
            $role->slug = Str::slug($request->name);
            $role->save();

            return redirect()->route("roles.index")->with(["status" => "success", "message" => trans("messages.role.added")]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' =>trans("messages.error.error")]);
        }
    }

    public function edit(string $id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                return redirect()->back()->with(["status" => "error", "message" =>trans("messages.role.notFound")]);
            }

            return Inertia::render(
                "Roles/Edit",
                [
                    "role" => $role
                ]
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' => trans("messages.error.error")]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:100",
        ]);

        try {

            $role = Role::find($request->id);

            if (!$role) {
                return redirect()->back()->with(["status" => "error", "message" => trans("messages.role.notFound")]);
            }

            $role->name = $request->name;
            $role->slug = Str::slug($request->name);
            $role->save();

            return redirect()->route('roles.index')->with(["status" => "success", "message" => trans("messages.role.updated")]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' => trans("messages.error.error")]);
        }
    }

    public function destroy(string $id)
    {
        try {

            $role = Role::find($id);

            if (!$role) {
                return redirect()->back()->with(["status" => "error", "message" => trans("messages.role.notFound")]);
            }
            $role->delete();
            return redirect()->route('roles.index')->with(["status" => "success", "message" => trans("messages.role.deleted")]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' => trans("messages.error.error")]);
        }
    }

    public function permissions(string $id)
    {   

        $role = Role::find($id);

        if (!$role) {
            return redirect()->back()->with(["status" => "error", "message" => trans("messages.role.notFound")]);
        }
        $permissionGroups = PermissionGroup::select('*')->with('permissions')->get();

        $assignedPermissions = RolePermission::where('role_id', $id)->pluck('permission_id')->toArray();
        return Inertia::render('Roles/Permissions', [
            'role' => $role,
            'permissionGroups' => $permissionGroups,
            'assignedPermissions' => $assignedPermissions
        ]);

    }
}
