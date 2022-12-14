<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use App\Utils\rules\ruleAddress;
use App\Entity\Manga;
use Framework\HttpMethode\Cookie;
use App\utils\DisplayCart;
use Framework\HttpMethode\Session;
use Framework\Paypal\PaypalPayment;

class BuyCart
{
    public function __invoke()
    {
        header('Access-Control-Allow-Origin: *');
        $session = Session::getInstance();
        $session->start();
        $session->has('user') ? ($user = $session->get('user')) : '';
        Cookie::has('user')
            ? ($user = unserialize(Cookie::get('user')))
            : header('Location: /');
        $allData = (new DisplayCart())();
        if (!empty($_POST)) {
            $ruleAdress = new ruleAddress();
            $errors = $ruleAdress->isValidateAdress($_POST);
            var_dump($errors);
        }
        $paypal = new PaypalPayment();

        return new Response('buyCart.html.twig', [
            'mangas' => $allData['mangas'],
            'total' => $allData['total'],
            'shippingCost' => $allData['shippingCost'],
            'js' => ['addManga.js'],
            'paypal' => $paypal->ui($allData['total'], $allData),
        ]);
    }
}
