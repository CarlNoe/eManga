<?php

use App\Controller\AllManga;
use App\Controller\Homepage;
use App\Controller\login;
use App\Controller\Register;
use App\Controller\SingleManga;
use App\Controller\NewManga;
use App\Controller\Cart;
use App\Controller\Paypal;
use App\Controller\OrderList;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route(['GET', 'POST'], '/login', login::class),
        new Route(['GET', 'POST'], '/register', Register::class),
        new Route(['GET', 'POST'], '/manga', SingleManga::class),
        new Route('GET', '/allmanga', AllManga::class),
        new Route(['GET', 'POST'], '/newmanga', NewManga::class),
        new Route(['GET', 'POST'], '/cart', Cart::class),
        new Route(['POST', 'GET'], '/paypal', Paypal::class),
        new Route(['PSOT', 'GET'], '/orderlist', OrderList::class),
    ],
];
