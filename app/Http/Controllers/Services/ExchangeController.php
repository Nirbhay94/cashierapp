<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\DotenvEditor;

class ExchangeController extends Controller
{
    protected $env;

    public function __construct(DotenvEditor $env)
    {
        $this->env = $env;
    }

    public function index()
    {
        return view('services.exchange', [
            'keys' => $this->env->getKeys(['OER_KEY'])
        ]);
    }

    public function update(Request $request)
    {
        $this->env->setKey('OER_KEY', $request->oer_key);

        $this->env->save();

        $message =  __('Exchange credentials was saved successfully!');

        return redirect()->back()->with('success', $message);
    }
}
