<?php

namespace App\Http\Controllers\People;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class SuppliersController extends Controller
{
    public function index()
    {
        return view('people.suppliers.index');
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->suppliers()->get();

            return DataTables::of($records)
                ->addColumn('images', function($data){
                    if($data->images){
                        $images = explode(',', $data->images);
                        $image_path = $images[array_rand($images)];
                    }else{
                        $image_path = 'images/default-avatar.png';
                    }

                    return '<img src="'.url($image_path).'" alt="'.$data->name.'" class="img-circle" width="60" height="60"/>';
                })
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('people.suppliers.edit', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-green edit-row">';
                    $html .= '<span class="material-icons">create</span>';
                    $html .= '</a>';

                    $html .= '<a href="'.route('people.suppliers.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
                    $html .= '<span class="material-icons">clear</span>';
                    $html .= '</a>';

                    return $html;
                })
                ->rawColumns(['action', 'images'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $record = new Supplier();

        $record->name = $request->name;
        $record->email = $request->email;
        $record->phone_number = $request->phone_number;

        if($request->media){
            $record->images = implode(',', $request->media);
        }

        $record->company = $request->company;
        $record->gst_number = $request->gst_number;
        $record->location = $request->location;
        $record->city = $request->city;
        $record->zip = $request->zip;
        $record->country = $request->country;

        Auth::user()->suppliers()->save($record);

        $message = __('Your record has been added successfully!');

        return response()->json($message,200);
    }

    public function edit(Request $request, $id)
    {
        if($record = Auth::user()->suppliers()->find($id)){
            $record->name = $request->name;
            $record->email = $request->email;
            $record->phone_number = $request->phone_number;

            if($request->media){
                $record->images = implode(',', $request->media);
            }

            $record->company = $request->company;
            $record->gst_number = $request->gst_number;
            $record->location = $request->location;
            $record->city = $request->city;
            $record->zip = $request->zip;
            $record->country = $request->country;

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
            if($record = Auth::user()->suppliers()->find($id)){
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
            'name'              => 'required|string',
            'company'           => 'required|string',
            'email'             => 'required_without_all:phone_number|nullable|email',
            'phone_number'      => 'required_without_all:email|nullable|string',

            'media'             => 'nullable|array',
            'media.*'           => [
                function($attribute, $value, $fail){
                    if (!File::exists(public_path($value))) {
                        $message = __('One of your selected file does not exists.');

                        return $fail($message);
                    }
                }
            ],
        ];
    }
}
