<?php

namespace App\Http\Controllers\Invoices;

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
use Sukohi\CsvValidator\Facades\CsvValidator;

class ImportController extends Controller
{
    use Transactions;

    public function index()
    {
        if(Auth::user()->can_use('import_data')){
            return view('invoice.import.index');
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function upload(Request $request, Token $token)
    {
        set_time_limit(60 * 30);

        if($file = $request->file('file')){
            try{
                $validator = CsvValidator::make($file->getRealPath(), [
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
                    'enable_repeat'         => 'required|in:yes,no',

                    'products'              => ['required', function($attribute, $value, $fail){
                        $products = array();

                        $items = explode(',', $value);

                        foreach($items as $item){
                            if(($details = explode('x', $item)) && count($details) == 2){
                                if(isset($products[$details[0]]) && $products[$details[0]]){
                                    $products[$details[0]] += $details[1];
                                }else{
                                    $products[$details[0]] = $details[1];
                                }
                            }else{
                                return $fail(__('The format of products is incorrect'));
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

                    'repeat_type'           => 'required_if:enable_repeat,yes|nullable|in:daily,weekly,monthly,yearly',
                    'repeat_interval'       => 'required_if:enable_repeat,yes|nullable|numeric|min:1',
                    'repeat_until'          => 'required_if:enable_repeat,yes|nullable|date_format:Y-m-d H:i:s',
                ]);

                if(!$validator->fails()) {
                    if($data = $validator->data()){
                        $data->each(function ($csv) use ($token){
                            $record = new CustomerInvoice();

                            $record->token = $token->Unique('customer_invoices', 'token', 10);
                            $record->customer_id = $csv->customer_id;
                            $record->note = $csv->note;
                            $record->allow_partial = $csv->allow_partial;

                            if($csv->enable_repeat == 'yes'){
                                $repeat_data = [
                                    'type' => $csv->repeat_type,
                                    'interval' => $csv->repeat_interval,
                                ];

                                $record->repeat_data = json_encode($repeat_data);
                                $record->repeat_until = Carbon::parse($csv->repeat_until);
                            }

                            $items = $taxes = $product_ids = $products = [];

                            $csv_products = explode(',', $csv->products);

                            foreach($csv_products as $item){
                                if(($details = explode('x', $item)) && count($details) == 2){
                                    $product = [];

                                    $product['product_id'] = $details[0];
                                    $product['quantity'] = $details[1];

                                    $products[] = $product;
                                }
                            }

                            foreach($products as $product){
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

                            $record->sub_total = money_number_format($this->subTotalPrice($items));
                            $record->product_ids = implode(',', $product_ids);
                            $record->tax_ids = implode(',', array_keys($taxes));
                            $record->tax = money_number_format($this->getTotalTaxAmount($record->sub_total, $taxes));
                            $record->total = money_number_format($record->sub_total + $record->tax);
                            $record->items = json_encode($items);

                            $invoice = Auth::user()->customer_invoices()->save($record);

                            $customer = $invoice->customer()->first();

                            if($csv->apply_balance == 'yes'){
                                $this->applyBalance($customer, $invoice);
                            }

                            $customer->increment('purchases', $this->countPurchases($items));

                            if($invoice->status == 'paid'){
                                $customer->notify(new PaidInvoice($customer, $invoice));
                            }else{
                                $customer->notify(new NewInvoice($customer, $invoice));
                            }
                        });

                        $message = __('A total of :num records has been added', [
                            'num'   => $data->count()
                        ]);

                        return response()->json($message, 200);
                    }else{
                        $message = __('You file does not contain enough data!');

                        return response()->json($message, 403);
                    }
                }else{
                    return response()->json($validator->getErrors(), 403);
                }
            }catch(\Exception $e){
                return response()->json($e->getMessage(), 403);
            }
        }else{
            $message = __('Something went wrong with the file! Please try again.');

            return response()->json($message, 403);
        }
    }

    public function download()
    {
        $file = public_path('sample/invoice_record.csv');

        return response()->download($file);
    }
}
