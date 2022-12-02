<?php

use App\Controller\Homepage;
use App\Controller\login;
use App\Controller\Register;

use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route(['GET', 'POST'], '/login', login::class),
        new Route(['GET', 'POST'], '/register', Register::class),
    ],
];
