<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 5/22/2018
 * Time: 5:13 PM
 */

namespace App\Http\Controllers\Invoices\Traits;


use App\Models\CustomerInvoice;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

trait StripeProcessor
{
    /**
     * @return null|array
     * @param User $user
     * @param CustomerInvoice $invoice
     */
    public function stripeDetails($user, $invoice)
    {
        $details = [];

        if(($credential = $user->stripe_credential) && $credential->enable){
            $details['public_key'] = $credential->public_key;
            $details['secret_key'] = $credential->secret_key;
            $details['note'] = $invoice->note;
            $details['currency'] = strtolower($credential->currency);

            if($details['currency'] != strtolower(currency($user, true))){
                $from = currency($user, true);
                $to = $credential->currency;

                $total = currency_convert($invoice->total, $from, $to);
            }else{
                $total = $invoice->total;
            }

            $details['total'] = ceil($total * 100);

            if($configuration = $user->invoice_configuration){
                $details['name'] = $configuration->business_name;
            }

            $details['charge_url'] = route('invoice.stripe.charge', [
                'token' => $invoice->token
            ]);

            return $details;
        }

        return null;
    }

    public function stripeCharge(Request $request, $token)
    {
        $invoice = $this->getInvoice($token);

        if ($invoice && $user = $invoice->user) {
            if ($details = $this->stripeDetails($user, $invoice)) {
                try{
                    Stripe::setApiKey($details['secret_key']);

                    $charge = Charge::create([
                        'amount'        => $details['total'],
                        'currency'      => $details['currency'],
                        'description'   => $details['note'],
                        'source'        => $request->get('stripeToken'),
                    ]);
                }catch(\Exception $e){
                    return redirect()->back()->with('error', $e->getMessage());
                }

                $this->updateInvoice($invoice, $charge->id, 'stripe');

                $message = __('Payment was successful!');

                return redirect()->back()->with('success', $message);
            }else{
                $message = __('Stripe not supported!');

                return redirect()->back()->with('error', $message);
            }
        } else {
            $message = __('The invoice could not be found!');

            return redirect()->back()->with('error', $message);
        }
    }
}