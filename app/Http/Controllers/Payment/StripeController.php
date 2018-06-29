<?php

namespace App\Http\Controllers\Payment;

use App\Models\StripeCredential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StripeController extends Controller
{
    protected $currencies = [];

    public function __construct()
    {
        $this->currencies = array_keys(config('settings.stripe_currencies'));
    }

    public function index()
    {
        return view('payment.stripe', [
            'currencies' => $this->currencies,
            'credential' => Auth::user()->stripe_credential()->first()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'secret_key' => 'required_with:enable',
            'public_key' => 'required_with:enable',
            'currency' => [Rule::in($this->currencies)],
        ]);

        $credential = Auth::user()->stripe_credential()->first();

        $credential = $credential ?: new StripeCredential();

        $credential->currency = $request->currency;
        $credential->secret_key = $request->secret_key;
        $credential->public_key = $request->public_key;
        $credential->enable = $request->filled('enable');

        Auth::user()->stripe_credential()->save($credential);

        $message = __('Your settings has been saved!');

        return redirect()->back()->with('success', $message);
    }
}
