<?php

namespace App\Http\Controllers\Payment;

use App\Models\PaypalCredential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaypalController extends Controller
{
    protected $currencies = [];

    public function __construct()
    {
        $this->currencies = array_keys(config('settings.paypal_currencies'));
    }

    public function index()
    {
        return view('payment.paypal', [
            'currencies' => $this->currencies,
            'credential' => Auth::user()->paypal_credential()->first()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client_id'         => 'required_with:enable',
            'client_secret'     => 'required_with:enable',
            'mode'              => 'in:sandbox,production',
            'currency'          => [Rule::in($this->currencies)],
        ]);

        $credential = Auth::user()->paypal_credential()->first();

        $credential = $credential ?: new PaypalCredential();

        $credential->currency = $request->currency;
        $credential->mode = $request->mode;
        $credential->client_id = $request->client_id;
        $credential->client_secret = $request->client_secret;
        $credential->enable = $request->filled('enable');

        Auth::user()->paypal_credential()->save($credential);

        $message = __('Your settings has been saved!');

        return redirect()->back()->with('success', $message);
    }
}
