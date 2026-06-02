<?php

return [
  'enabled' => (bool) env('SMS_ENABLED', false),
  'driver' => env('SMS_DRIVER', 'log'),
  'default_country_code' => env('SMS_DEFAULT_COUNTRY_CODE', '237'),

  'africas_talking' => [
    'username' => env('SMS_AT_USERNAME'),
    'api_key' => env('SMS_AT_API_KEY'),
    'from' => env('SMS_AT_FROM', 'SchoolGood'),
  ],

  'twilio' => [
    'sid' => env('SMS_TWILIO_SID'),
    'token' => env('SMS_TWILIO_TOKEN'),
    'from' => env('SMS_TWILIO_FROM'),
  ],
];
