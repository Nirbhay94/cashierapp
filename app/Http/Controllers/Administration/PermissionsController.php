<?php

namespace App\Http\Controllers\Administration;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administration.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('administration.permissions.create')
            ->with(compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $default_permissions = Permission::all()->pluck('name');

        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s]+$/u|unique:roles',
            'permissions' => 'required|array',
            'permissions.*' => Rule::in($default_permissions->toArray())
        ]);

        $permissions = $this->prepareRequirements($request->permissions);

        $role = new Role();
        $role->name = strtolower($request->name);
        $role->save();
        $role->syncPermissions($permissions);


        $message = __('Your settings has been saved!');

        return redirect()->route('administration.permissions.index')
            ->with('success', $message);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($role = Role::find($id)){
            $permissions = Permission::all();

            return view('administration.permissions.edit')
                ->with(compact('role', 'permissions'));
        }else{
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($role = Role::find($id)){
            $default_permissions = Permission::all()->pluck('name');

            $this->validate($request, [
                'name' => 'required|regex:/^[\pL\s]+$/u|unique:roles',
                'permissions' => 'required|array',
                'permissions.*' => Rule::in($default_permissions->toArray())
            ]);

            $permissions = $this->prepareRequirements($request->permissions);

            if(!in_array($role->name, ['user', 'admin'])){
                $role->name = strtolower($request->name);
            }

            $role->save();
            $role->syncPermissions($permissions);


            $message = __('Your settings has been saved!');

            return redirect()->route('administration.permissions.index')
                ->with('success', $message);
        }else{
            return abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            if($role = Role::find($id)){
                if(!in_array($role->name, ['user', 'admin'])){
                    try{
                        User::role($role->name)->get()->each(function ($user) {
                            $user->syncRoles(['user']);
                        });

                        $role->delete();

                        return response()->json(__('Role has been successfully deleted!'), 200);
                    }catch(\Exception $e){
                        return response()->json($e->getMessage(), 400);
                    }
                }else{
                    return response()->json(__('You are not allowed to delete this role!'), 403);
                }
            }else{
                return response()->json(__('The role could not be found!'), 404);
            }
        }else{
            return abort(404);
        }
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $roles = Role::all();

            return DataTables::of($roles)
                ->editColumn('name', function($data){
                    $html = '';

                    switch($data->name){
                        case 'user':
                            $html .= '<span class="label label-primary" style="margin-right: 4px">'.ucwords($data->name).'</span> ';
                            break;

                        case 'admin':
                            $html .= '<span class="label label-warning" style="margin-right: 4px">'.ucwords($data->name).'</span> ';
                            break;

                        default:
                            $html .= '<span class="label label-default" style="margin-right: 4px">'.ucwords($data->name).'</span> ';
                            break;
                    }

                    return $html;
                })
                ->addColumn('total', function ($data){
                    return number_format(User::role($data->name)->count());
                })
                ->addColumn('level', function($data){
                    $html = '';

                    for($i = 1; $i <= count($data->permissions); $i++) {
                        $html .= '<span class="material-icons col-gold">pets</span>';
                    }

                    return $html;
                })
                ->addColumn('action', function($data){
                    $html = '';

                    $html .= '<a href="'.route('administration.permissions.edit', ['id' => $data->id]).'" class="col-green">';
                    $html .= '<span class="material-icons">mode_edit</span>';
                    $html .= '</a>';

                    if(!in_array($data->name, ['admin', 'user'])){
                        $html .= '<a href="'.route('administration.permissions.destroy', ['id' => $data->id]).'" class="delete-row col-red" data-id="'.$data->id.'">';
                        $html .= '<span class="material-icons">clear</span>';
                        $html .= '</a>';
                    }

                    return $html;
                })
                ->removeColumn('guard_name')
                ->rawColumns(['name', 'level', 'action'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function prepareRequirements($permissions)
    {
        $required_with = [
            'alter users' => ['read users']
        ];

        foreach($required_with as $key => $data){
            if(in_array($key, $permissions)){
                foreach($data as $value){
                    if(!in_array($value, $permissions)){
                        array_push($permissions, $value);
                    }
                }
            }
        }

        return $permissions;
    }
}
