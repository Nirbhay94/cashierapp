<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 5/18/2018
 * Time: 6:26 AM
 */

namespace App\Notifications\Customer\Traits;


use App\Models\Customer;
use App\Models\User;

trait BladeProperties
{
    /**
     * User Model
     *
     * @var User $user
     */
    protected $user;

    /**
     * Customer Model
     *
     * @var Customer $user
     */
    protected $customer;

    /**
     * Required html content
     *
     * @var $content
     */
    protected $content = [];

    /**
     * Set From Address
     *
     * @var $from
     */
    protected $from_address = null;

    /**
     * Set From Name
     *
     * @var $from
     */
    protected $from_name = null;

    /**
     * Set Reply To Address
     *
     * @var $reply_to
     */
    protected $reply_to_address = null;

    /**
     * Set Reply To Name
     *
     * @var $reply_to
     */
    protected $reply_to_name = null;


}