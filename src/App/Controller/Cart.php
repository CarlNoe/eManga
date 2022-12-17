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
use App\Entity\User;
use App\Entity\UsersAddresses;
use Framework\Doctrine\EntityManager;

class Cart
{
    public function __invoke()
    {
        header('Access-Control-Allow-Origin: *');
        $session = Session::getInstance();
        $session->start();
        $user = EntityManager::getRepository(User::class)->find(
            $session->get('user')->getId()
        );
        $adresseRepository = EntityManager::getRepository(
            UsersAddresses::class
        );
        $AdressesOfUser = $adresseRepository->findAddress($user->getId());
        if (!($session->has('user') || Cookie::has('user'))) {
            header('Location: /');
        }

        if (!empty($_POST)) {
            $ruleAdress = new ruleAddress();
            $errors = $ruleAdress->isValidateAdress($_POST);
            if (empty($errors)) {
                $address = new Address($_POST);
                EntityManager::getRepository(Address::class)->insertAddress(
                    $address
                );
                EntityManager::getRepository(
                    UsersAddresses::class
                )->insertUserAddress($user, $address);
            }
        }

        $order = $this->createOrder();

        $paypal = new PaypalPayment(
            Config::get('PAYPAL_CLIENT_ID'),
            Config::get('PAYPAL_CLIENT_SECRET'),
            true
        );

        return new Response('Cart.html.twig', [
            'errors' => $errors ?? [],
            'onlyAddresses' => $this->getOnlyAddresses($AdressesOfUser),
            'orders' => $order,
            'js' => ['addManga.js', 'removeManga.js'],
            'paypal' => $paypal->ui($order),
            'isConnected' => $session->has('user'),
        ]);
    }

    public function createOrder(): Order
    {
        $address = null;
        $user = Session::has('user')
            ? Session::get('user')
            : unserialize(Cookie::get('user'));
        $mangaRepository = EntityManager::getRepository(Manga::class);
        if (!empty($_GET)) {
            $address = EntityManager::getRepository(Address::class)->find(
                $_GET['address']
            );
        }
        $order = new Order($user, 0, 0, $address);

        $cart = Cookie::get('cart');
        if ($cart !== null) {
            $cart = json_decode($cart, true);
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
        } else {
            $order->addShippingCost(1);
        }
        return $order;
    }

    function getOnlyAddresses($AdressesOfUser)
    {
        $onlyAddresses = [];
        $i = 0;
        foreach ($AdressesOfUser as $value) {
            $onlyAddresses[$i]['id'] = $value->getId();
            $onlyAddresses[$i]['street'] = $value->getStreet();
            $onlyAddresses[$i]['city'] = $value->getCity();
            $onlyAddresses[$i]['zipcode'] = $value->getZipCode();
            $onlyAddresses[$i]['country'] = $value->getCountry();
            $i++;
        }

        return $onlyAddresses;
    }
}
