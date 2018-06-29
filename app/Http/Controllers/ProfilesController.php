<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Notifications\SendGoodbyeEmail;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Image;
use jeremykenedy\Uuid\Uuid;
use Validator;
use View;

class ProfilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Fetch user
     * (You can extract this to repository method).
     *
     * @param $username
     *
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return User::with('profile')->wherename($username)->firstOrFail();
    }

    /**
     * Display the specified resource.
     *
     * @param string $username
     *
     * @return Response
     */
    public function show($username)
    {
        try {
            if($user = $this->getUserByUsername($username)){
                $features = config('laraplans.features');

                return view('profiles.show')
                    ->with(compact('features', 'user'));
            }else{
                return abort(404);
            }
        } catch (ModelNotFoundException $exception) {
            return abort(404);
        }
    }

    /**
     * /profiles/username/edit.
     *
     * @param $username
     *
     * @return mixed
     */
    public function edit($username)
    {
        try {
            $user = $this->getUserByUsername($username);
            if($user && (Auth::user()->id == $user->id)){
                if(Auth::user()->id != $user->id){
                    return abort(404);
                }

                return view('profiles.edit', compact('user'));
            }else{
                return abort(404);
            }
        } catch (ModelNotFoundException $exception) {
            return abort(404);
        }
    }

    /**
     * Update a user's profile.
     *
     * @param $username
     *
     * @return mixed
     */
    public function update($username, Request $request)
    {
        try {
            $user = $this->getUserByUsername($username);

            if($user && (Auth::user()->id == $user->id)){
                // Get inputs...
                $input = $request->only('location', 'bio', 'avatar_status');

                // Validate them..
                $validator = Validator::make($request->all(), [
                    'bio'              => 'max:500',
                    'avatar_status'    => 'required|boolean',
                ]);

                if($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                // Now, we update the profile..
                if($user->profile == null) {
                    $profile = new Profile();
                    $profile->fill($input);
                    $user->profile()->save($profile);
                }else{
                    $user->profile->fill($input)->save();
                }

                $user->updated_ip_address = $request->ip();
                $user->save();

                return redirect('profile/'.$user->name.'/edit')->with('success', __('Your profile has been successfully updated'));
            }else{
                return abort(404);
            }
        } catch (ModelNotFoundException $exception) {
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
    public function updateAccount(Request $request, $id)
    {
        if((Auth::user()->id == $id) && $user = User::find($id)){
            $rules = [
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
            ];

            if($request->name != $user->name){
                $rules = array_merge([
                    'name' => 'unique:users'
                ], $rules);
            }


            if ($request->email != $user->email) {
                $rules = array_merge([
                    'email' => 'unique:users',
                ], $rules);
            }

            $this->validate($request, $rules);


            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;

            if($user->can('alter users')) {
                $user->name = $request->name;
                $user->email = $request->email;
            }

            $user->updated_ip_address = $request->ip();

            $user->save();

            return redirect()->route('profile.edit', ['username' => $user->name])
                ->with('success', __('Your account has been successfully updated'));
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
    public function updatePassword(Request $request, $id)
    {
        if((Auth::user()->id == $id) && $user = User::find($id)){
            $this->validate($request, [
                'old_password' => 'required',
                'password' => 'required|min:5|max:20|confirmed'
            ]);

            if ($request->input('password') != null) {
                if(!Hash::check($request->get('old_password'), $user->password)){
                    return redirect()->back()->with('error', __('The old password was Incorrect!'));
                }

                $user->password = bcrypt($request->input('password'));
            }

            $user->updated_ip_address = $request->ip();
            $user->save();

            return redirect()->back()->with('success', __('Your password has been successfully updated!'));
        }else{
            return abort(404);
        }
    }

    /**
     * Upload and Update user avatar.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file') && $user = Auth::user()) {
            try{
                $avatar = $request->file('file');

                $name = 'avatar.' . $avatar->getClientOriginalExtension();
                $directory = $this->getAvatarPath($user->id);
                $link = $this->getAvatarUrl($user->id, $name);

                // Make the user a folder and set permissions
                File::makeDirectory($directory, 0755, true, true);

                // Save the file to the server
                Image::make($avatar)->resize(300, 300)->save($directory . '/' . $name);

                // Save the public image path
                $user->profile->avatar = $link;
                $user->profile->save();

                $message =__('Your photo has been uploaded successfully! Click the Save button below to proceed.');

                return response()->json($message, 200);
            }catch(\Exception $e){
                return response()->json($e->getMessage(), 404);
            }
        } else {
            $message = __('Something went wrong! If this persists, kindly contact the administrator.');

            return response()->json($message, 404);
        }
    }

    /**
     * Get the path for user's avatar
     *
     * @param $id
     * @return string
     */
    public function getAvatarPath($id)
    {
        return storage_path('users/id/'.$id.'/uploads/images/avatar/');
    }

    /**
     * Get the url for user's avatar
     *
     * @param $id
     * @param $image
     * @return string
     */
    public function getAvatarUrl($id, $image)
    {
        return route('image.profile.avatar', compact('id', 'image'), false);
    }

    /**
     * Show user avatar.
     *
     * @param $id
     * @param $image
     *
     * @return string
     */
    public function userProfileAvatar($id, $image)
    {
        return Image::make($this->getAvatarPath($id).'/'.$image)->response();
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function deleteAccount(Request $request, $id)
    {
        if(($user = User::find($id)) && (Auth::user()->id == $id)){
            try{
                $this->validate($request, [
                    'reason' => 'required|string|min:5|max:500'
                ]);

                $user->deleted_ip_address = $request->ip();
                $user->deleted_reason = $request->get('reason');
                $user->save();

                // Send Goodbye email notification
                $this->sendGoodbyeEmail($user, $user->token);

                // Soft Delete User
                $user->delete();

                // Clear out the session
                $request->session()->flush();
                $request->session()->regenerate();

                return redirect()->route('login')->with('success', __('Your account has been deleted!'));

            }catch(\Exception $e){
                return redirect()->back()->with('error', __('Something went wrong :( Message:'. $e->getMessage()));
            }
        }else{
            return abort(404);
        }
    }

    /**
     * Send GoodBye Email Function via Notify.
     *
     * @param array  $user
     * @param string $token
     *
     * @return void
     */
    public static function sendGoodbyeEmail(User $user, $token)
    {
        $user->notify(new SendGoodbyeEmail($user));
    }
}
