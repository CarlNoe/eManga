<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\HttpMethode\Session;
use Framework\HttpMethode\Cookie;
use Framework\Paypal\PaypalPayment;
use App\Utils\rules\ruleAddress;
use App\utils\DisplayCart;
use Framework\Config\Config;

class Cart
{
    public function __invoke()
    {
        header('Access-Control-Allow-Origin: *');
        $session = Session::getInstance();
        $session->start();
        if ($session->has('user') || Cookie::has('user')) {
            $user = $session->has('user')
                ? $session->get('user')
                : unserialize(Cookie::get('user'));
        } else {
            header('Location: /');
        }

        $allData = (new DisplayCart())();
        if (!empty($_POST)) {
            $ruleAdress = new ruleAddress();
            $errors = $ruleAdress->isValidateAdress($_POST);
            var_dump($errors);
        }
        $paypal = new PaypalPayment(
            Config::get('PAYPAL_CLIENT_ID'),
            Config::get('PAYPAL_SECRET'),
            true
        );

        return new Response('buyCart.html.twig', [
            'mangas' => $allData['mangas'],
            'total' => $allData['total'],
            'shippingCost' => $allData['shippingCost'],
            'js' => ['addManga.js'],
            'paypal' => $paypal->ui($allData),
        ]);
    }
}
