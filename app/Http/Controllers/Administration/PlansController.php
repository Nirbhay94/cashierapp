<?php

namespace App\Http\Controllers\Administration;

use Gerardojbaez\Laraplans\Models\Plan;
use Gerardojbaez\Laraplans\Models\PlanFeature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlansController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $records = array();
        Plan::orderBy('sort_order', 'ASC')->chunk(4, function($plans) use (&$records) {
            $records[] = $plans;
        });

        return view('administration.plans.index', compact('records'));
    }

    public function edit($id)
    {
        if($plan = Plan::find($id)){
            return view('administration.plans.edit', compact('plan'));
        }else{
            return abort(404);
        }
    }

    public function destroy($id, Request $request)
    {
        if($request->ajax()){
            try{
                if($plan = Plan::find($id)){
                    $plan->delete();

                    return response()->json(__('Plan has been deleted successfully!'), 200);
                }else{
                    return response()->json(__('Your record could not be found!'), 404);
                }
            }catch(\Exception $e){
                return response()->json(__('Oops! Something went wrong. Message:').' '.$e->getMessage(), 403);
            }
        }else{
            return abort(403);
        }
    }

    public function update($id, Request $request)
    {
        if($plan = Plan::find($id)){
            $default_features = config('laraplans.features');

            $rules = [
                'name' => 'required|max:20',
                'description' => 'required|max:100',
                'price' => 'required|numeric',
                'interval' => 'required|in:day,week,month,year',
                'interval_count' => 'required|numeric|integer|max:50',
                'trial_period_days' => 'required|numeric|integer|max:50',
                'sort_order' => 'required|numeric|integer|max:50',
                'min_quantity' => 'required|numeric|integer',
                'max_quantity' => 'nullable|numeric|integer'
            ];

            foreach($default_features as $code => $feature){
                $rules[] = [
                    $code => 'required|numeric',
                ];
            }

            $this->validate($request, $rules);

            $input = $request->only([
                'name', 'description', 'price', 'interval', 'interval_count', 'interval_count',
                'trial_period_days', 'sort_order', 'min_quantity', 'max_quantity'
            ]);

            $plan->fill($input);
            $plan->save();

            foreach($default_features as $code => $feature){
                $record = $plan->features()->where('code', $code)->first();
                $record->value = $request->get($code);
                $record->save();
            }

            return redirect()->back()->with('success', __('Plan has been updated successfully!'));

        }else{
            return abort(404);
        }
    }

    public function create()
    {
        $features = collect(config('laraplans.features'))->toArray();

        return view('administration.plans.create', compact('features'));
    }

    public function store(Request $request)
    {
        $default_features = config('laraplans.features');

        $rules = [
            'name' => 'required:max:20',
            'description' => 'required|max:100',
            'price' => 'required|numeric',
            'interval' => 'required|in:day,week,month,year',
            'interval_count' => 'required|numeric|integer|max:50',
            'trial_period_days' => 'required|numeric|integer|max:50',
            'sort_order' => 'required|numeric|integer|max:50',
            'min_quantity' => 'required|numeric|integer',
            'max_quantity' => 'nullable|numeric|integer'
        ];

        foreach($default_features as $code => $feature){
            $rules[] = [
                $code => 'required|numeric',
            ];
        }

        $this->validate($request, $rules);

        $plan = new Plan();

        $input = $request->only([
            'name', 'description', 'price', 'interval', 'interval_count', 'interval_count',
            'trial_period_days', 'sort_order', 'min_quantity', 'max_quantity'
        ]);

        $features = [];

        foreach($default_features as $code => $data){
            $feature = collect($data)->only(['label', 'value', 'type', 'sort_order'])
                ->put('code', $code)->put('value', $request->get($code));

            $features[] = new PlanFeature($feature->toArray());
        }


        $plan->fill($input);
        $plan->save();

        // Save Features to plan...
        $plan->features()->saveMany($features);

        return redirect()->route('administration.plans.index')
            ->with('success', __('Plan has been created successfully!'));
    }
}
