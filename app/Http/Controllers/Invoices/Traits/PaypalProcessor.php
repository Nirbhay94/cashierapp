<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 5/22/2018
 * Time: 5:11 PM
 */

namespace App\Http\Controllers\Invoices\Traits;


use App\Models\CustomerInvoice;
use App\Models\User;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

trait PaypalProcessor
{
    protected $currencies;

    public function __construct()
    {
        $this->currencies = config('settings.paypal_currencies');
    }

    /**
     * @return null|array
     * @param User $user
     * @param CustomerInvoice $invoice
     */
    public function paypalDetails($user, $invoice)
    {
        $details = [];

        if (($credential = $user->paypal_credential) && $credential->enable) {
            $details['client_id'] = $credential->client_id;
            $details['client_secret'] = $credential->client_secret;
            $details['mode'] = $credential->mode;

            $details['note'] = $invoice->note;
            $details['currency'] = strtolower($credential->currency);
            $details['items'] = json_decode($invoice->items);

            if ($details['currency'] != currency($user, true)) {
                $from = currency($user, true);
                $to = $credential->currency;

                $total = currency_convert($invoice->total, $from, $to);
            } else {
                $total = $invoice->total;
            }

            if (!$this->currencies[$credential->currency]['decimal_support']) {
                $details['total'] = ceil($total);
            } else {
                $details['total'] = money_number_format($total);
            }

            if ($configuration = $user->invoice_configuration) {
                $details['name'] = $configuration->business_name;
            }

            $details['create_url'] = route('invoice.paypal.create_payment', [
                'token' => $invoice->token
            ]);

            $details['execute_url'] = route('invoice.paypal.execute_payment', [
                'token' => $invoice->token
            ]);

            return $details;
        }

        return null;
    }

    public function paypalCreatePayment(Request $request, $token)
    {
        $invoice = $this->getInvoice($token);

        if ($invoice && $user = $invoice->user) {
            try {
                if ($details = $this->paypalDetails($user, $invoice)) {
                    $context = $this->getApiContext($details);

                    $payer = $this->setPayer();

                    $amount = $this->setAmount($details['total'], $details['currency']);

                    $transaction = $this->setTransaction($amount);
                    $transaction->setDescription($invoice->note);
                    $transaction->setInvoiceNumber($invoice->token);

                    $redirectUrls = $this->getRedirectUrls($invoice);

                    $payment = $this->setPayment($transaction, $payer);
                    $payment->setRedirectUrls($redirectUrls);

                    $payment->create($context);
                } else {
                    return response()->json(__('Paypal not supported!'), 500);
                }
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }

            return $payment;
        } else {
            $message = __('The invoice could not be found!');

            return response()->json($message, 404);
        }
    }

    public function paypalExecutePayment(Request $request, $token)
    {
        if($request->get('paymentID') && $request->get('payerID')) {
            $payerID = $request->get('payerID');
            $paymentId = $request->get('paymentID');
            $invoice = $this->getInvoice($token);

            if ($invoice && $user = $invoice->user) {
                if ($details = $this->paypalDetails($user, $invoice)) {
                    try{
                        $context = $this->getApiContext($details);

                        $payment = Payment::get($paymentId, $context);

                        $execution = new PaymentExecution();
                        $execution->setPayerId($payerID);

                        $amount = $this->setAmount($details['total'], $details['currency']);

                        $transaction = $this->setTransaction($amount);
                        $transaction->setDescription($invoice->note);
                        $transaction->setInvoiceNumber($invoice->token);

                        $execution->addTransaction($transaction);

                        $payment->execute($execution, $context);
                    }catch(\Exception $e){
                        return response()->json($e->getMessage(), 500);
                    }

                    $this->updateInvoice($invoice, $payment->getId());

                    $message = __('Payment was successful!');

                    return response()->json($message, 200);
                }else{
                    $message = __('Paypal not supported!');

                    return response()->json($message, 404);
                }
            }else{
                $message = __('The invoice could not be found!');

                return response()->json($message, 404);
            }

        }else{
            $message = __('Something went wrong! Please contact the administrator!');

            return response()->json($message, 400);
        }
    }

    private function setPayer($method = 'paypal')
    {
        $payer = new Payer();

        $payer->setPaymentMethod($method);

        return $payer;
    }

    /**
     * @param $total
     * @param $currency
     * @return Amount
     */
    private function setAmount($total, $currency)
    {
        $amount = new Amount();

        $amount->setCurrency(strtoupper($currency))
            ->setTotal($total);

        return $amount;
    }

    private function setItems($items)
    {
        $list = [];

        foreach ($items as $item) {
            $product = new Item();

            $product->setName($item->name)
                ->setCurrency('USD')
                ->setQuantity($item->amount)
                ->setSku($item->id)// Similar to `item_number` in Classic API
                ->setPrice($item->price);

            $list[] = $product;
        }

        $itemList = new ItemList();

        $itemList->setItems($list);

        return $itemList;
    }

    /**
     * @param Amount $amount
     * @return Transaction
     */
    private function setTransaction($amount)
    {
        $transaction = new Transaction();

        $transaction->setAmount($amount);

        return $transaction;
    }

    /**
     * @param Transaction $transaction
     * @param Payer $payer
     * @return Payment
     */
    private function setPayment($transaction, $payer)
    {
        $payment = new Payment();

        $payment->setIntent("sale");

        $payment->setTransactions([$transaction]);

        $payment->setPayer($payer);

        return $payment;
    }

    /**
     * Helper method for getting an APIContext for all calls
     * @param array $details
     * @return ApiContext
     */
    private function getApiContext($details)
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $details['client_id'],
                $details['client_secret']
            )
        );


        $apiContext->setConfig([
            'mode' => isset($details['mode'])? $details['mode']: 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'cache.enabled' => false,
            //'cache.FileName' => '/PaypalCache' // for determining paypal cache directory
            //'http.CURLOPT_CONNECTTIMEOUT' => 30
            //'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
        ]);

        return $apiContext;
    }

    private function getRedirectUrls($invoice)
    {
        $redirectUrls = new RedirectUrls();

        $returnUrl = route('invoice.paypal.execute_payment', [
            'token' => $invoice->token
        ]);

        $redirectUrls->setReturnUrl($returnUrl);

        $cancelUrl = route('invoice.payment', [
            'token' => $invoice->token
        ]);

        $redirectUrls->setCancelUrl($cancelUrl);

        return $redirectUrls;
    }
}