<?php

namespace App\Http\Controllers\Customers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ListController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('customers')){
            return view('customers.list.index');
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->customers()->get();

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
                ->editColumn('balance', function($data){
                    if($number = $data->balance){
                        return money($number, Auth::user());
                    }
                })
                ->addColumn('invoices', function ($data){
                    $link = route('invoice.list').'?customer='.$data->id;

                    $html = $data->invoices()->count() . '&nbsp;';
                    $html .= '<a href="'.$link.'">';
                    $html .= '<i class="fa fa-link"></i>';
                    $html .= '</a>';

                    return $html;
                })
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('customers.list.edit', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-green edit-row">';
                    $html .= '<span class="material-icons">create</span>';
                    $html .= '</a>';

                    $html .= '<a href="'.route('customers.list.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
                    $html .= '<span class="material-icons">clear</span>';
                    $html .= '</a>';

                    return $html;
                })
                ->rawColumns(['action', 'images', 'invoices'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        if(Auth::user()->record('customers')){
            $record = new Customer();
            $record->name = $request->name;
            $record->email = $request->email;
            $record->phone_number = $request->phone_number;

            if($request->media){
                $record->images = implode(',', $request->media);
            }

            $record->location = $request->location;
            $record->city = $request->city;
            $record->zip = $request->zip;
            $record->country = $request->country;

            Auth::user()->customers()->save($record);

            $message = __('Your record has been added successfully!');

            return response()->json($message,200);
        }else{
            return response()->json(trans('laraplans.max_usage_reached'),403);
        }
    }

    public function edit(Request $request, $id)
    {
        if($record = Auth::user()->customers()->find($id)){
            $this->validate($request, $this->validationRules());

            $record->name = $request->name;
            $record->email = $request->email;
            $record->phone_number = $request->phone_number;

            if($request->media){
                $record->images = implode(',', $request->media);
            }

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
            if($record = Auth::user()->customers()->find($id)){
                try{
                    Auth::user()->reduce('customers');

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

    public function search(Request $request)
    {
        if($request->ajax()){
            $response = [];

            if($term = $request->q){
                $tags = Customer::search($term)->limit(10)->pluck('name', 'id');

                foreach($tags as $key => $value){
                    $response[] = ['id' => $key, 'text' => $value];
                }
            }

            return response()->json($response);
        }else{
            return abort(403);
        }
    }

    public function get(Request $request)
    {
        if($customer = Auth::user()->customers()->find($request->id)){
            return response()->json($customer->toArray());
        }else{
            $message = __('User could not be found!');

            return response()->json($message, 404);
        }
    }

    public function fetch(Request $request)
    {
        $order = $request->order ?: null;
        $column = $request->column ?: null;
        $page = $request->page ?: 0;

        $records = Auth::user()->customers();

        if($order && $column){
            $records = $records->orderBy($column, $order);
        }

        $records = $records->paginate(10, ['*'], 'page', $page);

        return $records->toArray();
    }
}
