<?php

namespace App\Http\Controllers\Customers;

use App\Mail\Customer\Broadcast;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BroadcastController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('send_broadcast')){
            return view('customers.broadcast.index');
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body'              => 'required|string',
            'subject'           => 'required|string',

            'customers'         => 'required_without:all_customers|nullable|array',
            'customers.*'       => [
                'exists:customers,id',
                function($attribute, $value, $fail){
                    if(!Auth::user()->customers()->find($value)){
                        $message = __('Customer (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ]
        ]);

        set_time_limit(60 * 30);

        if($request->all_customers){
            $customers = Auth::user()->customers()->get();
        }else{
            $customers = Auth::user()->customers()
                ->whereIn('id', $request->customers)
                ->get();
        }

        $mail = $request->only(['subject', 'body']);

        Mail::to($customers)->send(new Broadcast(Auth::user(), $mail));

        $message = __('Broadcast has been sent successfully!');

        return redirect()->back()->with('success', $message);
    }
}
