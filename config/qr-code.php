<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default QR Code Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for QR code generation
    |
    */

    'default_size' => 300,
    'default_margin' => 10,
    'default_format' => 'png',
    'default_encoding' => 'UTF-8',
    
    /*
    |--------------------------------------------------------------------------
    | Card QR Code Settings
    |--------------------------------------------------------------------------
    |
    | Settings specific for business card QR codes
    |
    */

    'card_qr' => [
        'size' => 400,
        'margin' => 20,
        'logo' => public_path('images/maroof-logo.png'),
        'logo_size' => 80,
    ],
];
