<?php

namespace App\Http\Controllers\Administrator\Security;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $perPage = request()->input('perPage') ?: 15;

        return Inertia::render('Administrator/Security/Permissions', [
            'permissions' => Permission::permissionQuery()
                ->paginate($perPage)
                ->through(fn ($permission) => [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'roles_count' => $permission->roles->count(),
                    'created_at' => Carbon::parse($permission->created_at)->diffForHumans(),
                ])
                ->withQueryString(),
            'filters' => request()->only(['search', 'perPage'])
        ]);
    }
}
