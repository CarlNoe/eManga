<?php

use Framework\Config\Config;
use Framework\Paypal\PaypalPayment;

class Paypal
{
    function __invoke()
    {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $paypal = new PaypalPayment(
            Config::get('PAYPAL_CLIENT_ID'),
            Config::get('PAYPAL_SECRET'),
            true
        );
    }
}
