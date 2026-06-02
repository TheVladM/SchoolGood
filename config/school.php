<?php

return [
    'payment_accounts' => [
        'orange_money' => [
            'label' => 'Orange Money',
            'number' => env('SCHOOL_ORANGE_MONEY_NUMBER', ''),
            'name' => env('SCHOOL_ORANGE_MONEY_NAME', 'SchoolGood'),
        ],
        'mtn_momo' => [
            'label' => 'MTN MoMo',
            'number' => env('SCHOOL_MTN_MOMO_NUMBER', ''),
            'name' => env('SCHOOL_MTN_MOMO_NAME', 'SchoolGood'),
        ],
        'bank' => [
            'label' => 'Banque',
            'account' => env('SCHOOL_BANK_ACCOUNT', ''),
            'bank_name' => env('SCHOOL_BANK_NAME', ''),
        ],
    ],
];
