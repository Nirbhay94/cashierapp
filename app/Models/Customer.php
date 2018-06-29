<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Sofa\Eloquence\Eloquence;

class Customer extends Model
{
    use Eloquence;
    use Notifiable;

    /**
     * Define all searchable columns in the table
     *
     * @var array
     */
    protected $searchableColumns = ['name'];

    /**
     * Route notifications for the Sms channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSms($notification)
    {
        return $this->phone_number;
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\CustomerInvoice', 'customer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
