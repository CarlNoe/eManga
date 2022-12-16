<?php

namespace App\Controller;

use App\Entity\Address;
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
        $session = Session::getInstance();
        $session->start();

        $AdressesOfUser = EntityManager::getRepository(
            Address::class
        )->getAdressesOfUser($session->get('user')->getId());
        foreach ($AdressesOfUser as $value) {
            $value->setUsers($session->get('user')->getId());
        }

        if (!($session->has('user') || Cookie::has('user'))) {
            header('Location: /');
        }

        if (!empty($_POST)) {
            $ruleAdress = new ruleAddress();
            $errors = $ruleAdress->isValidateAdress($_POST);
            var_dump($errors);
            if (empty($errors)) {
                $address = new Address($_POST);
                EntityManager::getRepository(Address::class)->insertAddress(
                    $address
                );
            }
        }

        var_dump($AdressesOfUser);
        header('Access-Control-Allow-Origin: *');

        $order = $this->createOrder();

        $paypal = new PaypalPayment(
            Config::get('PAYPAL_CLIENT_ID'),
            Config::get('PAYPAL_CLIENT_SECRET'),
            true
        );

        return new Response('Cart.html.twig', [
            '$AdressesOfUser' => $AdressesOfUser,
            'orders' => $order,
            'js' => ['addManga.js'],
            'paypal' => $paypal->ui($order),
        ]);
    }

    public function createOrder(): Order
    {
        $user = Session::has('user')
            ? Session::get('user')
            : unserialize(Cookie::get('user'));
        $mangaRepository = EntityManager::getRepository(Manga::class);
        $order = new Order($user, 0, 0);
        $cart = json_decode(Cookie::get('cart'), true);
        if ($cart !== null) {
            foreach ($cart as $key => $quantity) {
                $key = str_replace('id', '', $key);
                $manga = $mangaRepository->find($key);
                $order->addOrderQuantity(
                    new OrderQuantity(
                        $order,
                        $manga,
                        $quantity,
                        $manga->getPrice()
                    )
                );
                $order->addOrderSubTotal($manga->getPrice() * $quantity);
                $order->addShippingCost(1);
            }
            $order->addShippingCost(1);
        }
        return $order;
    }
}
