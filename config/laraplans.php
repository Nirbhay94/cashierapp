<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Positive Words
    |--------------------------------------------------------------------------
    |
    | These words indicates "true" and are used to check if a particular plan
    | feature is enabled.
    |
    */
    'positive_words' => [
        'yes',
        'true',
        'y',
    ],

    'negative_words' => [
        'no',
        'false',
        'n'
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you want to use your own models you will want to update the following
    | array to make sure Laraplans use them.
    |
    */
    'models' => [
        'plan' => 'Gerardojbaez\Laraplans\Models\Plan',
        'plan_feature' => 'Gerardojbaez\Laraplans\Models\PlanFeature',
        'plan_subscription' => 'Gerardojbaez\Laraplans\Models\PlanSubscription',
        'plan_subscription_usage' => 'Gerardojbaez\Laraplans\Models\PlanSubscriptionUsage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | The heart of this package. Here you will specify all features available
    | for your plans.
    |
    */
    'features' => [
        'products' => [
            'label' => 'Total Products',
            'value' => 100,
            'sort_order' => 0,
            'type' => 'quantity',
            'resettable_interval' => 'month',
            'resettable_count' => 0
        ],

        'pos_terminal' => [
            'label' => 'POS Terminal',
            'value' => 'true',
            'sort_order' => 1,
            'type' => 'boolean',
            'resettable_interval' => 'month',
            'resettable_count' => 0
        ],

        'customers' => [
            'label' => 'Total Customers',
            'value' => 10,
            'sort_order' => 2,
            'type' => 'quantity',
            'resettable_interval' => 'month',
            'resettable_count' => 0
        ],

        'send_broadcast' => [
            'label' => 'Send Broadcast',
            'value' => 'true',
            'sort_order' => 3,
            'type' => 'boolean',
            'resettable_interval' => 'month',
            'resettable_count' => 0
        ],

        'issue_invoice' => [
            'label' => 'Issue Invoice',
            'value' => 'true',
            'sort_order' => 4,
            'type' => 'boolean',
            'resettable_interval' => 'month',
            'resettable_count' => 0
        ],

        'import_data' => [
            'label' => 'Data Import Support',
            'value' => 'true',
            'sort_order' => 5,
            'type' => 'boolean',
            'resettable_interval' => 'month',
            'resettable_count' => 0
        ],
    ],
];
