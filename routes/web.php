<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    Route::group(['middleware' => ['global']], function(){
        Route::get('/', 'WelcomeController@welcome')->name('welcome');

        Auth::routes();

        Route::group(['prefix' => 'images/profile'], function(){
            Route::get('{id}/invoice/logo/{image}', 'Configuration\InvoiceController@invoiceLogo')->name('image.profile.invoice.logo');

            Route::get('{id}/avatar/{image}', 'ProfilesController@userProfileAvatar')->name('image.profile.avatar');
        });

        Route::get('qr-code', function(){
            $data = 'htpp://mycashier.app';
            return '<img src="'.(new \chillerlan\QRCode\QRCode)->render($data).'" />';
        });
    });

    // Public Routes
    Route::group(['middleware' => ['web', 'global', 'lock']], function () {
        // Socialite Register Routes
        Route::get('social/redirect/{provider}', 'Auth\SocialController@getSocialRedirect')->name('social.redirect');

        Route::get('social/handle/{provider}', 'Auth\SocialController@getSocialHandle')->name('social.handle');

        Route::group(['prefix' => 'customer/invoice/{token}', 'namespace' => 'Invoices'], function(){
            Route::get('download', 'InvoiceController@download')->name('invoice.download');

            Route::get('payment', 'InvoiceController@payment')->name('invoice.payment');
        });
    });

    Route::group(['middleware' => 'global'], function(){
        Route::group(['prefix' => 'customer/invoice/{token}', 'namespace' => 'Invoices'], function(){

            Route::group(['prefix' => 'paypal'], function(){
                Route::post('create_payment', 'InvoiceController@paypalCreatePayment')->name('invoice.paypal.create_payment');

                Route::any('execute_payment', 'InvoiceController@paypalExecutePayment')->name('invoice.paypal.execute_payment');
            });

            Route::group(['prefix' => 'stripe'], function(){
                Route::post('charge', 'InvoiceController@stripeCharge')->name('invoice.stripe.charge');
            });

        });
    });

// Registered and non-verified Routes...
    Route::group(['middleware' => ['auth', 'global', 'lock']], function () {

        // Platform's Home Route
        Route::group(['prefix' => 'home', 'namespace' => 'Dashboard'], function(){
            Route::get('', 'HomeController@index')->name('home');

            Route::get('line-data', 'HomeController@ajaxLineData')->name('home.line-data');
        });


        Route::group(['prefix' => 'dashboard', 'namespace' => 'Dashboard'], function(){
            Route::group(['prefix' => 'reports'], function(){
                Route::get('', 'ReportsController@index')->name('dashboard.reports');

                Route::get('line-data', 'ReportsController@ajaxLineData')->name('dashboard.reports.line-data');
            });

            Route::group(['prefix' => 'tax'], function(){
                Route::get('', 'TaxController@index')->name('dashboard.tax');

                Route::post('post', 'TaxController@data')->name('dashboard.tax.data');
            });
        });
    });

