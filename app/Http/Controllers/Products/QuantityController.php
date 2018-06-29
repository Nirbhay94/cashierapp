<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QuantityController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('products')) {
            return view('products.quantity.index');
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'products' => 'required|array',
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
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        foreach($request->products as $item){
            if($product = Auth::user()->products()->find($item['product_id'])){
                if($product->quantity){
                    $product->increment('quantity', $item['quantity']);
                }else{
                    $product->quantity = $item['quantity'];
                    $product->save();
                }
            }
        }

        $message = __('The selected products has been updated!');

        return redirect()->back()->with('success', $message);
    }
}
