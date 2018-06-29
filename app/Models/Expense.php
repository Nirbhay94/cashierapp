<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function category()
    {
        return $this->belongsTo('App\Models\ExpenseCategory', 'expense_category_id', 'id');
    }
}
