<?php

namespace App\Actions\Store;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreUserAction
{
    public function execute($request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'username' => $request->username,
                'joined_at' => $request->joined_at,
                'email' => $request->email,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole($request->roles);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
        }
    }
}
