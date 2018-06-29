<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AllController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('products')){
            $taxes = Auth::user()->product_taxes()->get()->pluck('name', 'id');

            $categories = Auth::user()->product_categories()->get()->pluck('name', 'id');

            $units = Auth::user()->product_units()->get()->pluck('name', 'id');

            return view('products.all.index')
                ->with(compact('taxes', 'categories', 'units'));
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->products()->get();

            return DataTables::of($records)
                ->addColumn('images', function($data){
                    if($data->images){
                        $images = explode(',', $data->images);
                        $image_path = $images[array_rand($images)];
                    }else{
                        $image_path = 'images/product-placeholder.png';
                    }

                    return '<img src="'.url($image_path).'" alt="'.$data->name.'" class="img-circle" width="60" height="60"/>';
                })
                ->addColumn('cost_full', function($data){
                    if($number = $data->cost){
                        return money($number, Auth::user());
                    }
                })
                ->addColumn('price_full', function($data){
                    if($number = $data->price){
                        return money($number, Auth::user());
                    }
                })
                ->addColumn('taxes_name', function($data){
                    if($data->taxes){
                        $ids = explode(',', $data->taxes);

                        $collection = array();

                        foreach($ids as $id){
                            if($tax = Auth::user()->product_taxes()->find($id)){
                                $collection[] = $tax->name .' ('. $tax->code .')';
                            }
                        }

                        return implode(', ', $collection);
                    }
                })
                ->addColumn('category_name', function ($data){
                    return $data->category->name;
                })
                ->addColumn('unit_name', function ($data){
                    return $data->unit->name;
                })
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('products.all.edit', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-green edit-row">';
                    $html .= '<span class="material-icons">create</span>';
                    $html .= '</a>';

                    $html .= '<a href="'.route('products.all.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
                    $html .= '<span class="material-icons">clear</span>';
                    $html .= '</a>';

                    return $html;
                })
                ->rawColumns(['action', 'images', 'cost_full', 'price_full'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        if(Auth::user()->record('products')){
            $record = new Product();
            $record->name = $request->name;
            $record->weight = $request->weight;
            $record->brand = $request->brand;
            $record->cost = money_number_format($request->cost);
            $record->price = money_number_format($request->price);
            $record->barcode_type = $request->barcode_type;
            $record->barcode = $request->barcode;
            $record->quantity = $request->quantity;
            $record->track = $request->track;

            if($request->taxes){
                $record->taxes = implode(',', $request->taxes);
            }

            if($request->media){
                $record->images = implode(',', $request->media);
            }

            $record->product_unit_id = $request->product_unit_id;
            $record->product_category_id = $request->product_category_id;
            $record->description = $request->description;

            Auth::user()->products()->save($record);

            $message = __('Your record has been added successfully!');

            return response()->json($message,200);
        }else{
            return response()->json(trans('laraplans.max_usage_reached'),403);
        }
    }

    public function edit(Request $request, $id)
    {
        if($record = Auth::user()->products()->find($id)){
            $this->validate($request, $this->validationRules());

            $record->name = $request->name;
            $record->weight = $request->weight;
            $record->brand = $request->brand;
            $record->cost = money_number_format($request->cost);
            $record->price = money_number_format($request->price);
            $record->barcode_type = $request->barcode_type;
            $record->barcode = $request->barcode;
            $record->quantity = $request->quantity;
            $record->track = $request->track;

            if($request->taxes){
                $record->taxes = implode(',', $request->taxes);
            }

            if($request->media){
                $record->images = implode(',', $request->media);
            }

            $record->product_unit_id = $request->product_unit_id;
            $record->product_category_id = $request->product_category_id;
            $record->description = $request->description;

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
            if($record = Auth::user()->products()->find($id)){
                try{
                    Auth::user()->reduce('products');

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
            'name'              => 'required|string',
            'weight'            => 'nullable|string',
            'brand'             => 'required|string',
            'cost'              => 'required|numeric|min:1',
            'price'             => 'required|numeric|min:1',
            'barcode_type'      => [
                'required', Rule::in(get_barcode_types())
            ],
            'barcode'           => 'required|integer',
            'quantity'          => 'required|integer|min:1',
            'track'             => 'required|in:yes,no',

            'taxes'             => [
                'nullable', 'array',
                function($attribute, $value, $fail){
                    foreach ($value as $id){
                        if(!Auth::user()->product_taxes()->find($id)){
                            $message = __('Tax (:id) does not exists.', [
                                'id' => $id
                            ]);

                            return $fail($message);
                        }
                    }
                }
            ],

            'taxes.*'           => 'integer|exists:product_taxes,id',

            'media'             => 'nullable|array',
            'media.*'           => [
                function($attribute, $value, $fail){
                    if (!File::exists(public_path($value))) {
                        $message = __(':image does not exists.', [
                            'image' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],

            'product_unit_id'   => [
                'required', 'integer',
                'exists:product_units,id',
                function($attribute, $value, $fail){
                    if(!Auth::user()->product_units()->find($value)){
                        $message = __('Product unit (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],
            'product_category_id'   => [
                'required', 'integer',
                'exists:product_categories,id',
                function($attribute, $value, $fail){
                    if(!Auth::user()->product_categories()->find($value)){
                        $message = __('Product category (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],
            'description'       => 'required|string'
        ];
    }

    public function search(Request $request)
    {
        if($request->ajax()){
            $response = [];

            if($term = $request->q){
                $tags = Product::search($term)->limit(10)->pluck('name', 'id');

                foreach($tags as $key => $value){
                    $response[] = ['id' => $key, 'text' => $value];
                }
            }

            return response()->json($response);
        }else{
            return abort(400);
        }
    }

    public function fetch(Request $request)
    {
        $categories = $request->categories ?: [];
        $order = $request->order ?: null;
        $column = $request->column ?: null;
        $page = $request->page ?: 0;

        if(!$request->keywords){
            $records = Auth::user()->products()
                ->whereIn('product_category_id', $categories);

            if($order && $column){
                $records = $records->orderBy($column, $order);
            }

            $records = $records->paginate(10, ['*'], 'page', $page);
        }else{
            $records = Auth::user()->products()->search($request->keywords)
                ->whereIn('product_category_id', $categories);

            if($order && $column){
                $records = $records->orderBy($column, $order);
            }

            $records = $records->paginate(10, ['*'], 'page', $page);
        }

        return $records->toArray();
    }
}
