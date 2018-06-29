<?php

use Illuminate\Validation\Rule;

return [

    /*
    |--------------------------------------------------------------------------
    | App Details & Author's Portfolio
    |--------------------------------------------------------------------------
    |
    | This is used by the installer to display the app de
    |
    */

    'name' => 'MyCashier - Everything you need to start managing payment transactions.',

    'documentation' => 'http://products.oluwatosin.me/mycashier/docs',

    'thumbnail' => 'http://oluwatosin.me/cdn/images/mycashier_logo.png',

    'market_place' => 'CodeCanyon',

    'link' => 'https://codecanyon.net/item/mycashier-everything-you-need-to-start-managing-payment-transactions/22000978',

    /*
    |--------------------------------------------------------------------------
    | Author Portfolio
    |--------------------------------------------------------------------------
    |
    | The credentials of the author goes here..
    |
    */

    'author' => [
        'name' => 'HolluwaTosin360',

        'avatar' => 'http://oluwatosin.me/avatar.jpg',

        'portfolio' => 'https://codecanyon.net/user/holluwatosin360/portfolio',
    ],

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */
    'core' => [
        'minPhpVersion' => '7.1.0'
    ],

    'requirements' => [
        'php' => [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            'JSON',
            'cURL',
            'gd',
            'fileinfo',
            'zip',
            'intl'
        ],
        'apache' => [
            'mod_rewrite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        'storage/framework/'     => '775',
        'storage/logs/'          => '775',
        'bootstrap/cache/'       => '775'
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Form Wizard Validation Rules & Messages
    |--------------------------------------------------------------------------
    |
    | This are the default form field validation rules. Available Rules:
    | https://laravel.com/docs/5.4/validation#available-validation-rules
    |
    */
    'environment' => [
        'keys' => [
            'APP_NAME' => [
                'label' => 'APPLICATION NAME',
                'hint' => 'This is used for sending emails to end users',
                'type' => 'text',
                'placeholder' => 'MyCashier...',
                'rules' => 'required|string|max:50'
            ],

            'APP_TIMEZONE' => [
                'label' => 'TIMEZONE',
                'hint' => '',
                'type' => 'select',
                'options' => array_combine_single(get_php_timezones()),
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'APP_URL' => [
                'label' => 'URL',
                'hint' => 'Set your website link. Kindly confirm before you proceed.',
                'type' => 'text',
                'placeholder' => '(e.g http:// or https://example.com/)',
                'rules' => 'required|url'
            ],

            'APP_REDIRECT_HTTPS' => [
                'label' => 'FORCE HTTPS',
                'hint' => 'This is strongly required by payment processors',
                'type' => 'select',
                'options' => [
                    'true' => 'Yes',
                    'false' => 'No'
                ],
                'rules' => [
                    'required', Rule::in(['true', 'false'])
                ],
                'placeholder' => '',
            ],

            'APP_LOCALE' => [
                'label' => 'LOCALE',
                'hint' => 'Select your default installation language.',
                'type' => 'select',
                'options' => get_available_locales(),
                'placeholder' => '',
                'rules' => [
                    'required', Rule::in(array_keys(get_available_locales()))
                ]
            ],

            'DB_HOST' => [
                'label' => 'DATABASE HOST',
                'hint' => 'Leave as localhost if your database is on the same server as this script.',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'DB_PORT' => [
                'label' => 'DATABASE PORT',
                'hint' => 'This is usually 3306. Please specify if it is otherwise.',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|numeric'
            ],

            'DB_DATABASE' => [
                'label' => 'DATABASE NAME',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'DB_USERNAME' => [
                'label' => 'DATABASE USERNAME',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:200'
            ],

            'DB_PASSWORD' => [
                'label' => 'DATABASE PASSWORD',
                'hint' => 'Please, confirm before you proceed.',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:200'
            ],

            'MAIL_DRIVER' => [
                'label' => 'MAIL DRIVER',
                'hint' => 'By setting this to SENDMAIL this script will attempt to use your server\'s default email system to send emails.',
                'type' => 'select',
                'options' => [
                    'smtp' => 'SMTP',
                    'sendmail' => 'SENDMAIL'
                ],
                'placeholder' => '',
                'rules' => [
                    'required',
                    Rule::in(['smtp', 'sendmail'])
                ]
            ],

            'MAIL_HOST' => [
                'label' => 'MAIL HOST',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'MAIL_PORT'=> [
                'label' => 'MAIL PORT',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'MAIL_USERNAME' => [
                'label' => 'MAIL USERNAME',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'MAIL_PASSWORD' => [
                'label' => 'MAIL PASSWORD',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'MAIL_ENCRYPTION' => [
                'label' => 'MAIL ENCRYPTION',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],

            'MAIL_FROM_ADDRESS' => [
                'label' => 'MAIL FROM ADDRESS',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|email|max:100'
            ],

            'MAIL_FROM_NAME' => [
                'label' => 'MAIL FROM NAME',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:150'
            ],

            'CURRENCY_LOCALE' => [
                'label' => 'CURRENCY LOCALE',
                'hint' => 'To be used for subscription. e.g USD',
                'type' => 'select',
                'options' => get_currencies(),
                'placeholder' => '',
                'rules' => [
                    'required', Rule::in(array_keys(get_currencies()))
                ]
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Installed Middlware Options
    |--------------------------------------------------------------------------
    | Different available status switch configuration for the
    | canInstall middleware located in `CanInstall.php`.
    |
    */
    'installed' => [
        'redirectOptions' => [
            'route' => [
                'name' => '',
                'data' => '',
            ],
            'abort' => [
                'type' => '404',
            ],
            'dump' => [
                'data' => 'Dumping a not found message.',
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Selected Installed Middlware Option
    |--------------------------------------------------------------------------
    | The selected option fo what happens when an installer intance has been
    | Default output is to `/resources/views/error/404.blade.php` if none.
    | The available middleware options include:
    | route, abort, dump, 404, default, ''
    |
    */
    'installedAlreadyAction' => '404',

    /*
    |--------------------------------------------------------------------------
    | Updater Enabled
    |--------------------------------------------------------------------------
    | Can the application run the '/update' route with the migrations.
    | The default option is set to False if none is present.
    | Boolean value
    |
    */
    'updaterEnabled' => true,
];
