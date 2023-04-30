<?php

namespace App\Actions\Store;

use App\Models\Category;

class StoreCategoryAction
{
    public function execute($request)
    {
        Category::create([
            'title' => $request->title,
            'details' => $request->details
        ]);
    }
}
