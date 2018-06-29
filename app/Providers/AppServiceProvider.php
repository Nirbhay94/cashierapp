<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        $this->prepareDatabase();
        $this->forceURLScheme($url);
        $this->setupBraintree();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @param UrlGenerator $url
     */
    private function forceURLScheme($url){
        if(env('APP_REDIRECT_HTTPS', false)) {
            $url->forceScheme('https');
        }
    }

    /**
     * Prepare database schema
     */
    private function prepareDatabase()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Setup Braintree Credentials
     */
    private function setupBraintree()
    {
        \Braintree_Configuration::environment(config('services.braintree.environment'));
        \Braintree_Configuration::merchantId(config('services.braintree.merchant_id'));
        \Braintree_Configuration::publicKey(config('services.braintree.public_key'));
        \Braintree_Configuration::privateKey(config('services.braintree.private_key'));
    }
}
