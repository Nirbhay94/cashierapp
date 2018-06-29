<?php

namespace App\Http\Controllers\Subscription;

use App\Models\PaymentSetting;
use Braintree\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TopupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $payment_setting = PaymentSetting::first();
        return view('subscription.topup', compact('payment_setting'));
    }

    public function process(Request $request)
    {
        $payment_setting = PaymentSetting::first();

        // Validate input...
        $this->validate($request, [
            'amount' => 'numeric|required|min:'.$payment_setting->amount_init,
            'payment_method_nonce' => 'required'
        ]);

        // Probably we should validate maximum amount too?
        if($payment_setting->amount_max){
            // Yes, we should..
            $this->validate($request, [
                'amount' => 'max:'.$payment_setting->amount_max
            ]);
        }

        $amount = $request->amount;
        $nonce = $request->payment_method_nonce;
        $result = Transaction::sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($result->success || !is_null($result->transaction)) {
            // Yay! Payment was successful.
            $transaction = $result->transaction;
            $transactionSuccessStatuses = [
                Transaction::AUTHORIZED,
                Transaction::AUTHORIZING,
                Transaction::SETTLED,
                Transaction::SETTLING,
                Transaction::SETTLEMENT_CONFIRMED,
                Transaction::SETTLEMENT_PENDING,
                Transaction::SUBMITTED_FOR_SETTLEMENT
            ];

            if(in_array($transaction->status, $transactionSuccessStatuses)) {
                // Sweet Success... We add to users credit
                $statement = __('Account Balance Topup');
                Auth::user()->addPoints($amount, $statement);

                $message = __('Transaction ended with a success!');

                return redirect()->route('home')->with('success', $message);
            }else{
                $message = __("Transaction ended with a failure! Message:").' '.$transaction->status;

                return redirect()->back()->with('error', $message);
            }
        }else{
            // Something went wrong, we need to collect errors...
            $response = redirect()->back();

            foreach($result->errors->deepAll() as $error) {
                $message = __('Error').' '.$error->code.': '. $error->message;

                $response->with('error', $message);
            }

            return $response;
        }
    }
}
