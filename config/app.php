<?php

return [
    'APP_ENV' => 'dev',
    'TEMPLATES_PATH' => dirname(__DIR__) . '/templates',
    'JS_PATH' =>
        'http://' .
        $_SERVER['HTTP_HOST'] .
        str_replace($_SERVER['DOCUMENT_ROOT'], '', '/js/'),
    'PAYPAL_CLIENT_ID' =>
        'AavDx3CXJ6t277KqR7EObmRCnqvxBZN8Vr0uG1fZn0eiJF60-CIykuOmoLKjJgB-2HbTZBZY01qoZOPI',
];
