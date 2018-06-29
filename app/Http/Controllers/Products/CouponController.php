<?php

namespace App\Http\Controllers\Products;

use App\Models\ProductCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    public function index()
    {
        if(Auth::user()->can_use('products')){
            $categories = Auth::user()->product_categories()
                ->get()->pluck('name', 'id');

            return view('products.coupon.index')
                ->with(compact('categories'));
        }else{
            return redirect()->route('home')->with([
                'error' => trans('laraplans.required')
            ]);
        }
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->product_coupons()->get();

            return DataTables::of($records)
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('products.coupon.edit', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-green edit-row">';
                    $html .= '<span class="material-icons">create</span>';
                    $html .= '</a>';

                    $html .= '<a href="'.route('products.coupon.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
                    $html .= '<span class="material-icons">clear</span>';
                    $html .= '</a>';

                    return $html;
                })
                ->addColumn('categories', function($data){
                    if($data->product_categories){
                        $ids = explode(',', $data->product_categories);

                        $collection = array();

                        foreach($ids as $id){
                            $html = '';

                            if($category = Auth::user()->product_categories()->find($id)){
                                $html .= '<div tabindex="0" class="chip">';
                                $html .= '<span>'.$category->name.'</span>';
                                $html .= '</div>';

                                $collection[] = $html;
                            }
                        }

                        return implode('', $collection);
                    }
                })
                ->rawColumns(['action', 'categories'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $record = new ProductCoupon();

        $record->name = $request->name;
        $record->code = $request->code;
        $record->discount = money_number_format($request->discount);
        $record->type = $request->type;

        $record->start_date = Carbon::parse($request->start_date);
        $record->end_date = Carbon::parse($request->end_date);

        $record->product_categories = implode(',', $request->product_categories);

        Auth::user()->product_coupons()->save($record);

        $message = __('Your record has been added successfully!');

        return response()->json($message,200);
    }

    public function edit(Request $request, $id)
    {
        if($record = Auth::user()->product_coupons()->find($id)){
            $this->validate($request, $this->validationRules());

            $record->name = $request->name;
            $record->code = $request->code;
            $record->discount = money_number_format($request->discount);
            $record->type = $request->type;

            $record->start_date = Carbon::parse($request->start_date);
            $record->end_date = Carbon::parse($request->end_date);

            $record->product_categories = implode(',', $request->product_categories);

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
            if($record = Auth::user()->product_coupons()->find($id)){
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

    public function get(Request $request)
    {
        $now = Carbon::now();

        $coupon = Auth::user()->product_coupons()
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('code', $request->code)->first();

        $result = $coupon ?: [];

        return response()->json($result, 200);
    }

    public function validationRules()
    {
        return [
            'name'                  => 'required|string',
            'code'                  => 'required|string',
            'discount'              => 'required|numeric',

            'start_date'            => 'required|string|date_format:Y-m-d H:i:s',
            'end_date'              => 'required|string|date_format:Y-m-d H:i:s|after:start_date',

            'type'                  => 'required|in:fixed,percentage',
            'product_categories'    => 'required|array',
            'product_categories.*'  => [
                'integer', 'exists:product_categories,id',
                function($attribute, $value, $fail){
                    if(!Auth::user()->product_categories()->find($value)){
                        $message = __('Product category (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],
        ];
    }
}
