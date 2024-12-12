<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Permission; // Assuming Permission model is located in the correct namespace
use App\Traits\GeneralTrait;

class CheckPermission
{
    use GeneralTrait;

    const ADMIN_ID = 1;

    /**
     * List of routes for which admin should have no access.
     *
     * @var array
     */
    private $noAccessRoutesForAdmin = [
        // "company.dashboard",
        // "trainer.dashboard",
    ];

    /**
     * Handle an incoming request to check user permissions.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = Auth::user();

        // Redirect to login if the user is not authenticated
        if (!$user) {
            return redirect()->route('login')->with(['status' => "warn", "message" => trans('auth.loginFailed')]);
        }

        // Get the name of the current route
        $currentRouteName = $request->route()->getName();

        // Check if the user is an admin
        if ($user->role_id == self::ADMIN_ID) {
            // Redirect admin if trying to access restricted route
            if (in_array($currentRouteName, $this->noAccessRoutesForAdmin)) {
                return redirect()->back()->with(['status' => "warn", "message" => trans('scenario.PermissionDenied')]);
            }

            // Continue processing for admin
            return $next($request);
        }

        // For non-admin users, check if permission exists for the current route
        $permissions = $this->getAllPermissionsForActiveUser();
        $permissionExists = Permission::where('route', $currentRouteName)->exists();

        // Continue processing if permission doesn't exist for the route
        if (!$permissionExists) {
            return $next($request);
        }

        // Redirect if the user does not have permission for the current route
        if (!in_array($currentRouteName, $permissions)) {
            return redirect()->back()->with(['status' => "warn", "message" => trans('scenario.PermissionDenied')]);
        }

        // Continue processing for users with valid permissions
        return $next($request);
    }
}
