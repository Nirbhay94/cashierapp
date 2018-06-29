<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for sending mails to users
    | You are free to modify these language lines according to your
    | application's requirements.
    |
    */

    'auth' => [
        'welcome' => [
            'subject' => 'Thank you for registering an account with us!',
            'greeting' => 'Hi :name',
            'headnote' => 'Its a pleasure to have you register with us!',
            'body' => 'Take you time to surf through our platform. If you have any question, do let us know through our support center.',
            'button' => 'Get Started',
            'footnote' => 'Note: Your password is :password',
            'regards' => 'Warm Regards',
        ],
        
        'goodbye' => [
            'subject' => 'Your Account Has Been Queued For Delete!',
            'greeting' => 'Hi :name',
            'body' => 'Your request for account deletion was successful.',
            'regards' => 'Warm Regards',
        ],
    ],

    'subscription' => [
        'expired' => [
            'greeting' => 'Hello :name',
            'headnote' => 'We were unable to renew your subscription.',
            'body' => 'An attempt to renew your expired subscription failed and all services have been stopped.',
            'button' => 'Summary',
            'footnote' => 'Please take a look as soon as possible. For enquiries or complaints please reach us on our support center.',
            'regards' => 'Warm Regards',
        ]
    ],
    
    'customer' => [
        'invoice' => [
            'new' => [
                'subject' => 'You have a pending invoice!',
                'greeting' => 'Hi :name',
                'headnote' => 'Check below for more details about your invoice',
                'button' => 'View',
                'footnote' => 'You can always click the link to generate an updated invoice.',
                'regards' => 'Regards',
            ],

            'paid' => [
                'subject' => 'You have a paid invoice!',
                'greeting' => 'Hi :name',
                'headnote' => 'Check below for more details about your invoice',
                'button' => 'View',
                'footnote' => 'You may also check your payment status online.',
                'regards' => 'Regards',
            ]
        ],

        'pos' => [
            'checkout' => [
                'subject' => 'Your transaction checkout was successful!',
                'greeting' => 'Hi :name',
                'headnote' => 'Below is a copy of your POS transaction',
                'button' => 'Print',
                'footnote' => 'Thank you for purchasing!',
                'regards' => 'Regards',
            ]
        ]
    ],

    'preview' => [
        'greeting' => 'Hi JohnDoe,',
        'headnote' => 'This is a sample of headnote ',
        'body' => 'This is a sample of a very very very long body!',
        'button' => 'Button Text',
        'footnote' => 'This is a sample of a foot note',
    ]
];