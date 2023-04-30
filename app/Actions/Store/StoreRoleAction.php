<?php

namespace App\Actions\Store;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class StoreRoleAction
{
    public function execute($request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'details' => $request->details
            ]);

            $role->syncPermissions($request->permissions);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
        }
    }
}
