<?php

namespace App\Http\Controllers\Configuration;

use App\Models\PosConfiguration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        return view('configuration.pos.index', [
            'config' => Auth::user()->pos_configuration()->first()
        ]);
    }

    public function store(Request $request)
    {
        $configuration = Auth::user()->pos_configuration()->first();

        $configuration = $configuration ?: new PosConfiguration();

        $configuration->header = $request->header;

        $configuration->footer = $request->footer;

        Auth::user()->pos_configuration()->save($configuration);

        $message = __('Your settings has been saved!');

        return redirect()->back()->with('success', $message);
    }
}
