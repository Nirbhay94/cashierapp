<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'product_unit_id', 'id');
    }
}
