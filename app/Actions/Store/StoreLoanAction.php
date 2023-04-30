<?php

namespace App\Actions\Store;

use App\Mail\BookLend;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StoreLoanAction
{
    public function execute($request)
    {
        DB::beginTransaction();
        try {
            Loan::create([
                'book_id' => $request->book_id,
                'member_id' => $request->user_id,
                'book_sn' => $request->book_sn,
                'loan_on' => now(),
                'return_date' => $request->return_date,
            ]);

            $book = Book::find($request->book_id);
            $borrowedBooks = $book->borrowed_books;
            $book->update([
                'borrowed_books' => ((int)$borrowedBooks + 1)
            ]);

            $user = User::find($request->user_id);
            $booksBorrowed = $user->books_borrowed;
            $user->update([
                'books_borrowed' => ((int)$booksBorrowed + 1)
            ]);

            $emailUser = $user->email;
            Mail::to($emailUser)->send(new BookLend());

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }
    }
}
