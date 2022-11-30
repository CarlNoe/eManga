<?php

use App\Controller\Homepage;
use App\Controller\login;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route(['GET', 'POST'], '/login', login::class),
    ],
];
