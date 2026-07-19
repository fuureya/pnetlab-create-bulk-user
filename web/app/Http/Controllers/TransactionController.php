<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $transactions = Transaction::with(['user', 'product'])->latest()->get();

        return Inertia::render('Transaksi', [
            'transactions' => $transactions
        ]);
    }
}
