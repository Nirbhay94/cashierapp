<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Product extends Model
{
    use Eloquence;

    protected $searchableColumns = ['name'];

    public function category()
    {
        return $this->belongsTo('App\Models\ProductCategory', 'product_category_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\ProductUnit', 'product_unit_id', 'id');
    }
}
