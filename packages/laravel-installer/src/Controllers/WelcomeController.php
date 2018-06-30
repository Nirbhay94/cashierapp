<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Middleware\LicenseHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Validator;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('vendor.installer.welcome');
    }

    /**
     * Validate Purchase and proceed with installation.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function proceed(Request $request)
    {

        $verification_code = $request->get('verification');
        Session::put('verification_code', $verification_code);
        /*try{
            $license = new LicenseHelper();
            $purchase_details = $license->register($verification_code);

            if(!is_object($purchase_details)){
                if(is_array($purchase_details) && isset($purchase_details['error'])){
                    $validator->getMessageBag()->add('verification', $purchase_details['message']);

                    return redirect()->back()->withErrors($validator);
                }else{
                    $message = __('Opps! Something unexpected went wrong. Kindly contact the author');

                    $validator->getMessageBag()->add('verification', $message);

                    return redirect()->back()->withErrors($validator);
                }
            }else{

            }
        }catch(\Exception $e){
            $validator->getMessageBag()->add('verification', $e->getMessage());

            return redirect()->back()->withErrors($validator);
        }*/

        return redirect()->route('LaravelInstaller::requirements');
    }
}
