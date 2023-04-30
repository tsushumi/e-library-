<?php

namespace App\Actions\Update;

class UpdateCategoryAction
{
    public function execute($request, $category)
    {
        $category->update([
            'name' => $request->name,
            'details' => $request->details
        ]);
    }
}
