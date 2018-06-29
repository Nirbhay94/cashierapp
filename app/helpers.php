<?php

if(!function_exists('get_available_locales')){
    function get_available_locales(){
        return [
            'en' => 'English',
            'es' => 'Spanish',
            'zh' => 'Chinese',
            'pt' => 'Portuguese',
            'ja' => 'Japanese',
            'ru' => 'Russian',
            'ms' => 'Malay',
            'fr' => 'French',
            'de' => 'German',
        ];
    }
}

if(!function_exists('get_currencies')){
    function get_currencies(){
        $file = storage_path('currencies.json');

        if(file_exists($file)){
            $contents = file_get_contents($file);

            $currencies = json_decode($contents, true);

            return array_pluck($currencies, 'name', 'code');
        }

        return [];
    }
}
if(!function_exists('array_combine_single')){
    function array_combine_single(array $values){
        return array_combine($values, $values);
    }
}

if(!function_exists('get_barcode_types')){
    function get_barcode_types(){
        return ['EAN', 'UPC', 'Code128', 'ITF-14', 'Code39'];
    }
}

if(!function_exists('previous_months')){
    function previous_months(){
        $months = [];

        for($i = 1; $i <= now()->month; $i++){
            $months[$i] = date("F", mktime(0, 0, 0, $i, 15));
        }

        return $months;
    }
}

if(!function_exists('money_number_format')){
    function money_number_format($number){
        return number_format((float) $number, 2, '.', '');
    }
}

if(!function_exists('get_currencies')){
    function get_currencies(){
        $currencies =  collect(app('currency')->getCurrencies());

        return $currencies->pluck('name', 'code')->toArray();
    }
}

if(!function_exists('get_php_timezones')){
    function get_php_timezones(){
        return DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    }
}

if(!function_exists('invoice_label')){
    function invoice_label($status){
        switch ($status){
            case 'paid':
                $label = 'success';
                break;
            case 'partial':
                $label = 'primary';
                break;
            case 'unpaid':
                $label = 'danger';
                break;
            default:
                $label = 'default';
                break;
        }
        return $label;
    }
}

if(!function_exists('pos_label')){
    function pos_label($status){
        switch ($status){
            case 'completed':
                $label = 'success';
                break;
            case 'invoice':
                $label = 'primary';
                break;
            default:
                $label = 'default';
                break;
        }
        return $label;
    }
}

if(!function_exists('money')){
    /**
     * @param \App\Models\User $user
     * @param $number
     * @return string
     */
    function money($number, $user = null){
        $locale = config('settings.currencyLocale');

        if($user && ($config = $user->invoice_configuration)){
            if($config && $config->currency_locale){
                $locale = $config->currency_locale;
            }
        }

        return currency_format((int) $number, $locale);
    }
}


if(!function_exists('currency')){
    /**
     * @param \App\Models\User $user
     * @param bool $show_character
     * @return string
     */
    function currency($user = null, $show_character = false){
        $locale = config('settings.currencyLocale');

        if($user && ($config = $user->invoice_configuration)){
            if($config && $config->currency_locale){
                $locale = $config->currency_locale;
            }
        }

        $currency = app('currency')->getCurrency($locale);

        return $show_character ? $currency['code']: $currency['symbol'];
    }
}

if(!function_exists('can_use_feature')){
    function can_use_feature($user, $feature){
        $subscription = $user->subscription('main');

        if($user->can('subscribe to services')){
            if($subscription && $subscription->isActive()) {
                return $subscription->ability()->canUse($feature);
            }else{
                return false;
            }
        }

        return true;
    }
}

if(!function_exists('record_feature')){
    function record_feature($user, $feature, $uses){
        if($user->can('subscribe to services')){
            if(can_use_feature($user, $feature)){
                return $user->subscriptionUsage('main')->record($feature, $uses);
            }else{
                return false;
            }
        }

        return true;
    }
}

if(!function_exists('reduce_feature')){
    function reduce_feature($user, $feature, $uses){
        if($user->can('subscribe to services')){
            if(can_use_feature($user, $feature)){
                return $user->subscriptionUsage('main')->reduce($feature, $uses);
            }else{
                return false;
            }
        }

        return true;
    }
}