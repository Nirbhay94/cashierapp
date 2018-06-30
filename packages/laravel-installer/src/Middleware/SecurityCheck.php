<?php

namespace RachidLaasri\LaravelInstaller\Middleware;

//use App\Http\Middleware\LicenseHelper;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SecurityCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make([],[]);
        if(Session::has('verification_code')) {
            $verification_code = Session::get('verification_code');
            Session::put('verification_code', $verification_code);
        }
        /*if(Session::has('verification_code')){
            $verification_code = Session::get('verification_code');
            try{
                $license = new LicenseHelper();
                $purchase_details = $license->details($verification_code);

                if(!is_object($purchase_details)){
                    if(is_array($purchase_details) && isset($purchase_details['error'])){
                        $validator->getMessageBag()->add('verification', $purchase_details['message']);

                        return redirect()->route('LaravelInstaller::welcome')->withErrors($validator);
                    }else{
                        $validator->getMessageBag()->add('verification', 'Opps! Something unexpected went wrong. Kindly contact the author');

                        return redirect()->route('LaravelInstaller::welcome')->withErrors($validator);
                    }
                }else{
                    Session::put('verification_code', $verification_code);
                }
            }catch(\Exception $e){
                $validator->getMessageBag()->add('verification', 'An error occurred while attempting to verify purchase! Message:'.' '.$e->getMessage());

                return redirect()->route('LaravelInstaller::welcome')->withErrors($validator);
            }
        }else{
            $validator->getMessageBag()->add('verification', 'Oops! Something unexpected went wrong. Please enter your verification.');
            return redirect()->route('LaravelInstaller::welcome')->withErrors($validator);
        }*/

        return $next($request);
    }
}
