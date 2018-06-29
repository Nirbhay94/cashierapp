<?php

namespace App\Http\Controllers\Settings;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $payment_setting = PaymentSetting::first();

        return view('settings.payment', compact('payment_setting'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'amount_init' => 'required|numeric|integer',
            'amount_inc' => 'nullable|numeric|integer',
            'amount_max' => 'nullable|numeric|integer',
            'business_name' => 'nullable|max:150',
            'business_id' => 'nullable|max:50',
            'business_logo' => 'nullable|mimes:png|dimensions:width=524,height=140|max:50',
            'business_phone' => 'nullable|max:50',
            'business_location' => 'nullable|max:150',
            'business_zip' => 'nullable|max:20',
            'business_city' => 'nullable|max:50',
            'business_country' => 'nullable|max:100',
            'business_legal_terms' => 'nullable|max:250',
        ]);

        $payment_setting = PaymentSetting::first();
        $payment_setting->amount_init = $request->amount_init;
        $payment_setting->amount_inc = $request->amount_inc;
        $payment_setting->amount_max = $request->amount_max;
        $payment_setting->business_name = $request->business_name;
        $payment_setting->business_id = $request->business_id;

        if($request->hasFile('business_logo')){
            $file = $request->file('business_logo');
            $name = 'invoice_logo.png';
            $path = '/images/'. $name;
            $file->move('images', $name);
            $payment_setting->business_logo = $path;
        }

        $payment_setting->business_phone = $request->business_phone;
        $payment_setting->business_location = $request->business_location;
        $payment_setting->business_zip = $request->business_zip;
        $payment_setting->business_city = $request->business_city;
        $payment_setting->business_country = $request->business_country;
        $payment_setting->business_legal_terms = $request->business_legal_terms;

        $payment_setting->save();

        return redirect()->back()
            ->with('success', __('Your settings has been successfully updated!'));
    }
}
