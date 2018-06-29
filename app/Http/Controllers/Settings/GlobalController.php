<?php

namespace App\Http\Controllers\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GlobalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.global');
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        if($setting){
            $this->validate($request, [
                'site_name'         => 'required',
                'site_name_abbr'    => 'required|max:5',
                'site_title'        => 'required',
                'logo'              => 'nullable|mimes:png|dimensions:width=200,height=47|max:50',
                'site_desc'         => 'required',
                'facebook_url'      => 'nullable|url',
                'twitter_url'       => 'nullable|url',
                'google_plus_url'   => 'nullable|url'
            ]);

            $setting->site_name = $request->site_name;
            $setting->site_name_abbr = $request->site_name_abbr;
            $setting->site_title = $request->site_title;

            if($request->hasFile('logo')){
                $file = $request->file('logo');
                $directory = 'images/uploads/';
                $name = 'logo.png';
                $file->move($directory, $name);
                $setting->logo = $directory . $name;
            }

            $setting->site_desc = $request->site_desc;
            $setting->facebook_url = $request->facebook_url;
            $setting->twitter_url = $request->twitter_url;
            $setting->instagram_url = $request->instagram_url;
            $setting->google_plus_url = $request->google_plus_url;
            $setting->save();

            $message = __('Global settings has been updated successfully!');

            return redirect()->back()->with('success', $message);
        }else{
            return abort(403);
        }
    }
}
