<?php

return [
  'school_name' => env('SCHOOL_NAME', 'SchoolGood'),

  'orange' => [
    'enabled' => (bool) env('PAYMENTS_ORANGE_ENABLED', false),
    'client_id' => env('PAYMENTS_ORANGE_CLIENT_ID'),
    'client_secret' => env('PAYMENTS_ORANGE_CLIENT_SECRET'),
    'merchant_key' => env('PAYMENTS_ORANGE_MERCHANT_KEY'),
    'oauth_url' => env('PAYMENTS_ORANGE_OAUTH_URL', 'https://api.orange.com/oauth/v3/token'),
    'payment_url' => env('PAYMENTS_ORANGE_PAYMENT_URL', 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment'),
    'currency' => env('PAYMENTS_ORANGE_CURRENCY', 'XAF'),
    'webhook_secret' => env('PAYMENTS_ORANGE_WEBHOOK_SECRET'),
    'return_url' => env('PAYMENTS_ORANGE_RETURN_URL'),
    'cancel_url' => env('PAYMENTS_ORANGE_CANCEL_URL'),
    'notif_url' => env('PAYMENTS_ORANGE_NOTIF_URL'),
  ],

  'mtn' => [
    'enabled' => (bool) env('PAYMENTS_MTN_ENABLED', false),
    'subscription_key' => env('PAYMENTS_MTN_SUBSCRIPTION_KEY'),
    'api_user' => env('PAYMENTS_MTN_API_USER'),
    'api_key' => env('PAYMENTS_MTN_API_KEY'),
    'target_environment' => env('PAYMENTS_MTN_TARGET_ENV', 'sandbox'),
    'base_url' => env('PAYMENTS_MTN_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
    'currency' => env('PAYMENTS_MTN_CURRENCY', 'XAF'),
    'webhook_secret' => env('PAYMENTS_MTN_WEBHOOK_SECRET'),
    'callback_url' => env('PAYMENTS_MTN_CALLBACK_URL'),
  ],

  'simulate_webhooks' => (bool) env('PAYMENTS_SIMULATE_WEBHOOKS', true),
];
