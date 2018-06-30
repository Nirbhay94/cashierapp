<?php

namespace Gerardojbaez\Laraplans;

use Gerardojbaez\Laraplans\Feature;

class SubscriptionAbility
{
    /**
     * Subscription model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $subscription;
    protected $positive_words;
    protected $negative_words;


    /**
     * Create a new Subscription instance.
     *
     * @return void
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
        $this->positive_words = config('laraplans.positive_words');
        $this->negative_words = config('laraplans.negative_words');

    }

    /**
     * Determine if the feature is enabled and has
     * available uses.
     *
     * @param string $feature
     * @return boolean
     */
    public function canUse($feature)
    {
        // Get features and usage
        $value = $this->value($feature);

        if (is_null($value)) {
            return false;
        }

        // Match "booleans" type value
        if ($this->enabled($feature) === true) {
            return true;
        }


        return (bool) $value;
    }

    /**
     * Determine if the feature is enabled and has
     * available uses.
     *
     * @param string $feature
     * @return boolean
     */
    public function canUseQuantity($feature)
    {
        // Get features and usage
        $value = $this->value($feature);

        if (is_null($value)) {
            return false;
        }

        // Match "booleans" type value
        if ($this->enabled($feature) === true) {
            return true;
        }

        // Check for available uses
        $remaining = $this->remainings($feature);

        if(is_bool($remaining)){
            return $remaining;
        }elseif(is_int($remaining)){
            return  $remaining > 0;
        }

        return false;
    }

    /**
     * Get how many times the feature has been used.
     *
     * @param  string $feature
     * @return int
     */
    public function consumed($feature)
    {
        foreach ($this->subscription->usage as $key => $usage) {
            if ($usage->code === $feature && !$usage->isExpired()) {
                return $usage->used;
            }
        }

        return 0;
    }

    /**
     * Get the available uses. If it is an unlimited feature then we return a boolean
     *
     * @param  string $feature
     * @return int|boolean
     */
    public function remainings($feature)
    {
        $value = $this->value($feature);
        $consumed = $this->consumed($feature);

        if (in_array($value, $this->positive_words)) {
            return true;
        }

        if (in_array($value, $this->negative_words)) {
            return false;
        }

        if((int) $value < 0){
            return true;
        }

        return ((int) $value - (int) $consumed);
    }

    /**
     * Check if subscription plan feature is enabled.
     *
     * @param string $feature
     * @return bool
     */
    public function enabled($feature)
    {
        $value = $this->value($feature);

        if (is_null($value)) {
            return false;
        }

        if (in_array($value, $this->positive_words)) {
            return true;
        }

        if (in_array($value, $this->negative_words)) {
            return false;
        }

        if ((int) $value < 0) {
            return true;
        }

        return false;
    }

    /**
     * Get feature value.
     *
     * @param  string $feature
     * @param  mixed $default
     * @return mixed
     */
    public function value($feature, $default = null)
    {
        foreach ($this->subscription->plan->features as $key => $data) {
            if ($feature === $data->code) {
                return $data->value;
            }
        }

        return $default;
    }
}
