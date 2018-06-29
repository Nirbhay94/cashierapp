<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 5/19/2018
 * Time: 5:38 PM
 */

namespace App\Http\Controllers\Dashboard\Traits;


use App\Models\CustomerInvoice;
use App\Models\PosTransaction;
use App\Models\SaleReport;
use App\Traits\Transactions;
use Illuminate\Support\Facades\Auth;

trait UpdateReports
{
    use Transactions;

    public function updateSales()
    {
        $last_report_date = null;

        if($last_report = Auth::user()->sale_reports()->latest()->first()){
            $last_report_date = $last_report->date;
        }

        if($transactions = $this->latestPosTransactions($last_report_date)){
            foreach($transactions as $transaction){
                $report = new SaleReport();

                $items = json_decode($transaction->items, true);

                $report->customer_id = $transaction->customer_id;

                $report->pos_transaction_id = $transaction->id;

                $report->tax = $transaction->tax;
                $report->total = $transaction->total;

                $report->profit = $this->calcProfit($items, $transaction->tax);
                $report->purchases = $this->countPurchases($items);

                $report->date = $transaction->created_at;

                Auth::user()->sale_reports()->save($report);
            }
        }

        if($invoices = $this->latestInvoice($last_report_date)){
            foreach($invoices as $invoice){
                $report = new SaleReport();

                $items = json_decode($invoice->items, true);

                $report->customer_id = $invoice->customer_id;

                $report->customer_invoice_id = $invoice->id;

                $report->tax = $invoice->tax;
                $report->total = $invoice->total;

                $report->profit = $this->calcProfit($items, $invoice->tax);
                $report->purchases = $this->countPurchases($items);

                $report->date = $invoice->created_at;

                Auth::user()->sale_reports()->save($report);
            }
        }

    }

    /**
     * @param $from
     * @return PosTransaction
     */
    private function latestPosTransactions($from)
    {
        $records = Auth::user()->pos_transactions()
            ->where('status', 'completed');

        if($from){
            $records = $records->where('created_at', '>', $from);
        }

        return $records->get();
    }

    /**
     * @param $from
     * @return CustomerInvoice
     */
    private function latestInvoice($from)
    {
        $records = Auth::user()->customer_invoices()
            ->where('status', 'paid');

        if($from){
            $records = $records->where('created_at', '>', $from);
        }

        return $records->get();
    }
}