// Registered and verified User Routes...
    Route::group(['middleware' => ['auth', 'verified', 'global', 'lock']], function () {

        Route::get('/elfinder/popup/{input_id}', '\Barryvdh\Elfinder\ElfinderController@showPopup')->name('filemanager-popup');

        // Products
        Route::group(['prefix' => 'products', 'namespace' => 'Products'], function(){
            Route::group(['prefix' => 'all'], function(){
                Route::get('', 'AllController@index')->name('products.all');

                Route::get('ajax/search', 'AllController@search')->name('products.all.ajax.search');

                Route::post('ajax/fetch', 'AllController@fetch')->name('products.all.ajax.fetch');

                Route::post('', 'AllController@store')->name('products.all');

                Route::put('{id}', 'AllController@edit')->name('products.all.edit');

                Route::delete('{id}', 'AllController@destroy')->name('products.all.destroy');

                Route::post('data', 'AllController@data')->name('products.all.data');
            });

            Route::group(['prefix' => 'quantity'], function(){
                Route::get('', 'QuantityController@index')->name('products.quantity');

                Route::post('', 'QuantityController@store')->name('products.quantity');
            });

            Route::group(['prefix' => 'import'], function(){
                Route::get('', 'ImportController@index')->name('products.import');

                Route::post('', 'ImportController@upload')->name('products.import');

                Route::get('sample', 'ImportController@download')->name('products.import.sample');
            });

            Route::group(['prefix' => 'category'], function(){
                Route::get('', 'CategoryController@index')->name('products.category');

                Route::post('', 'CategoryController@store')->name('products.category');

                Route::put('{id}', 'CategoryController@edit')->name('products.category.edit');

                Route::delete('{id}', 'CategoryController@destroy')->name('products.category.destroy');

                Route::post('data', 'CategoryController@data')->name('products.category.data');
            });

            Route::group(['prefix' => 'coupon'], function(){
                Route::get('', 'CouponController@index')->name('products.coupon');

                Route::post('', 'CouponController@store')->name('products.coupon');

                Route::post('ajax/get', 'CouponController@get')->name('products.coupon.ajax.get');

                Route::put('{id}', 'CouponController@edit')->name('products.coupon.edit');

                Route::delete('{id}', 'CouponController@destroy')->name('products.coupon.destroy');

                Route::post('data', 'CouponController@data')->name('products.coupon.data');
            });
        });

        Route::group(['prefix' => 'expenses', 'namespace' => 'Expenses'], function() {
            Route::group(['prefix' => 'list'], function(){
                Route::get('', 'ListController@index')->name('expenses.list');

                Route::post('', 'ListController@store')->name('expenses.list');

                Route::post('data', 'ListController@data')->name('expenses.list.data');

                Route::put('{id}', 'ListController@edit')->name('expenses.list.edit');

                Route::delete('{id}', 'ListController@destroy')->name('expenses.list.destroy');
            });

            Route::group(['prefix' => 'category'], function(){
                Route::get('', 'CategoryController@index')->name('expenses.categories');

                Route::post('', 'CategoryController@store')->name('expenses.categories');

                Route::post('data', 'CategoryController@data')->name('expenses.categories.data');

                Route::put('{id}', 'CategoryController@edit')->name('expenses.categories.edit');

                Route::delete('{id}', 'CategoryController@destroy')->name('expenses.categories.destroy');
            });
        });

        Route::group(['prefix' => 'invoice', 'namespace' => 'Invoices'], function(){

            Route::group(['prefix' => 'list'], function(){
                Route::get('', 'ListController@index')->name('invoice.list');

                Route::post('', 'ListController@store')->name('invoice.list');

                Route::post('{id}/pay', 'ListController@pay')->name('invoice.list.pay');

                Route::delete('{id}', 'ListController@destroy')->name('invoice.list.destroy');

                Route::post('data', 'ListController@data')->name('invoice.list.data');
            });

            Route::group(['prefix' => 'import'], function(){
                Route::get('', 'ImportController@index')->name('invoice.import');

                Route::post('', 'ImportController@upload')->name('invoice.import');

                Route::get('sample', 'ImportController@download')->name('invoice.import.sample');
            });

            Route::group(['prefix' => 'template'], function(){
                Route::get('', 'TemplateController@index')->name('invoice.template');
            });

            Route::group(['prefix' => 'draft'], function(){
                Route::get('', 'DraftController@index')->name('invoice.draft');
            });
        });

        // Customers
        Route::group(['prefix' => 'customers', 'namespace' => 'Customers'], function(){

            Route::group(['prefix' => 'list'], function(){
                Route::get('', 'ListController@index')->name('customers.list');

                Route::post('', 'ListController@store')->name('customers.list');

                Route::get('ajax/search', 'ListController@search')->name('customers.list.ajax.search');

                Route::post('ajax/fetch', 'ListController@fetch')->name('customers.list.ajax.fetch');

                Route::post('ajax/get', 'ListController@get')->name('customers.list.ajax.get');

                Route::put('{id}', 'ListController@edit')->name('customers.list.edit');

                Route::delete('{id}', 'ListController@destroy')->name('customers.list.destroy');

                Route::post('data', 'ListController@data')->name('customers.list.data');
            });

            Route::group(['prefix' => 'import'], function(){
                Route::get('', 'ImportController@index')->name('customers.import');

                Route::post('', 'ImportController@upload')->name('customers.import');

                Route::get('sample', 'ImportController@download')->name('customers.import.sample');
            });

            Route::group(['prefix' => 'broadcast'], function(){
                Route::get('', 'BroadcastController@index')->name('customers.broadcast');

                Route::post('', 'BroadcastController@store')->name('customers.broadcast');
            });

            Route::group(['prefix' => 'balance'], function(){
                Route::get('', 'BalanceController@index')->name('customers.balance');

                Route::post('', 'BalanceController@store')->name('customers.balance');
            });
        });

        Route::group(['prefix' => 'people', 'namespace' => 'People'], function() {

            Route::group(['prefix' => 'suppliers'], function () {
                Route::get('', 'SuppliersController@index')->name('people.suppliers');

                Route::post('', 'SuppliersController@store')->name('people.suppliers');

                Route::post('data', 'SuppliersController@data')->name('people.suppliers.data');

                Route::put('{id}', 'SuppliersController@edit')->name('people.suppliers.edit');

                Route::delete('{id}', 'SuppliersController@destroy')->name('people.suppliers.destroy');
            });
        });

        Route::group(['prefix' => 'transactions', 'namespace' => 'Transactions'], function() {
            Route::group(['prefix' => 'pos'], function () {
                Route::get('', 'PosController@index')->name('transactions.pos');

                Route::post('data', 'PosController@data')->name('transactions.pos.data');

                Route::get('{id}', 'PosController@receipt')->name('transactions.pos.receipt');
            });

            Route::group(['prefix' => 'invoices'], function () {
                Route::get('', 'InvoiceController@index')->name('transactions.invoice');

                Route::post('data', 'InvoiceController@data')->name('transactions.invoice.data');
            });
        });

        // POS
        Route::group(['prefix' => 'pos'], function(){
            Route::get('', 'PosController@index')->name('pos');

            Route::post('invoice', 'PosController@invoice')->name('pos.invoice');

            Route::post('checkout', 'PosController@checkout')->name('pos.checkout');
        });

        Route::group(['prefix' => 'payment', 'namespace' => 'Payment'], function(){
            Route::group(['prefix' => 'bank'], function(){
                Route::get('', 'BankController@index')->name('payment.bank');

                Route::post('', 'BankController@store')->name('payment.bank');
            });

            Route::group(['prefix' => 'paypal'], function(){
                Route::get('', 'PaypalController@index')->name('payment.paypal');

                Route::post('', 'PaypalController@store')->name('payment.paypal');
            });

            Route::group(['prefix' => 'stripe'], function(){
                Route::get('', 'StripeController@index')->name('payment.stripe');

                Route::post('', 'StripeController@store')->name('payment.stripe');
            });
        });

        // Category
        Route::group(['prefix' => 'configuration', 'namespace' => 'Configuration'], function(){
            Route::group(['prefix' => 'email'], function(){
                Route::get('', 'EmailController@index')->name('configuration.email');

                Route::post('', 'EmailController@store')->name('configuration.email');

                Route::get('preview', 'EmailController@preview')->name('configuration.email.preview');
            });

            Route::group(['prefix' => 'payment'], function(){
                Route::get('', 'PaymentController@index')->name('configuration.payment');
            });

            Route::group(['prefix' => 'sms'], function(){
                Route::get('', 'SmsController@index')->name('configuration.sms');
            });

            Route::group(['prefix' => 'pos'], function(){
                Route::get('', 'PosController@index')->name('configuration.pos');

                Route::post('', 'PosController@store')->name('configuration.pos');
            });

            Route::group(['prefix' => 'tax'], function(){
                Route::get('', 'TaxController@index')->name('configuration.tax');

                Route::post('', 'TaxController@store')->name('configuration.tax');

                Route::put('{id}', 'TaxController@edit')->name('configuration.tax.edit');

                Route::delete('{id}', 'TaxController@destroy')->name('configuration.tax.destroy');

                Route::post('data', 'TaxController@data')->name('configuration.tax.data');
            });

            Route::group(['prefix' => 'invoice'], function(){
                Route::get('', 'InvoiceController@index')->name('configuration.invoice');

                Route::post('', 'InvoiceController@store')->name('configuration.invoice');
            });

            Route::group(['prefix' => 'unit'], function(){
                Route::get('', 'UnitController@index')->name('configuration.unit');

                Route::post('', 'UnitController@store')->name('configuration.unit');

                Route::put('{id}', 'UnitController@edit')->name('configuration.unit.edit');

                Route::delete('{id}', 'UnitController@destroy')->name('configuration.unit.destroy');

                Route::post('data', 'UnitController@data')->name('configuration.unit.data');
            });
        });

        // User Subscriptions and Summary Routes
        Route::group(['prefix' => 'subscription', 'namespace' => 'Subscription', 'middleware' => 'license:extended'], function(){

            // Subscription Summary
            Route::group(['prefix' => 'summary', 'middleware' => ['permission:view financial data']], function(){
                Route::get('/', 'SummaryController@index')->name('subscription.summary');
            });

            // Subscription Invoices
            Route::group(['prefix' => 'invoices', 'middleware' => ['permission:view financial data|subscribe to services']], function(){
                Route::get('/', 'InvoicesController@index')->name('subscription.invoices');

                Route::post('data', 'InvoicesController@data')->name('subscription.invoices.data');

                Route::get('{id}/download', 'InvoicesController@download')->name('subscription.invoices.download');
            });

            // Subscription Change
            Route::group(['prefix' => 'change', 'middleware' => ['permission:subscribe to services']], function(){
                Route::get('/', 'ChangeController@index')->name('subscription.change');

                Route::get('checkout', 'ChangeController@checkout')->name('subscription.change.checkout');

                Route::post('checkout', 'ChangeController@process')->name('subscription.change.checkout');

                Route::post('checkout/begin-trial', 'ChangeController@processTrial')->name('subscription.change.checkout.begin-trial');

                Route::post('checkout/apply-credit', 'ChangeController@processCredit')->name('subscription.change.checkout.apply-credit');
            });

            // Balance Topup
            Route::group(['prefix' => 'topup', 'middleware' => ['permission:subscribe to services']], function(){
                Route::get('/', 'TopupController@index')->name('subscription.topup');

                Route::post('/', 'TopupController@process')->name('subscription.topup');
            });

            // Subscription Extend
            Route::group(['prefix' => 'extend', 'middleware' => ['permission:subscribe to services']], function(){
                Route::get('/', 'ExtendController@index')->name('subscription.extend');

                Route::get('checkout', 'ExtendController@checkout')->name('subscription.extend.checkout');

                Route::post('checkout', 'ExtendController@process')->name('subscription.extend.checkout');

                Route::post('checkout/apply-credit', 'ExtendController@processCredit')->name('subscription.extend.checkout.apply-credit');
            });
        });
    });

