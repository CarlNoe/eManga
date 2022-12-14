<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use App\Entity\Manga;
use Framework\HttpMethode\Cookie;
use App\utils\DisplayCart;

class Cart
{
    public function __invoke()
    {
        $allData = (new DisplayCart())();
        return new Response('displayCart.html.twig', [
            'mangas' => $allData['mangas'],
            'total' => $allData['total'],
            'shippingCost' => $allData['shippingCost'],
            'js' => ['addManga.js'],
        ]);
    }
}
