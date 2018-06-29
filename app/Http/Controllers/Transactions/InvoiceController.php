<?php

namespace App\Http\Controllers\Transactions;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('transactions.invoice.index');
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->customer_invoice_transactions()->get();

            return DataTables::of($records)
                ->editColumn('customer_id', function($data){
                    return Auth::user()->customers()->find($data->customer_id)->name;
                })
                ->editColumn('amount', function($data){
                    if($number = $data->amount){
                        return money($number, Auth::user());
                    }
                })
                ->editColumn('customer_invoice_id', function($data){
                    if($invoice = $data->customer_invoice){
                        return $invoice->token;
                    }
                })
                ->editColumn('created_at', function($data){
                    return Carbon::parse($data->created_at)->toDayDateTimeString();
                })
                ->addColumn('action', function($data){

                })
                ->rawColumns(['action', 'status', 'customer_invoice_id', 'amount'])
                ->make(true);
        }else{
            return abort(404);
        }
    }
}
