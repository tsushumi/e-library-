<?php

namespace App\Http\Controllers\Administrator\Library;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\CreatesAlerts;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Store\StoreLoanAction;
use App\Http\Requests\Store\StoreLoanRequest;

class LoanController extends Controller
{
    use CreatesAlerts;
    public function index(Request $request)
    {
        $perPage = request()->input('perPage') ?: 10;

        return Inertia::render('Administrator/Library/BookLoans', [
            'filters' => request()->only(['search', 'perPage']),
            // 'loaner' => Loan::query()->groupBy('member')->get(),
            'loans' => Loan::loanQuery()
                ->paginate($perPage)
                ->through(fn ($loan) => [
                    'id' => $loan->id,
                    'member' => $loan->member,
                    'book' => $loan->book,
                    'book_sn' => Str::mask($loan->book_sn, '*', 4, -1),
                    'return_date' => Carbon::parse($loan->return_date)->diffForHumans(),
                    'returned_on' => Carbon::parse($loan->returned_on)->diffForHumans(),
                    'loan_at' => Carbon::parse($loan->loan_on)->diffForHumans(),
                    'remaining_time' => Carbon::parse($loan->return_date)->diffInHours(Carbon::parse($loan->loan_on)),
                    'start_time' => Carbon::parse($loan->return_date)->diffInHours(Carbon::parse($loan->loan_on)),
                    'current_time' => Carbon::now()->diffInHours(Carbon::parse($loan->loan_on)),
                    "percent" => Carbon::now()->diffInHours(Carbon::parse($loan->loan_on)) / Carbon::parse($loan->return_date)->diffInHours(Carbon::parse($loan->loan_on)) * 100
                ])
                ->withQueryString(),
            'users' => User::role(['member', 'librarian'])
                ->limit(10)
                ->when(request('term'), function ($query, $term) {
                    $query->where('name', 'like', "%$term%")
                        ->orWhere('phone', 'like', "%$term%")
                        ->orWhere('username', 'like', "%$term%")
                        ->orWhere('email', 'like', "%$term%");
                })
                ->get(['id', 'name', 'email']),
            'usersBooks' => User::role(['member', 'librarian'])
                ->get(['id', 'name']),
            'books' => Book::limit(15)
                ->when(request('bookTerm'), function ($query, $term) {
                    $query->where('title', 'like', "%$term%")
                        ->orWhere('slug', 'like', "%$term%");
                })
                ->get()
        ]);
    }

    public function store(StoreLoanRequest $request, StoreLoanAction $action)
    {
        $action->execute($request);
        $this->createAlert('Success', 'You have successfully issued a book', 'success');
        // dertermine user status(is user banned or not),
        // $member = User::query()->findOrFail($request->member_id);
        // $book = Book::query()->findOrFail($request->book_id);
        // if ($member->ban()) {
        //     $this->createAlert('Alert', 'Member is banned from using this library', 'danger');
        // } else {
        //     // determine books availability
        //     if ($book->availability() <= 3) {
        //         $this->createAlert('Alert', 'This book cannot be borrowed. Few books in the facility', 'danger');
        //     } else {
        //         $action->execute($request);
        //         $this->createAlert('Success', 'You have successfully issued a book', 'success');
        //     }
        // }
    }
}
