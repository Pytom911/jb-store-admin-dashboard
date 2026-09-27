<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Admin
    |--------------------------------------------------------------------------
    |
    | Number customers are sent to when they enquire about an account. Stored
    | in the environment so it never has to be changed in a view or controller.
    | Use the international format without "+" or spaces, e.g. 6281234567890.
    |
    */

    'whatsapp_number' => env('WHATSAPP_ADMIN_NUMBER', '6281234567890'),

    'accounts_per_page' => 12,

];
