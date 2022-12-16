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
use Doctrine\ORM\Mapping\Entity;
use Framework\Doctrine\EntityManager;

class Cart
{
    public function __invoke()
    {
        $session = Session::getInstance();
        $session->start();
        $user = EntityManager::getRepository(User::class)->find(
            $session->get('user')->getId()
        );
        $adresseRepository = EntityManager::getRepository(Address::class);
        $AdressesOfUser = $adresseRepository->getAdressesOfUser($user->getId());

        if (!($session->has('user') || Cookie::has('user'))) {
            header('Location: /');
        }

        if (!empty($_POST)) {
            $ruleAdress = new ruleAddress();
            $errors = $ruleAdress->isValidateAdress($_POST);
            if (empty($errors)) {
                $address = new Address($_POST);
                $user->addAddress($address);
                $adresseRepository->insertAddress($address);
            }
        }
        header('Access-Control-Allow-Origin: *');

        $order = $this->createOrder();

        $paypal = new PaypalPayment(
            Config::get('PAYPAL_CLIENT_ID'),
            Config::get('PAYPAL_CLIENT_SECRET'),
            true
        );

        return new Response('Cart.html.twig', [
            'onlyAddresses' => $this->getOnlyAddresses($AdressesOfUser),
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

    function getOnlyAddresses($AdressesOfUser)
    {
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
