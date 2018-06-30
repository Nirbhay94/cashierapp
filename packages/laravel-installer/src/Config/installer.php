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

    'name' => 'iTranslate - A Powerful Tool that Automates Translation of Your Laravel Applications',

    'link' => '',

    'documentation' => 'http://products.oluwatosin.me/itranslate/docs',

    'thumbnail' => 'http://oluwatosin.me/cdn/images/itranslate_thumbnail.png',

    'market_place' => 'CodeCanyon',

    'author' => 'HolluwaTosin360',

    'avatar' => 'http://oluwatosin.me/avatar.jpg',

    'portfolio' => 'https://codecanyon.net/user/holluwatosin360/portfolio',

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
            'zip'
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
                'label' => 'Application Name',
                'hint' => 'This is used for sending emails to end users',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'APP_TIMEZONE' => [
                'label' => 'Application Timezone',
                'hint' => '',
                'type' => 'select',
                'options' => array_combine_single(get_php_timezones()),
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'APP_URL' => [
                'label' => 'Application URL',
                'hint' => 'Set your website link. Kindly confirm before you proceed.',
                'type' => 'text',
                'placeholder' => '(e.g http:// or https://example.com/)',
                'rules' => 'required|url'
            ],
            'DB_HOST' => [
                'label' => 'Database Host',
                'hint' => 'Leave as localhost if your database is on the same server as this script.',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'DB_PORT' => [
                'label' => 'Database Port',
                'hint' => 'This is usually 3306. Please specify if it is otherwise.',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|numeric'
            ],
            'DB_DATABASE' => [
                'label' => 'Database Name',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'DB_USERNAME' => [
                'label' => 'Database Username',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'DB_PASSWORD' => [
                'label' => 'Database Password',
                'hint' => 'Kindly confirm before you proceed.',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'MAIL_DRIVER' => [
                'label' => 'Mail Driver',
                'hint' => 'By setting this to SENDMAIL this script will attempt to use your server\'s default email system to send emails.',
                'type' => 'select',
                'options' => [
                    'smtp' => 'SMTP',
                    'sendmail' => 'SENDMAIL'
                ],
                'placeholder' => '',
                'rules' => ['required', Rule::in(['smtp', 'sendmail'])]
            ],
            'MAIL_HOST' => [
                'label' => 'Mail Host',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'MAIL_PORT'=> [
                'label' => 'Mail Port',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'MAIL_USERNAME' => [
                'label' => 'Mail Username',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'MAIL_PASSWORD' => [
                'label' => 'Mail Password',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
            ],
            'MAIL_ENCRYPTION' => [
                'label' => 'Mail Encryption',
                'hint' => '',
                'type' => 'text',
                'placeholder' => '',
                'rules' => 'required|string|max:50'
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
