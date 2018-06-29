<?php

use Illuminate\Database\Seeder;
use Gerardojbaez\Laraplans\Models\Plan;
use Gerardojbaez\Laraplans\Models\PlanFeature;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!count(Plan::all())){
            $plan = new Plan();
            $plan->name = __('Starter Plan');
            $plan->description = __('This is a basic package.');
            $plan->price = 9.99;
            $plan->interval = 'month';
            $plan->interval_count = 1;
            $plan->trial_period_days = 7;
            $plan->sort_order = 1;
            $plan->save();

            // We specify its features...
            $default_features = config('laraplans.features');
            $features = array();

            foreach($default_features as $code => $feature){
                $feature = collect($feature)->only(['label', 'value', 'type', 'sort_order'])
                    ->toArray();
                $feature['code'] = $code;

                $features[] = new PlanFeature($feature);
            }

            // Attach to plan...
            $plan->features()->saveMany($features);
        }
    }
}
