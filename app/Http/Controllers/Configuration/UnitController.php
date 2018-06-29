<?php

namespace App\Http\Controllers\Configuration;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    public function index()
    {
        $units = Auth::user()->product_units()->whereNull('base')
            ->get()->pluck('name', 'id');
        
        return view('configuration.unit.index')
            ->with(compact('units'));
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->product_units()->get();

            return DataTables::of($records)
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('configuration.unit.edit', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-green edit-row">';
                    $html .= '<span class="material-icons">create</span>';
                    $html .= '</a>';

                    $html .= '<a href="'.route('configuration.unit.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
                    $html .= '<span class="material-icons">clear</span>';
                    $html .= '</a>';

                    return $html;
                })
                ->addColumn('base_name', function($data){
                    $collection = array();

                    if($base = Auth::user()->product_units()->find($data->base)){
                        $collection[] = $base->name;
                    }

                    return implode('', $collection);
                })
                ->rawColumns(['action'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $record = new ProductUnit();

        $record->name = $request->name;
        $record->code = $request->code;
        $record->base = $request->base;

        if($request->base){
            $record->value = $request->value;
        }else{
            $record->value = 1;
        }

        Auth::user()->product_units()->save($record);

        $message = __('Your record has been added successfully!');

        return response()->json($message,200);
    }

    public function edit(Request $request, $id)
    {
        if($record = Auth::user()->product_units()->find($id)){
            $rules = [
                'base' => [
                    'nullable', 'integer', 'exists:product_units,id',

                    function($attribute, $value, $fail){
                        if($unit = Auth::user()->product_units()->find($value)){
                            if ($unit->base != null) {
                                $message = __('You may only select a root base.');

                                return $fail($message);
                            }
                        }else{
                            $message = __('Base unit (:id) does not exists.', [
                                'id' => $value
                            ]);

                            return $fail($message);
                        }
                    },

                    'not_in:'.$id
                ]
            ];

            $this->validate($request, array_merge($this->validationRules(), $rules));

            $record->name = $request->name;
            $record->code = $request->code;
            $record->base = $request->base;

            if($request->base){
                $record->value = $request->value;
            }else{
                $record->value = 1;
            }

            $record->save();

            $message = __('The record has been saved!');

            return response()->json($message, 200);
        }else{
            $message = __('The record could not be found!');

            return response()->json($message, 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            if($record = Auth::user()->product_units()->find($id)){
                try{
                    $record->delete();

                    return response()->json(__('The record has been removed!'), 200);
                }catch(\Exception $e){
                    return response()->json($e->getMessage(), 400);
                }
            }else{
                $message = __('The record could not be found!');

                return response()->json($message, 404);
            }
        }else{
            return abort(403);
        }
    }

    public function validationRules()
    {
        return [
            'name'                  => 'required|string',
            'code'                  => 'required|string',
            'base'                  => [
                'nullable', 'integer', 'exists:product_units,id',

                function($attribute, $value, $fail){
                    if($unit = Auth::user()->product_units()->find($value)){
                        if ($unit->base != null) {
                            $message = __('You may only select a root base.');

                            return $fail($message);
                        }
                    }else{
                        $message = __('Base unit (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                },

            ],
            'value'                 => 'required_with:base|numeric',
        ];
    }
}
