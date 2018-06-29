<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InsufficientBalance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class RenewSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew all expired subscriptions or notify user if balance is insufficient!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::where('auto_renewal', 'yes')
            ->where('verified', true)
            ->has('subscriptions')->chunk(100, function($users){
                foreach($users as $user){
                    $subscription = $user->subscription('main');

                    if($subscription && $subscription->isEnded() && !$subscription->onTrial()){
                        $plan = $subscription->plan;

                        $statement = __('Auto renewal of the subscription: :plan', [
                            'plan'  => $plan->name
                        ]);

                        if($user->currentPoints() >= $plan->price){

                            $invoice = new Invoice();
                            $invoice->user_id = $user->id;
                            $invoice->transaction_id = uniqid();
                            $invoice->note = $statement;
                            $items = [[$plan->name, ($plan->price / 1), 1]];
                            $invoice->total = $plan->price;
                            $invoice->items = json_encode($items);
                            $invoice->save();

                            $data = ['invoice_id' => $invoice->id];

                            $user->addPoints(-$plan->price, $statement, $data);

                            $subscription->renew();

                            $this->line('Renewed '. $plan->name .' on behalf of '.$user->name);
                        }else{
                            // Notify user of insufficient balance...
                            $url = route('subscription.topup');
                            $user->notify(new InsufficientBalance($user, $url));

                            // Update settings...
                            $user->auto_renewal = 'no';
                            $user->save();

                            $this->error('Unable to renew '. $plan->name .'on behalf of'. $user->name);
                            $this->error('Reason: Insufficient balance');
                        }
                    }
                }
            });

    }
}
