<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Required Data
    |--------------------------------------------------------------------------
    |
    | This is used for major transactions and processes
    | on the site.
    |
    */

    'supported_barcodes' => [
        'EAN', 'UPC', 'Code128', 'ITF-14', 'Code39'
    ],

    'paypal_currencies' => [
        'AUD'   => ['name' => 'Australian Dollar', 'decimal_support' => true],
        'BRL'   => ['name' => 'Brazilian Real', 'decimal_support' => true],
        'CAD'   => ['name' => 'Canadian Dollar', 'decimal_support' => true],
        'CZK'   => ['name' => 'Czech Koruna', 'decimal_support' => true],
        'DKK'   => ['name' => 'Danish Krone', 'decimal_support' => true],
        'EUR'   => ['name' => 'Euro', 'decimal_support' => true],
        'HKD'   => ['name' => 'Hong Kong Dollar', 'decimal_support' => true],
        'HUF'   => ['name' => 'Hungarian Forint', 'decimal_support' => false],
        'INR'   => ['name' => 'Indian Rupee', 'decimal_support' => true],
        'ILS'   => ['name' => 'Israeli New Sheqel', 'decimal_support' => true],
        'JPY'   => ['name' => 'Japanese Yen', 'decimal_support' => false],
        'MYR'   => ['name' => 'Malaysian Ringgit', 'decimal_support' => true],
        'MXN'   => ['name' => 'Mexican Peso', 'decimal_support' => true],
        'NOK'   => ['name' => 'Norwegian Krone', 'decimal_support' => true],
        'NZD'   => ['name' => 'New Zealand Dollar', 'decimal_support' => true],
        'PHP'   => ['name' => 'Philippine Peso', 'decimal_support' => true],
        'PLN'   => ['name' => 'Polish Zloty', 'decimal_support' => true],
        'GBP'   => ['name' => 'Pound Sterling', 'decimal_support' => true],
        'RUB'   => ['name' => 'Russian Ruble', 'decimal_support' => true],
        'SGD'   => ['name' => 'Singapore Dollar', 'decimal_support' => true],
        'SEK'   => ['name' => 'Swedish Krona', 'decimal_support' => true],
        'CHF'   => ['name' => 'Swiss Franc', 'decimal_support' => true],
        'TWD'   => ['name' => 'Taiwan New Dollar', 'decimal_support' => false],
        'THB'   => ['name' => 'Thai Baht', 'decimal_support' => true],
        'USD'   => ['name' => 'U.S. Dollar', 'decimal_support' => true],
    ],
    
    'stripe_currencies' => [
        'AUD'   => ['name' => 'Australian Dollar'],
        'BRL'   => ['name' => 'Brazilian Real'],
        'CAD'   => ['name' => 'Canadian Dollar'],
        'CZK'   => ['name' => 'Czech Koruna'],
        'DKK'   => ['name' => 'Danish Krone'],
        'EUR'   => ['name' => 'Euro'],
        'HKD'   => ['name' => 'Hong Kong Dollar'],
        'HUF'   => ['name' => 'Hungarian Forint'],
        'INR'   => ['name' => 'Indian Rupee'],
        'ILS'   => ['name' => 'Israeli New Sheqel'],
        'JPY'   => ['name' => 'Japanese Yen'],
        'MYR'   => ['name' => 'Malaysian Ringgit'],
        'MXN'   => ['name' => 'Mexican Peso'],
        'NOK'   => ['name' => 'Norwegian Krone'],
        'NZD'   => ['name' => 'New Zealand Dollar'],
        'PHP'   => ['name' => 'Philippine Peso'],
        'PLN'   => ['name' => 'Polish Zloty'],
        'GBP'   => ['name' => 'Pound Sterling'],
        'RUB'   => ['name' => 'Russian Ruble'],
        'SGD'   => ['name' => 'Singapore Dollar'],
        'SEK'   => ['name' => 'Swedish Krona'],
        'CHF'   => ['name' => 'Swiss Franc'],
        'TWD'   => ['name' => 'Taiwan New Dollar'],
        'THB'   => ['name' => 'Thai Baht'],
        'USD'   => ['name' => 'U.S. Dollar'],
    ],
    /*
    |--------------------------------------------------------------------------
    | Authentication Settings
    |--------------------------------------------------------------------------
    |
    | This is used for authentication procedures
    |
    */

    'activation' => env('ACTIVATION', false),

    'timePeriod' => env('ACTIVATION_LIMIT_TIME_PERIOD', 24),

    'maxAttempts' => env('ACTIVATION_LIMIT_MAX_ATTEMPTS', 3),

    'nullIpAddress' => env('NULL_IP_ADDRESS', '0.0.0.0'),

    'restoreUserEncType' => 'AES-256-ECB',

    'restoreUserCutoff' => env('USER_RESTORE_CUTOFF_DAYS', 31),

    'restoreKey' => env('USER_RESTORE_ENCRYPTION_KEY', 'sup3rS3cr3tR35t0r3K3y21!'),

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    |
    | This is used for formating money string
    |
    */

    'currencyLocale' => env('CURRENCY_LOCALE', 'USD'),


    /*
    |--------------------------------------------------------------------------
    | Third Party Settings
    |--------------------------------------------------------------------------
    |
    | This options defines which services to be enabled in the application
    |
    */

    // Google reCaptcha
    'reCaptchaStatus' => env('ENABLE_RECAPTCHA', false),

    // Google Maps
    'googleMapsAPIStatus' => env('ENABLE_GOOGLEMAPS', false),

    // Facebook Login
    'facebookLoginStatus' => env('ENABLE_FACEBOOK_OAUTH', false),

    // Twitter Login
    'twitterLoginStatus' => env('ENABLE_TWITTER_OAUTH', false),

    // Instagram Login
    'instagramLoginStatus' => env('ENABLE_INSTAGRAM_OAUTH', false),

    // Google Login
    'googlePlusLoginStatus' => env('ENABLE_GOOGLE_OAUTH', false)

];
