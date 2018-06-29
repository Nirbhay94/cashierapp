<?php

namespace App\Http\Controllers;

use App\Models\CustomerInvoice;
use App\Models\PosTransaction;
use App\Notifications\Customer\NewInvoice;
use App\Notifications\Customer\PaidInvoice;
use App\Notifications\Customer\PosCheckout;
use App\Traits\Transactions;
use Dirape\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    use Transactions;

    public function index()
    {
        if(Auth::user()->can_use('pos_terminal')){
            return view('pos.index', [
                'categories'    => $this->getCategories(),
                'taxes'         => $this->getTaxes()
            ]);
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function invoice(Request $request, Token $token)
    {
        $rules = array_merge($this->validationRules(), [
            'allow_partial'         => 'required|in:yes,no'
        ]);

        $this->validate($request, $rules);

        $record = new CustomerInvoice();

        $record->token = $token->Unique('customer_invoices', 'token', 10);
        $record->customer_id = $request->customer_id;
        $record->note = $request->note;
        $record->allow_partial = $request->allow_partial;

        $items = $taxes = $product_ids = [];

        foreach($request->items as $product){
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
            $this->applyBalance($customer, $invoice);

            if($request->notify){
                if($invoice->status == 'paid'){
                    $customer->notify(new PaidInvoice($customer, $invoice));
                }else{
                    $customer->notify(new NewInvoice($customer, $invoice));
                }
            }

            $customer->increment('purchases', $this->countPurchases($items));
        }


        // Insert Pos Transaction
        $record = new PosTransaction();

        $record->customer_id = $request->customer_id;
        $record->customer_invoice_id = $invoice->id;

        $record->details = $request->details;
        $record->note = $request->note;

        $record->items = json_encode($items);
        $record->product_ids = implode(',', $product_ids);
        $record->tax_ids = implode(',', array_keys($taxes));

        $record->sub_total = money_number_format($this->subTotalPrice($items));
        $record->tax = money_number_format($this->getTotalTaxAmount($record->sub_total, $taxes));
        $record->total = money_number_format($record->sub_total + $record->tax);

        $record->status = 'invoice';

        Auth::user()->pos_transactions()->save($record);

        $data = route('invoice.download', ['token' => $invoice->token]);

        return response()->json($data);
    }

    public function checkout(Request $request)
    {
        $rules = array_merge($this->validationRules(), [
            'coupon'                => [
                'nullable', 'exists:product_coupons,code',
                function($attribute, $value, $fail){
                    if(!Auth::user()->product_coupons()->where('code', $value)->first()){
                        $message = __('Coupon (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],
            'amount_paid'           => 'required|numeric',
        ]);

        $this->validate($request, $rules);

        $record = new PosTransaction();

        $record->customer_id = $request->customer_id;
        $record->customer_invoice_id = $request->customer_invoice_id;

        $record->details = $request->details;
        $record->note = $request->note;

        $items = $taxes = $product_ids = [];

        foreach($request->items as $product){
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

        $record->items = json_encode($items);
        $record->product_ids = implode(',', $product_ids);
        $record->tax_ids = implode(',', array_keys($taxes));

        $record->sub_total = money_number_format($this->subTotalPrice($items, $request->coupon));
        $record->discount = ($this->discount)? money_number_format($this->discount): null;
        $record->tax = money_number_format($this->getTotalTaxAmount($record->sub_total, $taxes));
        $record->total = money_number_format($record->sub_total + $record->tax);

        $record->status = 'completed';

        $transaction = Auth::user()->pos_transactions()->save($record);

        if($customer = $transaction->customer()->first()){
            $credit = 0;

            $total = $record->total;

            if($customer->balance > 0){
                $balance = (int) $customer->balance;

                if($balance < $total){
                    $total = money_number_format($total - $balance);
                }else{
                    $credit += $balance - $record->total;

                    $total = money_number_format(0);
                }
            }

            if($request->amount_paid > $total){
                $credit += $request->amount_paid - $total;
            }

            $customer->balance = money_number_format($credit);

            $customer->save();

            if($request->notify){
                $customer->notify(new PosCheckout($customer, $transaction));
            }

            $customer->increment('purchases', $this->countPurchases($items));
        }

        $data = route('transactions.pos.receipt', ['id' => $transaction->id]);

        return response()->json($data);
    }

    public function getTaxes()
    {
        return Auth::user()->product_taxes->mapWithKeys(function ($record) {
            return [
                $record->id => [
                    'id'            => $record->id,
                    'code'          => $record->code,
                    'type'          => $record->type,
                    'name'          => $record->name,
                    'amount'        => $record->amount,
                ]
            ];
        })->toArray();
    }

    public function getCategories()
    {
        return Auth::user()->product_categories->mapWithKeys(function ($record) {
            return [
                $record->id => [
                    'id'            => $record->id,
                    'products'      => $record->products()->count(),
                    'name'          => $record->name,
                ]
            ];
        })->toArray();
    }

    private function validationRules()
    {
        return [
            'note'                  => 'required|string',
            'details'               => 'required|string',
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

            'items'                 => ['required', 'array', function($attribute, $value, $fail){
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

            'items.*.product_id'    => [
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
            'items.*.quantity'      => 'required|integer|min:1',
        ];
    }
}
