<?php

namespace App\Http\Controllers\Account;

use Carbon\Carbon;
use App\Models\Loan;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function overview()
    {
        return Inertia::render('Account/Overview', [
            'user' => auth()->user()
        ]);
    }

    public function my_loans(Request $request)
    {
        $perpage = request()->input('perPage') ?: 10;

        return Inertia::render('Account/MyLoans', [
            'loans' => Loan::where('member_id', Auth::user()->id)
                ->loanQuery()
                ->paginate($perpage)
                ->through(fn ($loan) => [
                    'id' => $loan->id,
                    'member' => $loan->member,
                    'return_date' => Carbon::parse($loan->return_date)->diffForHumans(),
                    'returned_on' => Carbon::parse($loan->returned_on)->diffForHumans(),
                    'loan_at' => Carbon::parse($loan->loan_on)->diffForHumans(),
                    'book' => $loan->book,
                    'book_cover' => $loan->book->getFirstMediaUrl('book_covers')
                ])
        ]);
    }
}
