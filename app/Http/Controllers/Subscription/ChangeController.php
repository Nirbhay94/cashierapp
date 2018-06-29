<?php

namespace App\Http\Controllers\Subscription;

use App\Models\Invoice;
use App\Models\User;
use Braintree\Transaction;
use Carbon\Carbon;
use Gerardojbaez\Laraplans\Models\Plan;
use Gerardojbaez\Laraplans\Models\PlanSubscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $records = [];
        Plan::orderBy('sort_order', 'ASC')->chunk(4, function($plans) use (&$records) {
            $records[] = $plans;
        });

        return view('subscription.change.index', compact('records'));
    }

    public function checkout(Request $request)
    {
        if($plan = Plan::find($request->plan)){
            $this->validate($request, [
                'quantity' => 'required|numeric|integer|min:'.$plan->min_quantity
            ]);

            if($plan->max_quantity){
                $this->validate($request, [
                    'quantity' => 'max:'.$plan->max_quantity
                ]);
            }

            $quantity = $request->quantity;
            $now = Carbon::now();

            if($subscription = Auth::user()->subscription('main')){
                // I guess this user just wants an upgrade...
                if($subscription->isActive()){
                    if($subscription->plan->id == $plan->id){
                        $message = __('You are not allowed to reselect an active subscription plan, until it expires.');

                        return redirect()->route('subscription.change')->with('error', $message);
                    }

                    // Make sure a user cannot downgrade an active subscription.
                    if($subscription->plan->price > $plan->price){
                        $message = __('You are not allowed to downgrade a subscription plan, until it expires.');

                        return redirect()->route('subscription.change')->with('error', $message);
                    }

                    if(!$subscription->onStrictTrial()){
                        $starts_at = new Carbon($subscription->starts_at);
                        $ends_at = new Carbon($subscription->ends_at);

                        $status = __(':plan :count days left', ['plan' => $subscription->plan->name, 'count' => $ends_at->diffInDays($now)]);
                        $statement = __('Upgrade subscription plan from :old to :new', ['old' => $subscription->plan->name, 'new' => $plan->name]);
                        $price = ($plan->price * $quantity) - ($ends_at->diffInDays($now)/max($ends_at->diffInDays($starts_at), 1) * $subscription->plan->price);

                        $details = [
                            'status' => $status,
                            'statement' => $statement,
                            'price' => round($price, 2),
                            'plan_id' => $plan->id,
                            'quantity' => $quantity,
                            'token' => str_random(16)
                        ];
                        
                        Session::put('change.checkout.details', $details);
                        
                        return view('subscription.change.checkout', compact('details', 'plan'));
                    }else{
                        $ends_at = new Carbon($subscription->trial_ends_at);

                        $status = __(':plan (Trial Period) :count days left', ['plan' => $subscription->plan->name, 'count' => $ends_at->diffInDays($now)]);
                        $statement = __('New subscription to :plan', ['plan' => $plan->name]);
                        $price = ($plan->price * $quantity);

                        $details = [
                            'status' => $status,
                            'statement' => $statement,
                            'price' => round($price, 2),
                            'plan_id' => $plan->id,
                            'quantity' => $quantity,
                            'token' => str_random(16)
                        ];

                        Session::put('change.checkout.details', $details);

                        return view('subscription.change.checkout', compact('details','plan'));
                    }
                }else{
                    $status = $subscription->plan->name.' '.__('(Expired)');
                    $statement = __('New subscription to :plan', ['plan' => $plan->name]);
                    $price = ($plan->price * $quantity);

                    $details = [
                        'status' => $status,
                        'statement' => $statement,
                        'price' => round($price, 2),
                        'plan_id' => $plan->id,
                        'quantity' => $quantity,
                        'token' => str_random(16)
                    ];
                    
                    Session::put('change.checkout.details', $details);
                    
                    return view('subscription.change.checkout', compact('details', 'plan'));
                }
            }else{
                // New subscription...
                $status = __('No Active Plan Yet');
                $statement = __('New subscription to :plan', ['plan' => $plan->name]);
                $price = ($plan->price * $quantity);

                $details = [
                    'status' => $status,
                    'statement' => $statement,
                    'price' => round($price, 2),
                    'plan_id' => $plan->id,
                    'quantity' => $quantity,
                    'token' => str_random(16)
                ];
                
                Session::put('change.checkout.details', $details);
                
                return view('subscription.change.checkout', compact('details', 'plan'));
            }
        }else{
            $message = __('The specified plan could not be found, please select another!');

            return redirect()->route('subscription.change')->with('error', $message);
        }
    }

    public function process(Request $request)
    {
        if($checkout = Session::get('change.checkout.details')){
            $amount = $checkout['price'];
            $statement = $checkout['statement'];
            $quantity = $checkout['quantity'];

            if($plan = Plan::find($checkout['plan_id'])){
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

                        // Subscribe user to the plan...
                        $subscription = $this->setSubscription(Auth::user(), $plan);
                        $this->setQuantity($quantity, $subscription, $plan);

                        Session::forget('change.checkout.details');

                        $message = __('Transaction ended with a success!');

                        return redirect()->route('home')->with('success', $message);
                    }else{
                        $message = __("Transaction ended with a failure! Message:").' '.$transaction->status;

                        return redirect()->back()->with('error', $message);
                    }
                }else{
                    $response = redirect()->back();

                    foreach($result->errors->deepAll() as $error) {
                        $message = __('Error :code :message', ['code' => $error->code, 'message' => $error->message]);

                        $response->with('error', $message);
                    }

                    return $response;
                }

            }else{
                $message = __('Oops! It looks like your selected plan could not be found. Please try again.');

                return redirect()->back()->with('error', $message);
            }
        }else{
            $message =  __('Oops! something went wrong. Please try again.');

            return redirect()->back()->with('error', $message);
        }
    }

    public function processTrial(Request $request)
    {
        if($request->ajax()){
            if($checkout = Session::get('change.checkout.details')){
                if($plan = Plan::find($checkout['plan_id'])){
                    if($plan->hasTrial() && !Auth::user()->subscription('main')){
                        // Subscribe user to the plan...
                        $this->setTrial($this->setSubscription(Auth::user(), $plan));

                        Session::forget('change.checkout.details');

                        $message = __('Your trial period has started. Expires in: :count days time', ['count' => $plan->trial_period_days]);

                        return response()->json($message, 200);
                    }else{
                        $message = __('Your selected plan does not support trial period. Please select another!');

                        return response()->json($message, 404);
                    }
                }else{
                    $message = __('Oops! It looks like your selected plan could not be found. Please try again.');

                    return response()->json($message, 404);
                }
            }else{
                $message =  __('Oops! something went wrong. Please try again.');

                return response()->json($message, 400);
            }
        }else{
            return abort(400);
        }
    }

    public function processCredit(Request $request)
    {
        if($request->ajax()) {
            if($checkout = Session::get('change.checkout.details')){
                $amount = $checkout['price'];
                $statement = $checkout['statement'];
                $quantity = $checkout['quantity'];

                if($plan = Plan::find($checkout['plan_id'])){
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
                        $subscription = $this->setSubscription(Auth::user(), $plan);
                        $this->setQuantity($quantity, $subscription, $plan);

                        Session::forget('change.checkout.details');

                        $message = __('Transaction ended with a success!');

                        return response()->json($message, 200);
                    }else{
                        $message = __('Insufficient Balance. Please Topup before you proceed!');

                        return response()->json($message, 403);
                    }
                }else{
                    $message = __('Oops! It looks like your selected plan could not be found. Please try again.');

                    return response()->json($message, 404);
                }
            }else{
                $message =  __('Oops! something went wrong. Please try again.');

                return response()->json($message, 400);
            }
        }else{
            return abort(400);
        }
    }

    /**
     * @return PlanSubscription
     * @param User $user
     * @param $plan
     */
    public function setSubscription($user, $plan)
    {
        if($subscription = $user->subscription('main')){
            $features = config('laraplans.features');
            $consumed = [];

            foreach($features as $key => $data) {
                if($data['type'] == 'quantity'){
                    $consumed[$key] = $subscription->ability()->consumed($key);
                }
            }

            session()->put('consumed.features', $consumed);
        }

        $subscription = $user->newSubscription('main', $plan);

        return $subscription->create();
    }

    /**
     * Set subscription quantity.
     *
     * @param  int $quantity
     * @param  PlanSubscription $subscription
     * @param  Plan $plan
     * @return PlanSubscription
     */
    public function setQuantity($quantity, $subscription, $plan)
    {
        if($quantity > 1){
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
            $ends_at = $ends_at->$method(($plan->interval_count * $quantity) - 1);

            $subscription->ends_at = $ends_at;
            $subscription->save();
        }

        return $subscription;
    }

    /**
     * Set subscription trial period.
     *
     * @param  PlanSubscription $subscription
     * @return PlanSubscription
     */
    public function setTrial($subscription)
    {
        $ends_at = Carbon::yesterday();

        $subscription->ends_at = $ends_at;
        $subscription->save();

        return $subscription;
    }
}
