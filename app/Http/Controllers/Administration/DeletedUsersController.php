<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeletedUsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:alter users')
            ->only(['update', 'destroy']);
    }

    /**
     * Get Soft Deleted User.
     *
     * @param int $id
     *
     * @return User|bool
     */
    public static function getDeletedUser($id)
    {
        if($user = User::onlyTrashed()->find($id)){
            return $user;
        }else{
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administration.deleted-users.index');
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $users = User::onlyTrashed()->get();

            return DataTables::of($users)
                ->editColumn('email', function($data){
                    return '<a href="mailto:'.$data->email.'" title="Email: '.$data->email.'">'.$data->email.'</a>';
                })
                ->addColumn('roles', function($data){
                    $html = '';

                    foreach ($data->getRoleNames() as $role) {
                        if ($role == 'user') {
                            $html .= '<span class="label label-primary" style="margin-right: 4px">'.ucwords($role).'</span> ';
                        }elseif ($role == 'admin') {
                            $html .= '<span class="label label-warning" style="margin-right: 4px">'.ucwords($role).'</span> ';
                        }else{
                            $html .= '<span class="label label-default">'.ucwords($role).'</span>';
                        }
                    }

                    return $html;
                })
                ->editColumn('deleted_at', function ($data){
                    $deleted_at = new Carbon($data->deleted_at);
                    return $deleted_at->diffForHumans();
                })
                ->addColumn('action', function($data){
                    $html = '';

                    if(Auth::user()->hasAnyPermission('read users', 'alter users')){
                        $html .= '<a href="'.route('administration.deleted-users.show', ['id' => $data->id]).'" class="col-blue">';
                        $html .= '<span class="material-icons">visibility</span>';
                        $html .= '</a>';
                    }

                    if(Auth::user()->hasAnyPermission('alter users')){
                        $html .= '<a href="'.route('administration.deleted-users.update', ['id' => $data->id]).'" class="refresh-row col-green"  data-id="'.$data->id.'">';
                        $html .= '<span class="material-icons">refresh</span>';
                        $html .= '</a>';

                        $html .= '<a href="'.route('administration.deleted-users.destroy', ['id' => $data->id]).'" class="delete-row col-red" data-id="'.$data->id.'">';
                        $html .= '<span class="material-icons">clear</span>';
                        $html .= '</a>';
                    }

                    return $html;
                })
                ->removeColumn('password', 'remember_token', 'verified', 'verification_token', 'token', 'signup_ip_address', 'confirmation_ip_address', 'social_signup_ip_address', 'admin_signup_ip_address', 'updated_ip_address', 'created_at', 'updated_at')
                ->rawColumns(['email','roles','action'])
                ->make(true);
        }else{
            return abort(404);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($user = self::getDeletedUser($id)){
            return view('administration.deleted-users.show', compact('user'));
        }else{
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->ajax()){
            if($user = self::getDeletedUser($id)){
                $user->restore();

                return response()->json(__('User was successfully restored'));
            }else{
                return response()->json(__('The user could not be found!'),404);
            }
        }else{
            return abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->ajax()) {
            if ($user = self::getDeletedUser($id)) {
                $user->forceDelete();

                return response()->json(__('User record was completely removed!'));
            } else {
                return response()->json(__('The user record could not be found!'));

            }
        }else{
            return abort(404);
        }
    }
}
