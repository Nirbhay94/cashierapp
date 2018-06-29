<?php

namespace App\Http\Controllers\Payment;

use App\Models\BankCredential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BankController extends Controller
{
    public function index()
    {
        return view('payment.bank', [
            'credential' => Auth::user()->bank_credential()->first()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'details' => 'required_with:enable'
        ]);

        $credential = Auth::user()->bank_credential()->first();

        $credential = $credential ?: new BankCredential();

        $credential->details = $request->details;
        $credential->enable = $request->filled('enable');

        Auth::user()->bank_credential()->save($credential);

        $message = __('Your settings has been saved!');

        return redirect()->back()->with('success', $message);
    }
}
