<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ThrottlesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Jrean\UserVerification\UserVerification;

class AjaxController extends Controller
{
    use ThrottlesEmails;

    public function resendVerificationEmail()
    {
       if(($user = Auth::user()) && !$user->verified){
           $verification = app(UserVerification::class);

           try{
               if($this->hasTooManyEmailAttempts($user)){
                   return response()->json(__('Too many email attempts. Please try again later'), 400);
               }

               $verification->send(Auth::user(), trans('auth.verification_subject'));

               $this->incrementEmailAttempts($user);

               return response()->json(__('Sent successfully!'), 200);
           }catch(\Exception $e){
               return response()->json(__('We are unable to send email. Please try again later'), 400);
           }

       }else{
           return response()->json(__('Your account has been verified. Refresh to continue'), 400);
       }
    }

    public function updateProfile($username, Request $request)
    {
        if($request->ajax()){
            if($user = $this->getUserByUsername($username)){
                if(Auth::user()->id == $user->id){
                    $this->validate($request, [
                        'name' => Rule::in(['auto_renewal']),
                        'value' => Rule::in(['yes', 'no']),
                    ]);

                    $column = $request->name;
                    $value = $request->value;

                    $user->fill([$column => $value]);

                    $user->save();

                    $message = __('Your profile has been updated successfully!');

                    return response()->json($message, 200);
                }else{
                    $message = __('You do not have the permission!');

                    return response()->json($message, 403);
                }
            }else{
                $message = __('Your profile could not be found!');

                return response()->json($message, 404);
            }
        }else{
            return abort(404);
        }
    }

    /**
    * Fetch user
    * (You can extract this to repository method).
    *
    * @param $username
    *
    * @return mixed
    */
    protected function getUserByUsername($username)
    {
        return User::with('profile')->wherename($username)->firstOrFail();
    }
}
