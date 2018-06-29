<?php

return [
    // your recapthca site key.
    'siteKey' => env('RECAPTCHA_KEY'),
    // your recapthca secret key.
    'secretKey' => env('RECAPTCHA_SECRET'),
    // other options to customize your configs
    'options' => [
        // set true if you want to hide your recaptcha badge
        'hideBadge' => env('RECAPTCHA_BADGEHIDE', false),
        // optional, reposition the reCAPTCHA badge. 'inline' allows you to control the CSS.
        // available values: bottomright, bottomleft, inline
        'dataBadge' => env('RECAPTCHA_DATABADGE', 'bottomright'),
        // timeout value for guzzle client
        'timeout' => env('RECAPTCHA_TIMEOUT', 5),
        // set true to show binding status on your javascript console
        'debug' => env('RECAPTCHA_DEBUG', false)
    ]
];
