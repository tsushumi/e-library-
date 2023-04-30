<?php

namespace App\Actions\Store;

use App\Models\Book;
use App\Models\TemporaryFile;

class StoreBookAction
{
    public function execute($request)
    {
        $book = Book::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'author' => $request->author,
            'ISBN_10' => $request->ISBN_10,
            'ISBN_13' => $request->ISBN_13,
            'edition' => $request->edition,
            'value' => $request->value,
            'copies' => $request->copies,
            'publisher' => $request->publisher,
        ]);

        
    }
}