// Registered and verified Ajax Routes.
    Route::group(['middleware' => ['auth'], 'prefix' => 'ajax'], function(){

        // Profile Ajax Routes.
        Route::group(['prefix' => 'profile'], function(){
            Route::post('{username}/update', 'AjaxController@updateProfile')->name('ajax.profile.update');
        });

        Route::post('resend-verification-email', 'AjaxController@resendVerificationEmail')->name('ajax.resend-verification-email');

    });

// Registered, verified, and is current user routes.
    Route::group(['middleware' => ['auth', 'verified', 'global', 'lock']], function () {

        // User Profile and Account Routes
        Route::resource('profile', 'ProfilesController', [
            'names' => [
                'show'          => 'profile.show',
                'create'        => 'profile.create',
                'edit'          => 'profile.edit',
                'update'        => 'profile.update',
                'destroy'       => 'profile.destroy'
            ],

            'only' => ['show', 'edit', 'update', 'create'],
        ])->parameters(['profile' => 'username']);

        // Profile Edit Routes
        Route::group(['prefix' => 'profile'], function(){
            Route::put('{id}/update-account', 'ProfilesController@updateAccount')->name('profile.update-account');

            Route::put('{id}/update-password', 'ProfilesController@updatePassword')->name('profile.update-password');

            Route::delete('{id}/delete-account', 'ProfilesController@deleteAccount')->name('profile.delete-account');
        });

        // Route to upload user avatar.
        Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);
    });

