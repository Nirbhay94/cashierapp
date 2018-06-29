<?php

namespace App\Http\Controllers\Invoices;

use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceTransaction;
use App\Notifications\Customer\NewInvoice;
use App\Notifications\Customer\PaidInvoice;
use App\Traits\Transactions;
use Carbon\Carbon;
use Dirape\Token\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ListController extends Controller
{
    use Transactions;

    public function index()
    {
        if(Auth::user()->can_use('issue_invoice')){
            return view('invoice.list.index');
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            if($request->customer){
                $records = Auth::user()->customer_invoices()
                    ->where('customer_id', $request->customer)
                    ->get();
            }else{
                $records = Auth::user()->customer_invoices()->get();
            }

            return DataTables::of($records)
                ->editColumn('customer_id', function($data){
                    if($customer = $data->customer){
                        return $customer->name;
                    }
                })
                ->editColumn('repeat_data', function($data){
                    if($data = json_decode($data->repeat_data, true)){
                        return __(':interval :type interval', [
                            'type' => $data['type'],
                            'interval' => $data['interval']
                        ]);
                    }else{
                        return __('No');
                    }
                })
                ->editColumn('status', function($data){
                    $html = '<span class="label label-'.invoice_label($data->status).'">';
                    $html .= strtoupper($data->status);
                    $html .= '</span>';

                    return $html;
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
                ->editColumn('amount_paid', function($data){
                    if($number = $data->amount_paid){
                        return money($number, Auth::user());
                    }
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
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('invoice.download', ['token' => $data->token]).'" class="col-green">';
                    $html .= '<span class="material-icons">visibility</span>';
                    $html .= '</a>';

                    if($data->status != 'paid' && $data->customer->balance){
                        $html .= '<a href="'.route('invoice.list.pay', ['id' => $data->id]).'" data-id="'.$data->id.'" class="pay-invoice">';
                        $html .= '<span class="material-icons">local_atm</span>';
                        $html .= '</a>';
                    }

                    if($data->status == 'unpaid'){
                        $html .= '<a href="'.route('invoice.list.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
                        $html .= '<span class="material-icons">clear</span>';
                        $html .= '</a>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'status', 'items', 'sub_total', 'total', 'tax', 'amount_paid'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function store(Request $request, Token $token)
    {
        $this->validate($request, $this->validationRules());

        $record = new CustomerInvoice();

        $record->token = $token->Unique('customer_invoices', 'token', 10);
        $record->customer_id = $request->customer_id;
        $record->note = $request->note;
        $record->allow_partial = $request->allow_partial;

        if($request->enable_repeat == 'yes'){
            $repeat_data = [
                'type' => $request->repeat_type,
                'interval' => $request->repeat_interval,
            ];

            $record->repeat_data = json_encode($repeat_data);
            $record->repeat_until = Carbon::parse($request->repeat_until);
        }

        $items = $taxes = $product_ids = [];

        foreach($request->products as $product){
            $item = [];

            if($product_item = Auth::user()->products()->find($product['product_id'])){
                $product_ids[] = $product['product_id'];

                $product_item->increment('sales', $product['quantity']);

                $item['name'] = $product_item->name;
                $item['price'] = $product_item->price;
                $item['amount'] = $product['quantity'];
                $item['id'] = $product_item->id;

                if($product_item->track == 'yes'){
                    $product_item->decrement('quantity', $product['quantity']);
                }

                if($product_item->taxes){
                    $this->updateTaxes($taxes, explode(',', $product_item->taxes));
                }
            }

            $items[] = $item;
        }

        $record->product_ids = implode(',', $product_ids);
        $record->tax_ids = implode(',', array_keys($taxes));

        $record->sub_total = money_number_format($this->subTotalPrice($items));
        $record->tax = money_number_format($this->getTotalTaxAmount($record->sub_total, $taxes));
        $record->total = money_number_format($record->sub_total + $record->tax);

        $record->items = json_encode($items);

        $invoice = Auth::user()->customer_invoices()->save($record);

        if($customer = $invoice->customer()->first()){
            if($request->apply_balance == 'yes'){
                $this->applyBalance($customer, $invoice);
            }

            $customer->increment('purchases', $this->countPurchases($items));

            if($invoice->status == 'paid'){
                $customer->notify(new PaidInvoice($customer, $invoice));
            }else{
                $customer->notify(new NewInvoice($customer, $invoice));
            }
        }

        $message = __('Your record has been added successfully!');

        return response()->json($message,200);
    }
    
    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            if($record = Auth::user()->customer_invoices()->find($id)){
                try{
                    $record->delete();

                    return response()->json(__('The record has been removed!'), 200);
                }catch(\Exception $e){
                    return response()->json($e->getMessage(), 400);
                }
            }else{
                $message = __('The record could not be found!');

                return response()->json($message, 404);
            }
        }else{
            return abort(403);
        }
    }

    public function pay(Request $request, $id)
    {
        if($request->ajax()) {
            if($invoice = Auth::user()->customer_invoices()->find($id)){
                if($invoice->status != 'paid'){
                    $customer = $invoice->customer()->first();

                    $this->applyBalance($customer, $invoice);

                    if($invoice->status == 'paid'){
                        $customer->notify(new PaidInvoice($customer, $invoice));
                    }

                    $message = __('Transaction ended with a success!');

                    return response()->json($message, 200);
                }

                $message = __('The invoice has been paid!');

                return response()->json($message, 403);
            }else{
                $message = __('The record could not be found!');

                return response()->json($message, 404);
            }
        }else{
            return abort(403);
        }
    }

    public function validationRules()
    {
        return [
            'customer_id'           => [
                'required', 'exists:customers,id',
                function($attribute, $value, $fail){
                    if(!Auth::user()->customers()->find($value)){
                        $message = __('Customer (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],
            'note'                  => 'required|string',
            'apply_balance'         => 'required|in:yes,no',
            'allow_partial'         => 'required|in:yes,no',

            'products'              => ['required', 'array', function($attribute, $value, $fail){
                $products = array();

                foreach($value as $item){
                    if(isset($products[$item['product_id']]) && $products[$item['product_id']]){
                        $products[$item['product_id']] += $item['quantity'];
                    }else{
                        $products[$item['product_id']] = $item['quantity'];
                    }
                }

                foreach($products as $id => $quantity){
                    if($product = Auth::user()->products()->find($id)){
                        if($product->track == 'yes' && $product->quantity < $quantity){

                            $message = __('The product :name has only :quantity left.', [
                                'quantity' => $product->quantity,
                                'name' => $product->name
                            ]);

                            return $fail($message);
                        }
                    }
                }
            }],

            'products.*.product_id' => [
                'required', 'exists:products,id',
                function($attribute, $value, $fail){
                    if(!Auth::user()->products()->find($value)){
                        $message = __('Product (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],
            'products.*.quantity'   => 'required|integer|min:1',

            'repeat_type'           => 'required_if:enable_repeat,yes|nullable|in:daily,weekly,monthly,yearly',
            'repeat_interval'       => 'required_if:enable_repeat,yes|nullable|numeric|min:1',
            'repeat_until'          => 'required_if:enable_repeat,yes|nullable|date_format:Y-m-d H:i:s',
        ];
    }
}
