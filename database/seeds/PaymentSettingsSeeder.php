<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentSetting;

class PaymentSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!PaymentSetting::first()){
            $payment_settings = new PaymentSetting();

            $payment_settings->save();
        }
    }
}
