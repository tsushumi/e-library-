<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $alert = null;
        if ($request->session()->has('errors')) {
            $alert = [
                'title'      => 'Error',
                'message'    => 'Something went wrong.',
                'type'       => 'warning',
            ];
        } else {
            $alert = $request->session()->get('alert', null);
        }

        return array_merge(parent::share($request), [
            'auth' => Auth::user() ? [
                'user' => Auth::user(),
                'roles' => Auth::user() ? Auth::user()->roles->pluck('name') : [],
                'permissions' => Auth::user() ? Auth::user()->getPermissionsViaRoles()->pluck('name') : []
            ] : null,
            'alert' => $alert
        ]);
    }
}
