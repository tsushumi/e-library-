<?php

namespace App\Http\Controllers\Administrator\Security;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Traits\CreatesAlerts;
use App\Http\Controllers\Controller;
use App\Actions\Store\StoreRoleAction;
use App\Actions\Update\UpdateRoleAction;
use App\Http\Requests\Store\StoreRoleRequest;
use App\Http\Requests\Update\UpdateRoleRequest;

class RoleController extends Controller
{
    use CreatesAlerts;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request()->input('perPage') ?: 15;

        return Inertia::render('Administrator/Security/Roles', [
            'roles' => Role::roleQuery()->paginate($perPage)->withQueryString(),
            'permissions' => Permission::select('name')->get(),
            'filters' => request()->only(['search', 'perPage'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(StoreRoleRequest $request, StoreRoleAction $action)
    {
        $action->execute($request);

        $this->createAlert('Success', 'New role :/n' . $request->name . '/n created', 'success');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     */
    public function update(UpdateRoleRequest $request, Role $role, UpdateRoleAction $action)
    {
        $action->execute($request, $role);
        $this->createAlert('Success', 'You have successfully updated role: ' . $role->name, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
