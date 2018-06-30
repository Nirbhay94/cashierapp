<?php

if(!function_exists('array_combine_single')){
    function array_combine_single(array $values){
        return array_combine($values, $values);
    }
}

if(!function_exists('isActive')){
    /**
     * Set the active class to the current opened menu.
     *
     * @param  string|array $route
     * @param  string       $className
     * @return string
     */
    function isActive($route, $className = 'active')
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }
        if (Route::currentRouteName() == $route) {
            return $className;
        }
        if (strpos(URL::current(), $route)) return $className;
    }
}

if(!function_exists('is_app_env')) {
    function is_app_env($key){
        return strpos($key, 'APP_') === 0;
    }
}

if(!function_exists('is_db_env')) {
    function is_db_env($key){
        return strpos($key, 'DB_') === 0;
    }
}

if(!function_exists('is_mail_env')) {
    function is_mail_env($key){
        return strpos($key, 'MAIL_') === 0;
    }
}

if(!function_exists('get_php_timezones')){
    function get_php_timezones(){
        return DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    }
}
