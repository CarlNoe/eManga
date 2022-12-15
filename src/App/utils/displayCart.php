<?php

namespace App\utils;

use Framework\Doctrine\EntityManager;
use App\Entity\Manga;
use Framework\HttpMethode\Cookie;

class DisplayCart
{
    public function __invoke()
    {
        $co = Cookie::get('cart');
        $cart = json_decode($co, true);
        $mangaRepository = EntityManager::getRepository(Manga::class);
        $mangas = [];
        $total = 0;
        $shippingCost = 0;
        foreach ($cart as $key => $value) {
            $key = str_replace('id', '', $key);
            $manga = $mangaRepository->find($key);
            $mangas[] = [$manga, $value];
            $total += $manga->getPrice() * $value;
            $shippingCost += $value;
        }

        return [
            'mangas' => $mangas,
            'total' => $total,
            'shippingCost' => $shippingCost + 1,
        ];
    }
}
