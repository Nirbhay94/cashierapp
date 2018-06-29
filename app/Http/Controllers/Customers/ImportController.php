<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Sukohi\CsvValidator\Facades\CsvValidator;

class ImportController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('import_data')){
            return view('customers.import.index');
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
                        'nullable', 'exists:customers,id',
                        function($attribute, $value, $fail){
                            if(!Auth::user()->customers()->find($value)){
                                $message = __('Customer (:id) does not exists.', [
                                    'id' => $value
                                ]);

                                return $fail($message);
                            }
                        }
                    ],
                    'name'              => 'required|string',
                    'email'             => 'required_without_all:phone_number|nullable|email',
                    'phone_number'      => 'required_without_all:email|nullable|string',

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
                ]);

                if(!$validator->fails()) {
                    if($data = $validator->data()){
                        $data->each(function ($csv) use($file_path) {
                            if(isset($csv->id) && $csv->id){
                                $record = Auth::user()->customers()->find($csv->id);
                            }else{
                                if(Auth::user()->record('customers')){
                                    $record = new Customer();
                                }else{
                                    return false;
                                }
                            }

                            $record->name = $csv->name;
                            $record->email = $csv->email;
                            $record->phone_number = $csv->phone_number;

                            if(isset($csv->image) && $csv->image){
                                $images = explode(',', $csv->image);

                                $media = array_map(function ($image) use($file_path) {
                                    return $file_path .'/'. $image;
                                }, $images);

                                $record->images = implode(',', $media);
                            }

                            $record->location = $csv->location;
                            $record->city = $csv->city;
                            $record->zip = $csv->zip;
                            $record->country = $csv->country;

                            Auth::user()->customers()->save($record);
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
        $file = public_path('sample/customers_record.csv');

        return response()->download($file);
    }
}
