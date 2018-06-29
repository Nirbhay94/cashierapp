<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxController extends Controller
{
    public function index()
    {
        return view('transactions.tax.index');
    }
}
