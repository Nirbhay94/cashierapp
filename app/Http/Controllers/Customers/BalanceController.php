<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('customers')){
            return view('customers.balance.index');
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
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
            'balance'               => 'required|numeric:min:0',
        ]);

        if($customer = Auth::user()->customers()->find($request->customer_id)){
            if($customer->balance){
                $customer->increment('balance', $request->balance);
            }else{
                $customer->balance = $request->balance;
                $customer->save();
            }

            $message = __('The selected customer has been updated!');

            return redirect()->back()->with('success', $message);
        }else{
            $message = __('The selected customer could not be found!');

            return redirect()->back()->with('error', $message);
        }
    }
}
