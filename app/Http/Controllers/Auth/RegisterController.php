<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\UserVerification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;
    use VerifiesUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Get the redirect path for a successful verification token verification.
     *
     * @var string
     */
    protected $redirectAfterVerification;

    /**
     * Get the redirect path if the user is already verified.
     *
     * @var string
     */
    protected $redirectIfVerified;

    /**
     * Get the redirect path for a failing token verification.
     *
     * @var string
     */
    protected $redirectIfVerificationFails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => ['getVerification', 'getVerificationError', 'logout'],
        ]);

        $this->redirectTo = (Auth::check())? route('home'): route('login');
        $this->redirectAfterVerification = (Auth::check())? route('home'): route('login');
        $this->redirectIfVerified = (Auth::check())? route('home'): route('login');
        $this->redirectIfVerificationFails = (Auth::check())? route('home'): route('login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'                  => 'required|max:255|unique:users',
            'first_name'            => '',
            'last_name'             => '',
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|min:6|max:20|confirmed',
            'password_confirmation' => 'required|same:password',
        ];

        if(config('settings.reCaptchaStatus')){
            $rules = array_merge($rules, [
                'g-recaptcha-response' => 'required|captcha'
            ]);
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'              => $data['name'],
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'token'             => str_random(64),
            'signup_ip_address' => request()->ip(),
        ]);

        $user->profile()->save(new Profile());
        $user->syncRoles('user');

        $verification = app(UserVerification::class);

        try{
            $subject = trans('auth.verification_subject');

            $verification->generate($user);
            $verification->send($user, $subject);
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }

        return $user;
    }
}
