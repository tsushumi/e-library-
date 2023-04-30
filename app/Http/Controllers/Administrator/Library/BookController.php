<?php

namespace App\Http\Controllers\Administrator\Library;

use App\Actions\Store\StoreBookAction;
use Carbon\Carbon;
use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreBookRequest;
use App\Models\Category;
use App\Traits\CreatesAlerts;

class BookController extends Controller
{
    use CreatesAlerts;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('perPage') ?: 5;

        return Inertia::render('Administrator/Library/Books', [
            'filters' => request()->only(['search', 'perPage']),
            'categories' => Category::all(),
            'books' => Book::bookQuery()
                ->paginate($perPage)
                ->through(fn ($book) => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'author' => $book->author,
                    'edition' => $book->edition,
                    'created_at' => Carbon::parse($book->created_at)->diffForHumans(),
                    'ISBN_10' => $book->ISBN_10,
                    'ISBN_13' => $book->ISBN_13,
                    'preview' => $book->getFirstMediaUrl('book_covers'),
                    'copies' => $book->copies
                ])
                ->withQueryString(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request, StoreBookAction $action)
    {
        $action->execute($request);
        $this->createAlert('Success', 'You have successfully added books to collection', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }
}
