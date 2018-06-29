<?php

namespace App\Http\Controllers\Configuration;

use App\Mail\PreviewMail;
use App\Models\EmailConfiguration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function index()
    {
        return view('configuration.email.index', [
            'config' => Auth::user()->email_configuration()->first()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'header'            => 'required_with:header_url|nullable',
            'header_url'        => 'required_with:header|nullable|url',

            'from_address'      => 'nullable|required_with:from_name|email',
            'from_name'         => 'nullable|string',

            'reply_to_address'  => 'nullable|required_with:reply_to_name|email',
            'reply_to_name'     => 'nullable|string',
        ]);

        $configuration = Auth::user()->email_configuration()->first();

        $configuration = $configuration ?: new EmailConfiguration();

        $configuration->header = $request->header;
        $configuration->header_url = $request->header_url;
        $configuration->subcopy = $request->subcopy;
        $configuration->footer = $request->footer;

        $configuration->from_address = $request->from_address;
        $configuration->from_name = $request->from_name;

        $configuration->reply_to_address = $request->reply_to_address;
        $configuration->reply_to_name = $request->reply_to_name;

        Auth::user()->email_configuration()->save($configuration);

        $message = __('Your settings has been saved!');

        return redirect()->back()->with('success', $message);
    }

    public function preview()
    {
        return new PreviewMail(Auth::user());
    }
}
