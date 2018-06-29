<?php

namespace App\Models;

use Gerardojbaez\Laraplans\Contracts\PlanSubscriberInterface;
use Gerardojbaez\Laraplans\Traits\PlanSubscriber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Trexology\Pointable\Contracts\Pointable;

class User extends Authenticatable implements PlanSubscriberInterface, Pointable
{
    use HasRoles;
    use Notifiable;
    use SoftDeletes;
    use PlanSubscriber;
    use \Trexology\Pointable\Traits\Pointable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

/*
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'verified',
        'verification_token',
        'token',
        'auto_renewal',
        'signup_ip_address',
        'confirmation_ip_address',
        'social_signup_ip_address',
        'admin_signup_ip_address',
        'updated_ip_address',
        'last_login_ip_address',
        'deleted_ip_address',
    ];
*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function social()
    {
        return $this->hasMany('App\Models\Social');
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function profiles()
    {
        return $this->belongsToMany('App\Models\Profile')->withTimestamps();
    }

    public function email_configuration()
    {
        return $this->hasOne('App\Models\EmailConfiguration', 'user_id', 'id');
    }

    public function pos_configuration()
    {
        return $this->hasOne('App\Models\PosConfiguration', 'user_id', 'id');
    }

    public function invoice_configuration()
    {
        return $this->hasOne('App\Models\CustomerInvoiceConfiguration', 'user_id', 'id');
    }

    public function paypal_credential()
    {
        return $this->hasOne('App\Models\PaypalCredential', 'user_id', 'id');
    }

    public function stripe_credential()
    {
        return $this->hasOne('App\Models\StripeCredential', 'user_id', 'id');
    }

    public function bank_credential()
    {
        return $this->hasOne('App\Models\BankCredential', 'user_id', 'id');
    }

    public function can_use($feature)
    {
        $subscription = $this->subscription('main');

        if($this->can('subscribe to services')){
            if($subscription && $subscription->isActive()) {
                return $subscription->ability()->canUse($feature);
            }else{
                return false;
            }
        }

        return true;
    }

    public function record($feature, $uses = 1)
    {
        if($this->can('subscribe to services')){
            if($this->can_use($feature)){
                return $this->subscriptionUsage('main')->record($feature, $uses);
            }else{
                return false;
            }
        }

        return true;
    }

    public function reduce($feature, $uses = 1)
    {
        if($this->can('subscribe to services')){
            if($this->can_use($feature)){
                return $this->subscriptionUsage('main')->reduce($feature, $uses);
            }else{
                return false;
            }
        }

        return true;
    }

    public function hasProfile($name)
    {
        foreach ($this->profiles as $profile) {
            if ($profile->name == $name) {
                return true;
            }
        }

        return false;
    }

    public function assignProfile($profile)
    {
        return $this->profiles()->attach($profile);
    }

    public function removeProfile($profile)
    {
        return $this->profiles()->detach($profile);
    }

    public function expenses()
    {
        return $this->hasMany('App\Models\Expense', 'user_id', 'id');
    }

    public function expense_categories()
    {
        return $this->hasMany('App\Models\ExpenseCategory', 'user_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'user_id', 'id');
    }

    public function product_categories()
    {
        return $this->hasMany('App\Models\ProductCategory', 'user_id', 'id');
    }

    public function product_coupons()
    {
        return $this->hasMany('App\Models\ProductCoupon', 'user_id', 'id');
    }

    public function product_taxes()
    {
        return $this->hasMany('App\Models\ProductTax', 'user_id', 'id');
    }

    public function product_units()
    {
        return $this->hasMany('App\Models\ProductUnit', 'user_id', 'id');
    }


    public function suppliers()
    {
        return $this->hasMany('App\Models\Supplier', 'user_id', 'id');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'user_id', 'id');
    }

    public function customer_invoices()
    {
        return $this->hasMany('App\Models\CustomerInvoice', 'user_id', 'id');
    }

    public function pos_transactions()
    {
        return $this->hasMany('App\Models\PosTransaction', 'user_id', 'id');
    }

    public function customer_invoice_transactions()
    {
        return $this->hasMany('App\Models\CustomerInvoiceTransaction', 'user_id', 'id');
    }

    public function sale_reports()
    {
        return $this->hasMany('App\Models\SaleReport', 'user_id', 'id');
    }
}
