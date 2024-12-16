<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PermissionsController extends Controller
{
   
    public function index(Request $request)
    {
        try {
            $sortBy = $request->sort ?? 'id';
            $sortAs = $request->direction ?? 'desc';

            $permissions = Permission::select('*')->with('permissionGroup')
                ->when($request->search, function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->orderBy($sortBy, $sortAs)
                ->paginate(10)
                ->withQueryString();

            $props = [
                "permissions" => $permissions,
                "request" => $request->all()
            ];

            return Inertia::render("Permissions/Index", $props);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' => trans("messages.error.error")]);
        }
    }

    public function create()
    {
        $permissionGroups = PermissionGroup::select('id', 'name')->get();

        return Inertia::render("Permissions/Create", ["permissionGroups" => $permissionGroups]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permission_group_id' => 'required',
            'route' => 'required|string',
        ]);

        try {
            $permissions = new Permission();

            $permissions->name = $request->name;
            $permissions->permission_group_id = $request->permission_group_id;
            $permissions->slug = Str::slug($request->name);
            $permissions->route = $request->route;
            $permissions->save();

            return redirect()->route('permissions.index')->with(['status' => "success", 'message' => trans("messages.permission.created")]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' => trans("messages.error.error")]);
        }
    }

    public function edit(string $id)
    {
        $permission = Permission::where('id', $id)->first();
        $permissionGroups = PermissionGroup::select('id', 'name')->get();

        return Inertia::render("Permissions/Edit", ["permission" => $permission, "permissionGroups" => $permissionGroups]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'permission_group_id' => 'required',
            'route' => 'required|string',
        ]);

        try {
            $permissions = Permission::where('id', $id)->firstOrFail();

            $permissions->name = $request->name;
            $permissions->permission_group_id = $request->permission_group_id;
            $permissions->slug = Str::slug($request->name);
            $permissions->route = $request->route;
            $permissions->save();

            return redirect()->route('permissions.index')->with(['status' => "success", 'message' => trans("messages.permission.Updated")]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' => trans("messages.error.error")]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $permissions = Permission::where('id', $id)->firstOrFail();
            $permissions->delete();
            return redirect()->route('permissions.index')->with(['status' => "success", 'message' => trans("messages.permission.deleted")]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with(['status' => "error", 'message' => trans("messages.error.error")]);
        }
    }
  

}


?>