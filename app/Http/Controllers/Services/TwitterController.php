<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\DotenvEditor;

class TwitterController extends Controller
{
    protected $env;

    public function __construct(DotenvEditor $env)
    {
        $this->env = $env;
    }

    // Twitter Service
    public function index()
    {
        $env = $this->env;

        $keys = $env->getKeys([
            'TW_KEY',
            'TW_SECRET'
        ]);

        return view('services.twitter')
            ->with(compact('api', 'keys'));
    }

    public function update(Request $request)
    {
        $env = $this->env;

        $this->validate($request, [
            'tw_key' => 'required',
            'tw_secret' => 'required'
        ]);

        $env->setKey('TW_KEY', $request->tw_key);
        $env->setKey('TW_SECRET', $request->tw_secret);

        $env->save();

        return redirect()->back()
            ->with('success', __('Twitter credentials was saved successfully!'));
    }
}
