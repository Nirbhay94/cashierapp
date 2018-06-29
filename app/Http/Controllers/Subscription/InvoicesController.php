<?php

namespace App\Http\Controllers\Subscription;

use App\Models\Invoice;
use App\Models\PaymentSetting;
use ConsoleTVs\Invoices\Classes\Invoice as SubscriptionInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('subscription.invoices');
    }

    public function download($id, SubscriptionInvoice $pdf)
    {
        set_time_limit(120);

        if($invoice = Invoice::find($id)){
            $settings = PaymentSetting::first();
            
            if($invoice->user->id == Auth::user()->id || Auth::user()->hasRole('admin')){
                $file = $this->getFilePath($invoice);

                $path = $this->absolutePath($file);

                if(!File::exists($path)){
                    $items = json_decode($invoice->items, true);

                    foreach($items as $item){
                        $pdf->addItem($item[0], $item[1], $item[2]);
                    }

                    $pdf->notes($invoice->note);
                    $pdf->number($invoice->transaction_id);


                    $pdf->logo(url('images/logo_black.png'));

                    if($settings){
                        if($settings->business_logo){
                            $pdf->logo(url($settings->business_logo));
                        }

                        // Specify business details...
                        $pdf->business([
                            'name'        => $settings->business_name,
                            'id'          => $settings->business_id,
                            'phone'       => $settings->business_phone,
                            'location'    => $settings->business_location,
                            'zip'         => $settings->business_zip,
                            'city'        => $settings->business_city,
                            'country'     => $settings->business_country,
                        ]);

                        // Add footnote
                        if($settings->business_legal_terms){
                            $pdf->footnote($settings->business_legal_terms);
                        }
                    }

                    $pdf->customer([
                        'name' => $invoice->user->first_name.' '.$invoice->user->last_name,
                        'id' => $invoice->user->id,
                        'phone' => $invoice->user->email
                    ]);

                    $pdf->save($file);
                }

                return response()->download($path);
            }else{
                return abort(403);
            }
        }else{
            $message =  __('Opps! The invoice appears to have been deleted.');

            return redirect()->back()->with('error', $message);
        }
    }

    public function data(Request $request)
    {
        if(!Auth::user()->hasRole('admin')){
            $invoices = Invoice::where('user_id', Auth::user()->id)
                ->get();
        }else{
            $invoices = Invoice::all();
        }

        return DataTables::of($invoices)
            ->editColumn('user_id', function($data){
                return '<a href="'.url('profile/'.$data->user->name).'">'.$data->user->name.'</a>';
            })
            ->editColumn('total', function($data){
                return money($data->total);
            })
            ->addColumn('action', function ($data){
                $html = '<a href="'.route('subscription.invoices.download', ['id' => $data->id]).'" class="col-green">';
                $html .= '<i class="material-icons">archive</i>';
                $html .= '</a>';

                return $html;
            })
            ->rawColumns(['action', 'user_id'])
            ->removeColumn('items', 'created_at', 'updated_at')
            ->make(true);
    }

    private function getInvoicePath($id)
    {
        return 'users/id/'.$id.'/invoices';
    }

    private function getInvoiceName($token)
    {
        return $token .'.pdf';
    }

    private function getFilePath($invoice)
    {
        $path = $this->getInvoicePath($invoice->user->id);

        $name = $this->getInvoiceName($invoice->transaction_id);

        return $path . '/' . $name;
    }

    private function absolutePath($path)
    {
        return storage_path($path);
    }
}
