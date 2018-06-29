<?php

namespace App\Http\Controllers\Configuration;

use App\Models\CustomerInvoiceConfiguration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        return view('configuration.invoice.index', [
            'config' => Auth::user()->invoice_configuration()->first()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'currency_locale'       => ['required', Rule::in(array_keys(get_currencies()))],

            'business_name'         => 'nullable|max:150',
            'business_id'           => 'nullable|max:50',
            'business_logo'         => 'nullable|mimes:png|dimensions:width=524,height=140|max:60',
            'business_phone'        => 'nullable|max:50',
            'business_location'     => 'nullable|max:150',
            'business_zip'          => 'nullable|max:20',
            'business_city'         => 'nullable|max:50',
            'business_country'      => 'nullable|max:100',
            'business_legal_terms'  => 'nullable|max:250',
        ]);

        $configuration = Auth::user()->invoice_configuration()->first();

        $configuration = $configuration ?: new CustomerInvoiceConfiguration();

        $configuration->business_name = $request->business_name;
        $configuration->business_id = $request->business_id;

        if($request->hasFile('business_logo')){
            $file = $request->file('business_logo');

            $name = 'logo.png';
            $directory = $this->getLogoPath(Auth::user()->id);

            File::makeDirectory($directory, 0755, true, true);

            Image::make($file)->save($directory . '/' . $name);

            $configuration->business_logo = $this->getLogoUrl(Auth::user()->id, $name);
        }

        $configuration->business_phone = $request->business_phone;
        $configuration->business_location = $request->business_location;
        $configuration->business_zip = $request->business_zip;
        $configuration->business_city = $request->business_city;
        $configuration->business_country = $request->business_country;
        $configuration->business_legal_terms = $request->business_legal_terms;
        $configuration->currency_locale = $request->currency_locale;

        Auth::user()->invoice_configuration()->save($configuration);

        $message = __('Your settings has been saved!');

        return redirect()->back()->with('success', $message);
    }

    public function invoiceLogo($id, $image)
    {
        return Image::make($this->getLogoPath($id). '/' .$image)->response();
    }

    /**
     * Get the path for user's avatar
     *
     * @param $id
     * @return string
     */
    public function getLogoPath($id)
    {
        return storage_path('users/id/'.$id.'/uploads/images/invoice/logo/');
    }

    /**
     * Get the url for invoice logo
     *
     * @param $id
     * @param $image
     * @return string
     */
    public function getLogoUrl($id, $image)
    {
        return route('image.profile.invoice.logo', compact('id', 'image'), false);
    }
}
