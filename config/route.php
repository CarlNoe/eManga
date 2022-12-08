<?php

use App\Controller\AllManga;
use App\Controller\Homepage;
use App\Controller\login;
use App\Controller\Register;
use App\Controller\SingleManga;
use App\Controller\NewManga;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route(['GET', 'POST'], '/login', login::class),
        new Route(['GET', 'POST'], '/register', Register::class),
        new Route(['GET', 'POST'], '/manga', SingleManga::class),
        new Route('GET', '/allmanga', AllManga::class),
        new Route(['GET', 'POST'], '/newmanga', NewManga::class),
    ],
];
