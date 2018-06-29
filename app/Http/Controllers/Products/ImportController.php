<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Sukohi\CsvValidator\Facades\CsvValidator;

class ImportController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('import_data')){
            return view('products.import.index');
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function upload(Request $request)
    {
        set_time_limit(60 * 30);

        if($file = $request->file('file')){
            $file_path = collect((array) config('elfinder.dir'))->first() .'/'. Auth::user()->id;

            try{
                $validator = CsvValidator::make($file->getRealPath(), [
                    'id'                => [
                        'nullable', 'exists:products,id',
                        function($attribute, $value, $fail){
                            if(!Auth::user()->products()->find($value)){
                                $message = __('Product (:id) does not exists.', [
                                    'id' => $value
                                ]);

                                return $fail($message);
                            }
                        }
                    ],
                    'name'              => 'required|string',
                    'weight'            => 'nullable|integer',
                    'brand'             => 'required|string',
                    'cost'              => 'required|numeric|min:1',
                    'price'             => 'required|numeric|min:1',
                    'barcode_type'      => [
                        'required', Rule::in(get_barcode_types())
                    ],
                    'barcode'           => 'required|integer',
                    'quantity'          => 'required|integer|min:1',
                    'track'             => 'required|in:yes,no',

                    'taxes'             => ['nullable',
                        function($attribute, $value, $fail){
                            $ids = explode(',', $value);

                            foreach ($ids as $id){
                                if(!Auth::user()->product_taxes()->find($id)){
                                    $message = __('Tax (:id) does not exists.', [
                                        'id' => $id
                                    ]);

                                    return $fail($message);
                                }
                            }
                        }
                    ],

                    'image'             => ['nullable',
                        function($attribute, $value, $fail) use ($file_path){
                            $images = explode(',', $value);

                            foreach ($images as $image){
                                if (!File::exists(public_path($file_path .'/'. $image))) {
                                    $message = __(':image does not exists.', [
                                        'image' => $image
                                    ]);

                                    return $fail($message);
                                }
                            }
                        }
                    ],

                    'product_unit_id'       => [
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
                    'description'           => 'required|string'
                ]);

                if(!$validator->fails()) {
                    if($data = $validator->data()){
                        $data->each(function ($csv) use($file_path) {
                            if(isset($csv->id) && $csv->id){
                                $record = Auth::user()->products()->find($csv->id);
                            }else{
                                if(Auth::user()->record('products')){
                                    $record = new Product();
                                }else{
                                    return false;
                                }
                            }

                            $record->name = $csv->name;
                            $record->weight = $csv->weight;
                            $record->brand = $csv->brand;
                            $record->cost = money_number_format($csv->cost);
                            $record->price = money_number_format($csv->price);
                            $record->barcode_type = $csv->barcode_type;
                            $record->barcode = $csv->barcode;
                            $record->quantity = $csv->quantity;
                            $record->track = $csv->track;

                            if(isset($csv->taxes) && $csv->taxes){
                                $record->taxes = $csv->taxes;
                            }

                            if(isset($csv->image) && $csv->image){
                                $images = explode(',', $csv->image);

                                $media = array_map(function ($image) use($file_path) {
                                    return $file_path .'/'. $image;
                                }, $images);

                                $record->images = implode(',', $media);
                            }

                            $record->product_unit_id = $csv->product_unit_id;
                            $record->product_category_id = $csv->product_category_id;
                            $record->description = $csv->description;

                            Auth::user()->products()->save($record);
                        });

                        $message = __('A total of :num records has been added', [
                            'num'   => $data->count()
                        ]);

                        return response()->json($message, 200);
                    }else{
                        $message = __('You file does not contain enough data!');
                        
                        return response()->json($message, 403);
                    }
                }else{
                    return response()->json($validator->getErrors(), 403);
                }
            }catch(\Exception $e){
                return response()->json($e->getMessage(), 403);
            }
        }else{
            $message = __('Something went wrong with the file! Please try again.');

            return response()->json($message, 403);
        }
    }

    public function download()
    {
        $file = public_path('sample/products_record.csv');

        return response()->download($file);
    }
}
