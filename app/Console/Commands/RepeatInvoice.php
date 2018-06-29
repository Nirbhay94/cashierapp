<?php

namespace App\Console\Commands;

use App\Models\CustomerInvoice;
use App\Models\Product;
use App\Notifications\Customer\NewInvoice;
use App\Notifications\LowProductQuantity;
use App\Notifications\MissingProduct;
use App\Traits\Transactions;
use Carbon\Carbon;
use Dirape\Token\Token;
use Illuminate\Console\Command;

class RepeatInvoice extends Command
{
    use Transactions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:repeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repeat all scheduled invoice';

    /**
     * Token generator
     */
    protected $token;

    /**
     * Create a new command instance.
     *
     * @param Token $token
     * @return void
     */
    public function __construct(Token $token)
    {
        parent::__construct();

        $this->token = $token;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $invoices = CustomerInvoice::whereNotNull('repeat_data')
            ->where('repeat_until', '>', Carbon::now())->get();

        foreach($invoices as $invoice){
            $user = $invoice->user()->first();

            if(can_use_feature($user, 'issue_invoice')){
                if($this->shouldRepeat($invoice)) {
                    $items = json_decode($invoice->items);

                    foreach($items as $item){
                        $quantity = $item->amount;

                        if($product = $user->products()->find($item->id)){
                            $product->increment('sales', $quantity);

                            if(!$this->validateQuantity($product, $quantity)){
                                $message = __('We could not re-issue invoice at this time. Token: :token', [
                                    'token' => $invoice->token
                                ]);

                                $user->notify(new LowProductQuantity($user, $product, $message));

                                $this->error('Low product quantity: '.$product->name.' User: '. $user->name);

                                continue 2;
                            }else{
                                if($product->track == 'yes'){
                                    $product->decrement('quantity', $quantity);
                                }
                            }
                        }else{
                            $message = __('We could not re-issue invoice at this time. Token: :token', [
                                'token' => $invoice->token
                            ]);

                            $user->notify(new MissingProduct($user, $item->name, $message));

                            $this->error('Missing product: '.$item->name.' User: '. $user->name);

                            continue 2;
                        }
                    }

                    $record = new CustomerInvoice();

                    $record->token = $this->token->Unique('customer_invoices', 'token', 10);
                    $record->customer_id = $invoice->customer_id;
                    $record->note = $invoice->note;
                    $record->allow_partial = $invoice->allow_partial;
                    $record->product_ids = $invoice->product_ids;
                    $record->tax_ids = $invoice->tax_ids;
                    $record->tax = $invoice->tax;
                    $record->sub_total = $invoice->sub_total;
                    $record->total = $invoice->total;
                    $record->items = $invoice->items;

                    $new_invoice = $user->customer_invoices()->save($record);

                    if($customer = $new_invoice->customer()->first()){
                        $items = json_decode($invoice->items, true);

                        $customer->increment('purchases', $this->countPurchases($items));

                        $customer->notify(new NewInvoice($customer, $new_invoice));
                    }

                    $this->line('Issued invoice #'. $new_invoice->token .' User: '. $user->name);

                    $invoice->last_repeat = Carbon::now();
                }else{
                    continue;
                }

                $invoice->save();
            }
        }

        $this->line('Completed execution!');
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return bool
     */
    public function validateQuantity($product, $quantity)
    {
        if($product->track == 'yes' && $quantity > $product->quantity){
            return false;
        }

        return true;
    }
    /**
     * @param CustomerInvoice $invoice
     * @return bool
     */
    public function shouldRepeat($invoice)
    {
        $data = json_decode($invoice->repeat_data);
        
        $type = $data->type;
        
        $interval = $data->interval;
        
        if($invoice->last_repeat != null && $interval) {
            $last = Carbon::parse($invoice->last_repeat);

            $next = $this->nextRepeat($last, $interval, $type);

            if ($next <= Carbon::now()) {
                return true;
            }
        }else{
            $created_at = $invoice->created_at;

            $first = $this->firstRepeat($created_at, $type, $interval);

            if($first <= Carbon::now()){
                return true;
            }
        }
        
        return false;
    }
    
    public function firstRepeat($created_at, $type, $interval)
    {
        $created = Carbon::parse($created_at);

        switch($type){
            case 'daily':
                $created->addDays($interval);
                break;
            case 'weekly':
                $created->addWeeks($interval);
                break;
            case 'monthly':
                $created->addMonths($interval);
                break;
            case 'yearly':
                $created->addYears($interval);
                break;
        }

        return $created;
    }
    /**
     * @param Carbon $last
     * @param int $interval
     * @param string $type
     * @return Carbon|null
     */
    public function nextRepeat($last, $interval, $type)
    {
        $next = Carbon::tomorrow();
        
        switch($type){
            case 'daily':
                $next = $last->addDays($interval);
                break;
            case 'weekly':
                $next = $last->addWeeks($interval);
                break;
            case 'monthly':
                $next = $last->addMonths($interval);
                break;
            case 'yearly':
                $next = $last->addYears($interval);
                break;
        }
        
        return $next;
    }
}