// Registered, verified, and is admin routes.
    Route::group(['middleware' => ['auth', 'verified', 'global', 'lock']], function () {

        // Administration Routes
        Route::group(['prefix' => 'administration'], function(){

            // Deleted Users Routes
            Route::resource('deleted-users', 'Administration\DeletedUsersController', [
                'names' => [
                    'index'     => 'administration.deleted-users.index',
                    'show'      => 'administration.deleted-users.show',
                    'update'    => 'administration.deleted-users.update',
                    'destroy'   => 'administration.deleted-users.destroy'
                ],

                'only' => ['index', 'show', 'update', 'destroy'],
            ])->parameters(['deleted-users' => 'id'])->middleware('permission:read users|alter users');

            Route::post('deleted-users/data', 'Administration\DeletedUsersController@data')->name('administration.deleted-users.data');

            // Users Routes
            Route::resource('users', 'Administration\UsersController', [
                'names' => [
                    'index'     => 'administration.users.index',
                    'show'      => 'administration.users.show',
                    'edit'      => 'administration.users.edit',
                    'update'    => 'administration.users.update',
                    'destroy'   => 'administration.users.destroy',
                    'create'    => 'administration.users.create',
                    'store'     => 'administration.users.store'
                ]
            ])->parameters(['users' => 'id'])->middleware('permission:read users|alter users');

            Route::post('users/data', 'Administration\UsersController@data')->name('administration.users.data');

            // Permission Routes
            Route::resource('permissions', 'Administration\PermissionsController', [
                'names' => [
                    'index'     => 'administration.permissions.index',
                    'edit'      => 'administration.permissions.edit',
                    'update'    => 'administration.permissions.update',
                    'destroy'   => 'administration.permissions.destroy',
                    'create'    => 'administration.permissions.create',
                    'store'     => 'administration.permissions.store'
                ]
            ])->parameters(['users' => 'id'])->middleware('permission:manage permissions');

            Route::post('permissions/data', 'Administration\PermissionsController@data')->name('administration.permissions.data');

            // Plans Routes
            Route::resource('plans', 'Administration\PlansController', [
                'names' => [
                    'index'     => 'administration.plans.index',
                    'edit'      => 'administration.plans.edit',
                    'update'    => 'administration.plans.update',
                    'destroy'   => 'administration.plans.destroy',
                    'create'    => 'administration.plans.create',
                    'store'     => 'administration.plans.store'
                ], 'except' => ['show']
            ])->parameters(['plans' => 'id'])->middleware('permission:manage plans');
        });

        // Settings Routes
        Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function(){
            Route::get('global', 'GlobalController@index')->name('settings.global');
            Route::post('global', 'GlobalController@update')->name('settings.global');

            Route::get('payment', 'PaymentController@index')->name('settings.payment');
            Route::post('payment', 'PaymentController@update')->name('settings.payment');
        });

        // Services Routes
        Route::group(['prefix' => 'services', 'namespace' => 'Services'], function(){
            Route::group(['prefix' => 'facebook'], function(){
                Route::get('', 'FacebookController@index')->name('services.facebook');

                Route::post('', 'FacebookController@update')->name('services.facebook');

                Route::get('login', 'FacebookController@login')->name('services.facebook.login');

                Route::get('callback', 'FacebookController@callback')->name('services.facebook.callback');
            });

            Route::group(['prefix' => 'twitter'], function(){
                Route::get('', 'TwitterController@index')->name('services.twitter');

                Route::post('', 'TwitterController@update')->name('services.twitter');
            });

            Route::group(['prefix' => 'exchange'], function(){
                Route::get('', 'ExchangeController@index')->name('services.exchange');

                Route::post('', 'ExchangeController@update')->name('services.exchange');
            });

            Route::group(['prefix' => 'braintree'], function(){
                Route::get('', 'BraintreeController@index')->name('services.braintree');

                Route::post('', 'BraintreeController@update')->name('services.braintree');
            });

            Route::group(['prefix' => 'google'], function (){
                Route::get('', 'GoogleController@index')->name('services.google');

                Route::post('google-maps', 'GoogleController@updateGoogleMaps')->name('services.google.google-maps');

                Route::post('google-recaptcha', 'GoogleController@updateGoogleRecaptcha')->name('services.google.google-recaptcha');
            });
        });
    });

});

