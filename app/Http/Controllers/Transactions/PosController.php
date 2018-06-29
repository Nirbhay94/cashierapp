<?php

namespace App\Http\Controllers\Transactions;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PosController extends Controller
{
    public function index()
    {
        return view('transactions.pos.index');
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->pos_transactions()->get();

            return DataTables::of($records)
                ->editColumn('customer_id', function($data){
                    return $data->customer->name;
                })
                ->editColumn('items', function($data){
                    $items = json_decode($data->items);

                    $collection = array();

                    foreach($items as $item){
                        $html = '';

                        $html .= '<div class="chip">';
                        $html .= '<span>'.$item->name.' ('.money($item->price, Auth::user()).') x '.$item->amount.'</span>';
                        $html .= '</div>';

                        $collection[] = $html;
                    }

                    return implode(' ', $collection);
                })
                ->editColumn('sub_total', function($data){
                    if($number = $data->sub_total){
                        return money($number, Auth::user());
                    }
                })
                ->editColumn('total', function($data){
                    if($number = $data->total){
                        return money($number, Auth::user());
                    }
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
                ->editColumn('status', function($data){
                    $html = '';

                    $html .= '<span class="label label-'.pos_label($data->status).'">';
                    $html .= strtoupper($data->status);
                    $html .= '</span>';

                    return $html;
                })
                ->editColumn('created_at', function($data){
                    return Carbon::parse($data->created_at)->toDayDateTimeString();
                })
                ->addColumn('action', function($data){
                    $html = '';

                    $receipt = route('transactions.pos.receipt', ['id' => $data->id]);

                    $html .= '<a href="javascript:Global.openPrintWindow(\''.$receipt.'\')">';
                    $html .= '<span class="material-icons">archive</span>';
                    $html .= '</a>';

                    return $html;
                })
                ->rawColumns(['action', 'status', 'customer_invoice_id', 'items', 'sub_total', 'total', 'tax'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function receipt($id)
    {
        if($transaction = Auth::user()->pos_transactions()->find($id)) {
            $configuration = Auth::user()->pos_configuration()->first();

            $header = ($configuration)? $configuration->header : null;

            $footer = ($configuration)? $configuration->footer : null;

            return view('pos.receipt', [
                'transaction'       => $transaction,
                'header'            => $header,
                'footer'            => $footer,
            ]);
        }else{
            return abort(404);
        }
    }
}
