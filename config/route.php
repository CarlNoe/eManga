<?php

use App\Controller\Homepage;
use App\Controller\login;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', login::class),
        new Route('GET', '/register', register::class),
    ],
];
