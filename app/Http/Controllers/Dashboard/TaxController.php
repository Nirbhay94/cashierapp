<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\Traits\UpdateReports;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TaxController extends Controller
{
    use UpdateReports;

    public function index()
    {
        $this->updateSales();

        return view('dashboard.tax.index');
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->sale_reports();

            if($from = $request->from){
                $records = $records->where('created_at', '>=', $from);
            }

            if($to = $request->to){
                $records = $records->where('created_at', '<=', $to);
            }

            $records = $records->where('tax', '!=', 0)->get();

            return DataTables::of($records)
                ->editColumn('customer_id', function($data){
                    return $data->customer->name;
                })
                ->editColumn('tax', function($data){
                    if($number = $data->tax){
                        return money($number, Auth::user());
                    }
                })
                ->editColumn('customer_invoice_id', function($data){
                    if($invoice = $data->customer_invoice()->first()){
                        $html = '';

                        $route = route('invoice.download', ['token' => $invoice->token]);

                        $html .= '<a href="'.$route.'">';
                        $html .= $invoice->token;
                        $html .= '</a>';

                        return $html;
                    }
                })
                ->editColumn('pos_transaction_id', function($data){
                    if($transaction = $data->pos_transaction()->first()){
                        $html = '';

                        $receipt = route('transactions.pos.receipt', ['id' => $transaction->id]);

                        $html .= '<a href="javascript:Global.openPrintWindow(\''.$receipt.'\')">';
                        $html .= $transaction->id;
                        $html .= '</a>';

                        return $html;
                    }
                })
                ->editColumn('date', function($data){
                    return Carbon::parse($data->date)->toDayDateTimeString();
                })
                ->removeColumn('total', 'purchases', 'profit')
                ->rawColumns(['action', 'status', 'customer_invoice_id', 'pos_transaction_id', 'tax'])
                ->make(true);
        }else{
            return abort(404);
        }
    }
}
