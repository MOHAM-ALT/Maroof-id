<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio Account SID
    |--------------------------------------------------------------------------
    |
    | Your Twilio Account SID from https://www.twilio.com/console
    |
    */

    'account_sid' => env('TWILIO_ACCOUNT_SID'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Auth Token
    |--------------------------------------------------------------------------
    |
    | Your Twilio Auth Token from https://www.twilio.com/console
    |
    */

    'auth_token' => env('TWILIO_AUTH_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | From Number
    |--------------------------------------------------------------------------
    |
    | The phone number registered with Twilio that SMS should be sent from
    |
    */

    'from' => env('TWILIO_FROM'),

    /*
    |--------------------------------------------------------------------------
    | Alphanumeric Sender
    |--------------------------------------------------------------------------
    |
    | The alphanumeric sender ID (if supported in your country)
    |
    */

    'alphanumeric_sender' => env('TWILIO_ALPHANUMERIC_SENDER'),
    
    /*
    |--------------------------------------------------------------------------
    | WhatsApp From Number
    |--------------------------------------------------------------------------
    |
    | The WhatsApp-enabled phone number registered with Twilio
    | Format: whatsapp:+14155238886
    |
    */

    'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),
];
