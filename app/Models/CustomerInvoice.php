<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
