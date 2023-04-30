<?php

namespace App\Actions\Update;

use Illuminate\Support\Facades\DB;

class UpdateRoleAction
{
    public function execute($request, $role)
    {
        DB::beginTransaction();
        try {
            $role->update([
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
