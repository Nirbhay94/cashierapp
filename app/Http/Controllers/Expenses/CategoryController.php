<?php

namespace App\Http\Controllers\Expenses;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('expenses.category.index');
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $records = Auth::user()->expense_categories()->get();

            return DataTables::of($records)
                ->addColumn('total', function($data){
                    return money($data->expenses()->sum('amount'), Auth::user());
                })
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('expenses.categories.edit', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-green edit-row">';
                    $html .= '<span class="material-icons">create</span>';
                    $html .= '</a>';

                    $html .= '<a href="'.route('expenses.categories.destroy', ['id' => $data->id]).'" data-id="'.$data->id.'" class="col-red delete-row">';
                    $html .= '<span class="material-icons">clear</span>';
                    $html .= '</a>';

                    return $html;
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

        $record = new ExpenseCategory();
        $record->name = $request->name;
        $record->description = $request->description;

        Auth::user()->expense_categories()->save($record);

        $message = __('Your record has been added successfully!');

        return response()->json($message,200);
    }

    public function edit(Request $request, $id)
    {
        if($record = Auth::user()->expense_categories()->find($id)){
            $this->validate($request, $this->validationRules());

            $record->name = $request->name;
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
            'description'       => 'required|string'
        ];
    }
}
