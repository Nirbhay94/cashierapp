<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInvoiceTransaction extends Model
{
    public function customer_invoice()
    {
        return $this->belongsTo('App\Models\CustomerInvoice', 'customer_invoice_id', 'id');
    }
}
