<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    public function index()
    {
        return view('invoice.template.index');
    }
}
