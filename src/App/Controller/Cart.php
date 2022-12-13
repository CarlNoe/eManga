<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use App\Entity\Manga;
use Framework\HttpMethode\Cookie;

class Cart
{
    public function __invoke()
    {
        $co = Cookie::get('cart');
        $cart = json_decode($co, true);
        $mangaRepository = EntityManager::getRepository(Manga::class);
        $mangas = [];
        $total = 0;
        foreach ($cart as $key => $value) {
            $key = str_replace('id', '', $key);
            $manga = $mangaRepository->find($key);
            $manga->setStock($value);
            $mangas[] = $manga;
            $total += $manga->getPrice() * $value;
        }

        return new Response('displayCart.html.twig', [
            'mangas' => $mangas,
            'total' => $total,
        ]);
    }
}
