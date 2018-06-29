<?php

namespace App\Http\Controllers\Subscription;

use App\Models\Invoice;
use Braintree\Transaction;
use Carbon\Carbon;
use Gerardojbaez\Laraplans\Models\Plan;
use Gerardojbaez\Laraplans\Models\PlanSubscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExtendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subscription = Auth::user()->subscription('main');

        return view('subscription.extend.index', compact('subscription'));
    }

    public function checkout(Request $request)
    {
        if($subscription = Auth::user()->subscription('main')){
            $plan = $subscription->plan;

            $this->validate($request, [
                'quantity' => 'required|numeric|integer|min:'.$plan->min_quantity
            ]);

            if($subscription->plan->max_quantity){
                $this->validate($request, [
                    'quantity' => 'max:'.$plan->max_quantity
                ]);
            }

            $quantity = $request->quantity;
            $now = Carbon::now();

            if($subscription->isActive()){
                if(!$subscription->onStrictTrial()){
                    $ends_at = new Carbon($subscription->ends_at);

                    $status = __(':plan :count days left', ['plan' => $plan->name, 'count' => $ends_at->diffInDays($now)]);
                    $statement = __('Extending subscription by :quantity quantities', ['quantity' => $quantity]);
                    $price = ($plan->price * $quantity);

                    $details = [
                        'status' => $status,
                        'statement' => $statement,
                        'price' => round($price, 2),
                        'plan_id' => $plan->id,
                        'quantity' => $quantity,
                        'token' => str_random(16)
                    ];


                    Session::put('extend.checkout.details', $details);

                    return view('subscription.extend.checkout', compact('details', 'plan'));
                }else{
                    $ends_at = new Carbon($subscription->trial_ends_at);

                    $status = __(':plan (Trial Period) :count days left', ['plan' => $plan->name, 'count' => $ends_at->diffInDays($now)]);
                    $statement = __('Start new subscription to :plan', ['plan' => $plan->name]);
                    $price = ($plan->price * $quantity);

                    $details = [
                        'status' => $status,
                        'statement' => $statement,
                        'price' => round($price, 2),
                        'plan_id' => $plan->id,
                        'quantity' => $quantity,
                        'token' => str_random(16)
                    ];

                    Session::put('extend.checkout.details', $details);

                    return view('subscription.extend.checkout', compact('details', 'plan'));
                }
            }else{
                $status = __(':plan (Expired)', ['plan' => $plan->name]);
                $statement = __('Renew subscription to :plan', ['plan' => $plan->name]);
                $price = ($plan->price * $quantity);

                $details = [
                    'status' => $status,
                    'statement' => $statement,
                    'price' => round($price, 2),
                    'plan_id' => $plan->id,
                    'quantity' => $quantity,
                    'token' => str_random(16)
                ];

                Session::put('extend.checkout.details', $details);

                return view('subscription.extend.checkout', compact('details', 'plan'));
            }
        }else{
            $message = __('You do not have any subscription plan yet!');

            return redirect()->back()->with('error', $message);
        }
    }

    public function process(Request $request)
    {
        if(($subscription = Auth::user()->subscription('main')) && ($checkout = Session::get('extend.checkout.details'))){
            $amount = $checkout['price'];
            $statement = $checkout['statement'];
            $quantity = $checkout['quantity'];

            if($plan = Plan::find($checkout['plan_id'])){

                // Validate input...
                $this->validate($request, [
                    'payment_method_nonce' => 'required'
                ]);
                $nonce = $request->payment_method_nonce;

                // Get Checkout Details...
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
                        // Create an invoice record...
                        $invoice = new Invoice();
                        $invoice->transaction_id = $transaction->id;
                        $invoice->user_id = Auth::user()->id;
                        $invoice->note = $statement;
                        $items = [
                            [$plan->name, ($amount/$quantity), $quantity, $plan->id]
                        ];
                        $invoice->total = $amount;
                        $invoice->items = json_encode($items);
                        $invoice->save();

                        $this->addQuantity($quantity, $subscription, $plan);

                        Session::forget('extend.checkout.details');

                        $message = __('Transaction ended with a success! Check your account summary for activity logs.');

                        return redirect()->route('home')->with('success', $message);
                    }else{
                        $message = __("Transaction ended with a failure! Message: :message", ['message' => $transaction->status]);

                        return redirect()->back()->with('error', $message);
                    }
                }else{
                    // Something went wrong, we need to collect errors...
                    $response = redirect()->back();

                    foreach($result->errors->deepAll() as $error) {
                        $message = __('Error (:code) :message', ['code' => $error->code, 'message' => $error->message]);

                        $response->with('error', $message);
                    }

                    return $response;
                }
            }else{
                $message = __('Oops! It looks like your selected plan could not be found. Please try again.');

                return redirect()->back()->with('error', $message);
            }
        }else{
            $message = __('Oops! something went wrong. Please try again.');

            return redirect()->back()->with('error', $message);
        }
    }

    public function processCredit(Request $request)
    {
        if (($subscription = Auth::user()->subscription('main')) && ($checkout = Session::get('extend.checkout.details'))) {
            $amount = $checkout['price'];
            $statement = $checkout['statement'];
            $quantity = $checkout['quantity'];

            if ($plan = Plan::find($checkout['plan_id'])) {
                if(Auth::user()->currentPoints() >= $amount){
                    // Create an invoice record...
                    $invoice = new Invoice();
                    $invoice->transaction_id = uniqid();
                    $invoice->user_id = Auth::user()->id;
                    $invoice->note = $statement;
                    $items = [[$plan->name, ($amount/$quantity), $quantity, $plan->id]];
                    $invoice->total = $amount;
                    $invoice->items = json_encode($items);
                    $invoice->save();

                    // We deduct the points... :)
                    $transaction = Auth::user()->addPoints(-$amount, $statement);
                    $transaction->invoice_id = $invoice->id;
                    $transaction->save();

                    // Subscribe user to the plan...
                    $this->addQuantity($quantity, $subscription, $plan);

                    Session::forget('extend.checkout.details');

                    $message = __('Transaction ended with a success! Check your account summary for activity logs.');

                    return response()->json($message, 200);
                }else{
                    $message = __('Insufficient Balance. Please Topup before you proceed!');

                    return response()->json($message, 403);
                }
            }
        }
    }
    /**
     * Add subscription quantity.
     *
     * @param  int $quantity
     * @param  PlanSubscription $subscription
     * @param  Plan $plan
     * @return PlanSubscription
     */
    public function addQuantity($quantity, $subscription, $plan)
    {
        if($quantity > 0){
            switch($plan->interval){
                case 'day':
                    $method = 'addDays';
                    break;
                case 'week':
                    $method = 'addWeeks';
                    break;
                case 'month':
                    $method = 'addMonths';
                    break;
                case 'year':
                    $method = 'addYears';
                    break;
                default:
                    $method = 'addMonths';
                    break;
            }
            $ends_at = new Carbon($subscription->ends_at);
            $ends_at = $ends_at->$method(($plan->interval_count * $quantity));

            $subscription->ends_at = $ends_at;
            $subscription->save();
        }

        return $subscription;
    }
}
