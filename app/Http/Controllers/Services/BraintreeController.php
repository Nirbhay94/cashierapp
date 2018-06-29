<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\DotenvEditor;

class BraintreeController extends Controller
{
    /**
     * @var DotenvEditor
     */
    protected $env;

    public function __construct(DotenvEditor $env)
    {
        $this->env = $env;
    }

    public function index()
    {
        return view('services.braintree', [
            'keys' => $this->env->getKeys([
                'BRAINTREE_ENV',
                'BRAINTREE_MERCHANT_ID',
                'BRAINTREE_PUBLIC_KEY',
                'BRAINTREE_PRIVATE_KEY',
            ])
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'braintree_env' => 'required|in:production,sandbox',
            'braintree_merchant_id' => 'required',
            'braintree_public_key' => 'required',
            'braintree_private_key' => 'required',
        ]);

        $this->env->setKey('BRAINTREE_ENV', $request->braintree_env);
        $this->env->setKey('BRAINTREE_MERCHANT_ID', $request->braintree_merchant_id);
        $this->env->setKey('BRAINTREE_PUBLIC_KEY', $request->braintree_public_key);
        $this->env->setKey('BRAINTREE_PRIVATE_KEY', $request->braintree_private_key);

        $this->env->save();

        $message = __('Braintree credentials was saved successfully!');

        return redirect()->back()->with('success', $message);
    }
}
