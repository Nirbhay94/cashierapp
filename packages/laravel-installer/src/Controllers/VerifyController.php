<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

//use App\Http\Middleware\LicenseHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifyController extends Controller
{
    /**
     * Display the verify overview page.
     *
     * @return \Illuminate\View\View
     */
    public function overview()
    {
        return view('vendor.installer.verify.overview');
    }

    /**
     * Display the verify overview page.
     *
     * @params Request
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification' => 'required|min:10',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        $verification_code = $request->get('verification');
        Cache::forever('verification_code', $verification_code);
        /*try{
            $license = new LicenseHelper();
            $purchase_details = $license->register($verification_code);

            if(!is_object($purchase_details)){
                if(is_array($purchase_details) && isset($purchase_details['error'])){
                    $validator->getMessageBag()->add('verification', $purchase_details['message']);

                    return redirect()->back()->withErrors($validator);
                }else{
                    $validator->getMessageBag()->add('verification', 'Opps! Something unexpected went wrong. Kindly contact the author');

                    return redirect()->back()->withErrors($validator);
                }
            }else{
                Cache::forever('verification_code', $verification_code);
            }
        }catch(\Exception $e){
            $validator->getMessageBag()->add('verification', 'An error occurred while attempting to verify purchase! Message:'.' '.$e->getMessage());

            return redirect()->back()->withErrors($validator);
        }*/

        return redirect()->to('/');
    }

}
