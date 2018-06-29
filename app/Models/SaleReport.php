<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReport extends Model
{
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    public function customer_invoice()
    {
        return $this->belongsTo('App\Models\CustomerInvoice', 'customer_invoice_id', 'id');
    }

    public function pos_transaction()
    {
        return $this->belongsTo('App\Models\PosTransaction', 'pos_transaction_id', 'id');
    }
}
