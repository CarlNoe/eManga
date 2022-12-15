<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\HttpMethode\Session;
use Framework\HttpMethode\Cookie;
use App\Paypal\PaypalPayment;
use App\Utils\rules\ruleAddress;
use Framework\Config\Config;
use App\Entity\Order;
use App\Entity\OrderQuantity;
use App\Entity\Manga;
use Framework\Doctrine\EntityManager;

class Cart
{
    public function __invoke()
    {
        header('Access-Control-Allow-Origin: *');
        $session = Session::getInstance();
        $session->start();
        if (!($session->has('user') || Cookie::has('user'))) {
            header('Location: /');
        }

        $order = $this->createOrder();
        if (!empty($_POST)) {
            $ruleAdress = new ruleAddress();
            $errors = $ruleAdress->isValidateAdress($_POST);
            var_dump($errors);
        }
        $paypal = new PaypalPayment(
            Config::get('PAYPAL_CLIENT_ID'),
            Config::get('PAYPAL_CLIENT_SECRET'),
            true
        );

        return new Response('Cart.html.twig', [
            'orders' => $order,
            'js' => ['addManga.js'],
            'paypal' => $paypal->ui($order),
            'isConnected' => $session->has('user'),
        ]);
    }

    public function createOrder(): Order
    {
        $user = Session::has('user')
            ? Session::get('user')
            : unserialize(Cookie::get('user'));
        $mangaRepository = EntityManager::getRepository(Manga::class);
        $order = new Order($user, 0, 0);
        $co = Cookie::get('cart');
        $cart = json_decode($co, true);
        foreach ($cart as $key => $quantity) {
            $key = str_replace('id', '', $key);
            $manga = $mangaRepository->find($key);
            $order->addOrderQuantity(
                new OrderQuantity($order, $manga, $quantity, $manga->getPrice())
            );
            $order->addOrderSubTotal($manga->getPrice() * $quantity);
            $order->addShippingCost(1);
        }
        $order->addShippingCost(1);

        return $order;
    }
}
