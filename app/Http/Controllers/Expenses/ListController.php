<?php

namespace App\Http\Controllers\Expenses;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ListController extends Controller
{
    public function index()
    {
        return view('expenses.list.index', [
            'categories' => Auth::user()->expense_categories()->get()->pluck('name', 'id')
        ]);
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->expenses()->get();

            return DataTables::of($records)
                ->addColumn('category_name', function($data){
                    return $data->category->name;
                })
                ->addColumn('formatted_amount', function($data){
                    return money($data->amount, Auth::user());
                })
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('expenses.list.edit', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-green edit-row">';
                    $html .= '<span class="material-icons">create</span>';
                    $html .= '</a>';

                    $html .= '<a href="'.route('expenses.list.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
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

        $record = new Expense();

        $record->name = $request->name;
        $record->note = $request->note;
        $record->expense_category_id = $request->expense_category_id;

        $record->expense_date = $request->expense_date;
        $record->amount = $request->amount;
        $record->payment_mode = $request->payment_mode;
        $record->payment_reference = $request->payment_reference;

        Auth::user()->expenses()->save($record);

        $message = __('Your record has been added successfully!');

        return response()->json($message,200);
    }

    public function edit(Request $request, $id)
    {
        if($record = Auth::user()->expenses()->find($id)){
            $record->name = $request->name;
            $record->note = $request->note;
            $record->expense_category_id = $request->expense_category_id;

            $record->expense_date = $request->expense_date;
            $record->amount = $request->amount;
            $record->payment_mode = $request->payment_mode;
            $record->payment_reference = $request->payment_reference;

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
            if($record = Auth::user()->expense_categories()->find($id)){
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
            'note'              => 'required|string',
            'amount'            => 'required|numeric',

            'expense_category_id'   => [
                'required', 'integer',
                'exists:expense_categories,id',
                function($attribute, $value, $fail){
                    if(!Auth::user()->expense_categories()->find($value)){
                        $message = __('Expense category (:id) does not exists.', [
                            'id' => $value
                        ]);

                        return $fail($message);
                    }
                }
            ],

            'payment_mode'      => 'required|string',
            'payment_reference' => 'required|string',
            'expense_date'      => 'required|string|date_format:Y-m-d H:i:s',
        ];
    }
}
