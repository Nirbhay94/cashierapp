<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Invoices\Traits\PaypalProcessor;
use App\Http\Controllers\Invoices\Traits\StripeProcessor;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceTransaction;
use App\Models\User;
use Carbon\Carbon;
use ConsoleTVs\Invoices\Classes\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class InvoiceController extends Controller
{
    use PaypalProcessor, StripeProcessor;

    public function download($token)
    {
        set_time_limit(120);

        if($invoice = CustomerInvoice::where('token', $token)->first()){
            $file = $this->getFilePath($invoice);

            $path = $this->absolutePath($file);
            
            if(!File::exists($path)){
                $pdf = $this->generate($invoice);
                $pdf->save($file);

                return response()->download($path);
            }else{
                $timestamp = File::lastModified($path);

                $last_modified = Carbon::createFromTimestamp($timestamp);

                if($last_modified < $invoice->updated_at){
                    $pdf = $this->generate($invoice);
                    $pdf->save($file);
                }

                return response()->download($path);
            }
        }else{
            return abort(404);
        }
    }

    public function payment($token)
    {
        $invoice = $this->getInvoice($token);

        if($invoice && $user = $invoice->user){
            return view('invoice.payment.index', [
                'invoice'           => $invoice,
                'configuration'     => $user->invoice_configuration,
                'user'              => $user,
                'items'             => json_decode($invoice->items),
                'processor'         => [
                    'bank'      => $this->bankDetails($user, $invoice),
                    'paypal'    => $this->paypalDetails($user, $invoice),
                    'stripe'    => $this->stripeDetails($user, $invoice)
                ]
            ]);
        }else{
            return abort(404);
        }
    }

    /**
     * @param $token
     * @return CustomerInvoice
     */
    protected function getInvoice($token)
    {
        return CustomerInvoice::where('token', $token)->first();
    }
    /**
     * @return null|array
     * @param User $user
     * @param CustomerInvoice $invoice
     */
    private function bankDetails($user, $invoice)
    {
        $details = [];

        if(($credential = $user->bank_credential) && $credential->enable){
            $details['details'] = $credential->details;

            return $details;
        }

        return null;
    }

    /**
     * @param CustomerInvoice $invoice
     * @param User $user
     * @return Invoice
     */
    private function generate($invoice)
    {
        $user = $invoice->user;
        
        $items = json_decode($invoice->items);
        
        $file = new Invoice();

        foreach($items as $item){
            $file->addItem($item->name, $item->price, $item->amount, $item->id);
        }

        $file->number($invoice->token);
        $file->currency(currency($user, true));

        if($invoice->note){
            $file->notes($invoice->note);
        }

        if($invoice->amount_paid){
            $file->amountPaid($invoice->amount_paid);
        }

        $file->status($invoice->status);

        if($invoice->tax){
            $file->taxType('fixed');
            $file->tax($invoice->tax);
        }

        $file->logo(url('images/logo_black.png'));

        if($config = $user->invoice_configuration){
            if($config->business_logo){
                $file->logo(url($config->business_logo));
            }

            // Specify business details...
            $file->business([
                'name'        => $config->business_name,
                'id'          => $config->business_id,
                'phone'       => $config->business_phone,
                'location'    => $config->business_location,
                'zip'         => $config->business_zip,
                'city'        => $config->business_city,
                'country'     => $config->business_country,
            ]);

            // Add footnote
            if($config->business_legal_terms){
                $file->footnote($config->business_legal_terms);
            }
        }


        // Specify customer details
        $file->customer([
            'name'          => $invoice->customer->name,
            'id'            => $invoice->customer->id,
            'phone'         => $invoice->customer->phone_number ?: $invoice->customer->email,
            'location'      => $invoice->customer->location,
            'zip'           => $invoice->customer->zip,
            'city'          => $invoice->customer->city,
            'country'       => $invoice->customer->country
        ]);

        $file->setPaymentUrl(route('invoice.payment', [
            'token' => $invoice->token
        ]));

        return $file;
    }

    /**
     * @param CustomerInvoice $invoice
     * @param string $transaction_id
     */
    public function updateInvoice($invoice, $transaction_id, $processor = 'paypal')
    {
        $invoice->payment_processor = $processor;
        $invoice->transaction_id = $transaction_id;

        if($customer = $invoice->customer()->first()){
            $customer->increment('balance', $invoice->amount_paid);
        }

        $invoice->status = 'paid';
        $invoice->amount_paid = $amount = $invoice->total;

        $invoice->save();

        $details = __('Full payment using :processor', [
            'processor' => ucwords($processor)
        ]);

        $this->updateInvoiceTransactions($invoice, $details, $amount, $processor);
    }

    /**
     * @param CustomerInvoice $invoice
     * @param string $details
     * @param int|float $amount
     */
    private function updateInvoiceTransactions($invoice, $details, $amount, $processor)
    {
        $transaction = new CustomerInvoiceTransaction();
        $transaction->details = $details;
        $transaction->customer_id = $invoice->customer->id;
        $transaction->customer_invoice_id = $invoice->id;
        $transaction->amount = $amount;
        $transaction->processor = $processor;

        $user = $invoice->user;

        $user->customer_invoice_transactions()->save($transaction);
    }

    private function getInvoicePath($id)
    {
        return 'users/id/'.$id.'/uploads/files/customer/invoices';
    }
    
    private function getInvoiceName($token, $status)
    {
        return $token .'_'. $status .'.pdf';
    }
    
    private function getFilePath($invoice)
    {
        $name = $this->getInvoiceName($invoice->token, $invoice->status);

        $path = $this->getInvoicePath($invoice->user->id);
        
        return $path . '/' . $name;
    }
    
    private function absolutePath($path)
    {
        return storage_path($path);
    }
}
