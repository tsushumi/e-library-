<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function home(Request $request)
    {
        return Inertia::render('Dashboard', [
            'members_count' => User::role('member')->count(),
            'librarians_count' => User::role('librarian')->count(),
            'books_count' => Book::sum('copies'),
            'books_borrowed' => User::sum('books_borrowed')
        ]);
    }
}
