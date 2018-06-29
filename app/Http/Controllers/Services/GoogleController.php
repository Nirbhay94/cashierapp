<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\DotenvEditor;

class GoogleController extends Controller
{
    protected $env;

    public function __construct(DotenvEditor $env)
    {
        $this->env = $env;
    }

    public function index()
    {
        return view('services.google', [
            'keys' => $this->env->getKeys([
                'GOOGLEMAPS_API_KEY',
                'ENABLE_GOOGLEMAPS',
                'RECAPTCHA_KEY',
                'RECAPTCHA_SECRET',
                'ENABLE_RECAPTCHA'
            ])
        ]);
    }

    public function updateGoogleMaps(Request $request)
    {
        $this->validate($request, [
            'google_maps_key' => 'required_if:enable_googlemaps,yes'
        ]);

        $this->env->setKey('GOOGLEMAPS_API_KEY', $request->google_maps_key);

        if($request->enable_googlemaps == 'yes'){
            $this->env->setKey('ENABLE_GOOGLEMAPS', 'true');
        }else{
            $this->env->setKey('ENABLE_GOOGLEMAPS', 'false');
        }

        $this->env->save();

        $message = __('Google credentials was saved successfully!');

        return redirect()->back()->with('success', $message);
    }

    public function updateGoogleRecaptcha(Request $request)
    {
        $this->validate($request, [
            'recaptcha_key' => 'required_if:enable_recaptcha,yes',
            'recaptcha_secret' => 'required_if:enable_recaptcha,yes'
        ]);

        $this->env->setKey('RECAPTCHA_KEY', $request->recaptcha_key);
        $this->env->setKey('RECAPTCHA_SECRET', $request->recaptcha_secret);

        if($request->enable_recaptcha == 'yes'){
            $this->env->setKey('ENABLE_RECAPTCHA', 'true');
        }else{
            $this->env->setKey('ENABLE_RECAPTCHA', 'false');
        }

        $this->env->save();

        $message = __('Google credentials was saved successfully!');

        return redirect()->back()->with('success', $message);
    }
}
