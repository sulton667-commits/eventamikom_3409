<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Transaction::with('event')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('order_id', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%');
        }

        if ($request->has('status') && $request->status != '' && $request->status != 'Semua Status') {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(10);

        return view('admin.transactions', compact('transactions'));
    }
}
