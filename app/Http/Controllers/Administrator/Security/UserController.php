<?php

namespace App\Http\Controllers\Administrator\Security;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Laravolt\Avatar\Avatar;
use Illuminate\Http\Request;
use App\Traits\CreatesAlerts;
use App\Http\Controllers\Controller;
use App\Actions\Store\StoreUserAction;
use App\Http\Requests\Store\StoreUserRequest;

class UserController extends Controller
{
    use CreatesAlerts;
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $perPage = request()->input('perPage') ?: 10;

        return Inertia::render('Administrator/Security/UserManagement', [
            'filters' => request()->only(['search', 'perPage']),
            'roles' => Role::query()->get(['id', 'name']),
            'users' => User::userQuery()
                ->paginate($perPage)
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck(['name']),
                    'joined_at' => Carbon::parse($user->created_at)->diffForHumans(),
                    //'avatar' => $user->avatar
                ])
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
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
    public function store(StoreUserRequest $request, StoreUserAction $action)
    {
        $action->execute($request);
        $this->createAlert('Success', 'New user is created', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     */
    public function destroy(User $user)
    {
        //
    }
}
