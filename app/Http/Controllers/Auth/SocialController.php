<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Social;
use App\Models\User;
use App\Notifications\SendWelcomeEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function getSocialRedirect($provider)
    {
        if (Config::has('services.'.$provider)) {
            return Socialite::driver($provider)->redirect();
        }

        return abort(404);
    }

    public function getSocialHandle(Request $request, $provider)
    {
        if ($request->denied) {
            $message = __('You did not share your profile data with our social app.');

            return redirect()->route('login')->with('status', $message);
        }

        if($user_object = Socialite::driver($provider)->user()){
            if($email = $user_object->getEmail()){
                $user = User::where('email', '=', $user_object->getEmail())->first();

                if(!$user) {
                    $social_data = Social::where('social_id', '=', $user_object->id)
                        ->where('provider', '=', $provider)
                        ->first();

                    if(!$social_data) {
                        $social_data = new Social();
                        $profile = new Profile();

                        $full_name = explode(' ', $user_object->getName());

                        if($user_object->getNickname()){
                            $username = $user_object->getNickname();
                        }else{
                            $username = strtolower(implode('.', $full_name));
                        }

                        $password = str_random(6);

                        $user = User::create([
                            'name'                 => $username,
                            'first_name'           => isset($full_name[0])? $full_name[0]: '',
                            'last_name'            => isset($full_name[1])? $full_name[1]: '',
                            'email'                => $email,
                            'password'             => bcrypt($password),
                            'token'                => str_random(64),
                            'verified'             => true,
                            'social_signup_ip_address' => request()->ip(),
                        ]);

                        $social_data->social_id = $user_object->id;
                        $social_data->provider = $provider;

                        $user->social()->save($social_data);
                        $user->syncRoles('user');
                        $user->verified = true;

                        $user->profile()->save($profile);
                        $user->save();

                        $user->notify((new SendWelcomeEmail($user, $password)));
                        $message = __('You have been registered successfully, Please check your email for your password.');
                    } else {
                        $user = $social_data->user;

                        $message = __('Welcome back :name! Your social login was successful.', ['name' => $user->name]);
                    }

                    auth()->login($user, true);

                    return redirect()->route('home')->with('success', $message);
                }

                auth()->login($user, true);

                return redirect()->route('home');
            }else{
                $message = __('The app was not granted the appropriate permission to fetch your email or your profile does not contain an email address.');

                return redirect()->route('login')->with('error', $message);
            }
        }else{
            $message = __('Oops! We were unable to fetch your details.');

            return redirect()->route('login')->with('error', $message);
        }
    }
}
