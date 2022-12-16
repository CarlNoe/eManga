<?php

namespace App\Controller;

use App\Paypal\PaypalPayment;
use Framework\Response\Response;
use Framework\Config\Config;

class Paypal
{
    function __invoke()
    {
        session_start();
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $paypal = new PaypalPayment(
            Config::get('PAYPAL_CLIENT_ID'),
            Config::get('PAYPAL_CLIENT_SECRET'),
            true
        );
        $cart = $_SESSION['cart'];
        $paypal->handle($cart, $data['authorizationId']);
    }
}
