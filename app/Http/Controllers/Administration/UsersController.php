<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Gerardojbaez\Laraplans\Models\Plan;
use Gerardojbaez\Laraplans\Models\PlanSubscription;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:alter users')
            ->only(['create', 'edit', 'update', 'destroy', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administration.users.index');
    }

    public function data(Request $request)
    {
        if($request->ajax()){
            $users = User::all();

            return DataTables::of($users)
                ->editColumn('email', function($data){
                    return '<a href="mailto:'.$data->email.'" title="Email: '.$data->email.'">'.$data->email.'</a>';
                })
                ->addColumn('balance', function($data){
                    return money($data->currentPoints());
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
                ->editColumn('created_at', function ($data){
                    $created_at = new Carbon($data->created_at);
                    return $created_at->toDayDateTimeString();
                })
                ->editColumn('updated_at', function ($data){
                    $updated_at = new Carbon($data->updated_at);
                    return $updated_at->toDayDateTimeString();
                })
                ->addColumn('action', function($data){
                    $html = '';

                    if(Auth::user()->hasAnyPermission('read users', 'alter users')){
                        $html .= '<a href="'.route('administration.users.show', ['id' => $data->id]).'" class="col-blue">';
                        $html .= '<span class="material-icons">visibility</span>';
                        $html .= '</a>';
                    }

                    if(Auth::user()->hasAnyPermission('alter users')){
                        $html .= '<a href="'.route('administration.users.edit', ['id' => $data->id]).'" class="col-green">';
                        $html .= '<span class="material-icons">mode_edit</span>';
                        $html .= '</a>';

                        $html .= '<a href="'.route('administration.users.destroy', ['id' => $data->id]).'" class="delete-row col-red" data-id="'.$data->id.'">';
                        $html .= '<span class="material-icons">clear</span>';
                        $html .= '</a>';
                    }

                    return $html;
                })
                ->removeColumn('password', 'remember_token', 'verified', 'token', 'signup_ip_address', 'confirmation_ip_address', 'social_signup_ip_address', 'admin_signup_ip_address', 'updated_ip_address', 'deleted_ip_address')
                ->rawColumns(['email','roles','action'])
                ->make(true);
        }else{
            return abort(404);
        }
    }

    public function create()
    {
        if($roles = Role::all()){
            $plans = Plan::all();

            return view('administration.users.create')
                ->with(compact('roles', 'plans'));
        }else{
            $message = __('There are none available roles.');

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name'                  => 'required|max:20|unique:users',
                'first_name'            => 'required|max:20',
                'last_name'             => 'required|max:20',
                'email'                 => 'required|email|max:255|unique:users',
                'password'              => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|same:password',
                'plan'                  => 'nullable|numeric',
                'role'                  => 'required',
            ]
        );

        if($request->has('plan') && $request->plan != 0){
            if($plan = Plan::find($request->plan)){
                $this->validate($request, [
                    'quantity' => 'required|numeric|integer|min:'.$plan->min_quantity
                ]);

                if($plan->max_quantity){
                    $this->validate($request, [
                        'quantity' => 'max:'.$plan->max_quantity
                    ]);
                }

                $quantity = $request->quantity;
            }else{
                return redirect()->back()
                    ->with('error', __('The selected plan was not found!'))
                    ->withInput($request->except(['password', 'password_confirmation']));
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $profile = new Profile();

        $user = User::create([
            'name'             => $request->input('name'),
            'first_name'       => $request->input('first_name'),
            'last_name'        => $request->input('last_name'),
            'email'            => $request->input('email'),
            'password'         => bcrypt($request->input('password')),
            'token'            => str_random(64),
            'admin_signup_ip_address' => request()->ip(),
            'verified'         => true,
        ]);

        $user->profile()->save($profile);
        $user->syncRoles($request->input('role'));
        $user->save();

        if($request->has('plan') && $request->plan != 0 && $plan){
            // Subscribe user to the plan...
            $subscription = $user->newSubscription('main', $plan)->create();
            $this->setQuantity($quantity, $subscription, $plan);
        }

        return redirect()->route('administration.users.index')
            ->with('success', __('Successfully created user!'));
    }

    /**
     * Set subscription quantity.
     *
     * @param  int $quantity
     * @param  PlanSubscription $subscription
     * @param  Plan $plan
     * @return PlanSubscription
     */
    public function setQuantity($quantity, $subscription, $plan)
    {
        if($quantity > 1){
            switch($plan->interval){
                case 'day':
                    $method = 'addDays';
                    break;
                case 'week':
                    $method = 'addWeeks';
                    break;
                case 'month':
                    $method = 'addMonths';
                    break;
                case 'year':
                    $method = 'addYears';
                    break;
                default:
                    $method = 'addMonths';
                    break;
            }
            $ends_at = new Carbon($subscription->ends_at);
            $ends_at = $ends_at->$method(($plan->interval_count * $quantity) - 1);

            $subscription->ends_at = $ends_at;
            $subscription->save();
        }

        return $subscription;
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
        if($user = User::find($id)){
            $features = config('laraplans.features');

            return view('administration.users.show')
                ->with(compact('user', 'features'));
        }else{
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(($user = User::find($id)) && ($roles = Role::all())){
            $current_role = $user->roles->first();

            return view('administration.users.edit')
                ->with(compact('user', 'roles', 'current_role'));
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
        if($user = User::find($id)){
            $rules = [
                'role' => 'required'
            ];

            if ($request->input('password') != null) {
                $rules = array_merge([
                    'password' => 'present|confirmed|min:6',
                ], $rules);
            }

            if(($request->input('name') != $user->name)){
                $rules = array_merge([
                    'name' => 'required|string|max:20|unique:users'
                ], $rules);
            }

            if(($request->input('email') != $user->email)){
                $rules = array_merge([
                    'email'  => 'email|max:255|unique:users',
                ], $rules);
            }

            $this->validate($request, $rules);

            if (($request->input('name') != $user->name)) {
                $user->name = $request->input('name');
            }

            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');

            if (($request->input('email') != $user->email)) {
                $user->email = $request->input('email');
            }

            if ($request->input('password') != null) {
                $user->password = bcrypt($request->input('password'));
            }

            $user->syncRoles($request->input('role'));
            $user->updated_ip_address = request()->ip();
            $user->save();

            return back()->with('success', __('Successfully updated user!'));
        }else{
            return abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if($request->ajax()) {
            if ($user = User::find($id)) {
                if ($user->id != Auth::user()->id) {
                    $user->deleted_ip_address = request()->ip();

                    $user->save();

                    try {
                        $user->delete();

                        return response()->json(__('User was successfully deleted!'));
                    } catch (\Exception $e) {
                        return response(__('Something went wrong! Message:') . ' ' . $e->getMessage(), 400);
                    }
                }

                return response()->json(__('You cannot delete yourself!'), 403);
            } else {
                return response()->json(__('User record could not be found!'), 404);
            }
        } else {
            return abort(404);
        }
    }
}
