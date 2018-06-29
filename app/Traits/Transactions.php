<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 5/18/2018
 * Time: 4:04 AM
 */

namespace App\Traits;


use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceTransaction;
use App\Models\ProductCoupon;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

trait Transactions
{
    /**
     * This is updated when a coupon
     * is applied
     *
     * @var $discount
     */
    public $discount = null;

    /**
     * @param array $taxes
     * @param array $tax_ids
     */
    private function updateTaxes(array &$taxes, array $tax_ids)
    {
        foreach($tax_ids as $id){
            if($product_tax = Auth::user()->product_taxes()->find($id)){
                $tax = [
                    'type' => $product_tax->type,
                    'amount' => $product_tax->amount
                ];

                $taxes[$product_tax->id] = $tax;
            }
        }
    }
    /**
     * Apply Customer's Balance
     *
     * @param Customer $customer
     * @param CustomerInvoice $invoice
     */
    private function applyBalance($customer, &$invoice)
    {
        if($customer->balance > 0){
            $balance = (int) $customer->balance;
            $total = ($invoice->total - $invoice->amount_paid);

            if($balance >= $total){
                $customer->balance = money_number_format($balance - $total);
                $invoice->amount_paid += money_number_format($total);
                $invoice->status = 'paid';

                $amount = money_number_format($total);

                $details = __('Full payment using balance');
            }else if($invoice->allow_partial == 'yes'){
                $invoice->amount_paid += money_number_format($balance);
                $customer->balance = money_number_format(0);
                $invoice->status = 'partial';

                $amount = money_number_format($balance);

                $details = __('Partial payment using balance');
            }

            if(isset($details) && isset($amount)){
                $this->updateInvoiceTransactions($invoice, $details, $amount);
            }

            $customer->save();
            $invoice->save();
        }
    }

    /**
     * @param CustomerInvoice $invoice
     * @param string $details
     * @param int|float $amount
     */
    private function updateInvoiceTransactions($invoice, $details, $amount)
    {
        $transaction = new CustomerInvoiceTransaction();
        $transaction->details = $details;
        $transaction->customer_id = $invoice->customer->id;
        $transaction->customer_invoice_id = $invoice->id;
        $transaction->amount = $amount;
        $transaction->processor = 'balance';

        Auth::user()->customer_invoice_transactions()->save($transaction);
    }

    /**
     * Calculate sub total
     *
     * @param $items
     * @param ProductCoupon $code
     * @return Collection
     */
    private function subTotalPrice($items, $code = null)
    {
        $total = collect($items)->sum(function ($item) {
            return bcmul($item['price'], $item['amount'], 2);
        });

        $now = Carbon::now();

        $coupon = Auth::user()->product_coupons()
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('code', $code)->first();

        $amount = $total;

        if($coupon){
            if($coupon->type == 'percentage'){
                $this->discount = bcdiv(bcmul($coupon->discount, $total, 2), 100, 2);
            }else{
                $this->discount = $coupon->discount;
            }

            $amount = max($total - $this->discount, 0);
        }

        return $amount;
    }

    /**
     * Count total item purchase
     *
     * @param array $items
     * @return Collection
     */
    private function countPurchases($items)
    {
        return collect($items)->sum(function ($item) {
            return $item['amount'];
        });
    }

    /**
     * Get total tax amount
     *
     * @param $total
     * @param $taxes
     * @return int
     */
    private function getTotalTaxAmount($total, $taxes)
    {
        $amount = 0;

        foreach($taxes as $tax){
            if($tax['type'] == 'percentage'){
                $amount += bcdiv(bcmul($tax['amount'], $total, 2), 100, 2);
            }else{
                $amount += $tax['amount'];
            }
        }

        return $amount;
    }

    /**
     * Count total item purchase
     *
     * @param array $items
     * @param int $tax
     * @return int
     */
    public function calcProfit($items, $tax)
    {
        $profit = 0;

        collect($items)->each(function ($item) use(&$profit) {
            if($product = Auth::user()->products()->find($item['id'])){
                $sale = ($item['price'] * $item['amount']);

                $cost = ($product->cost * $item['amount']);

                $profit += $sale - $cost;
            }
        });

        return $profit;
    }
}