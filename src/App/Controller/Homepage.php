<?php

namespace App\Controller;

use Framework\Response\Response;

class Homepage
{
    public function __invoke()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            var_dump('connecté');
        }
        if (isset($_SESSION['user']) && isset($_GET['disconnect'])) {
            unset($_SESSION['user']);
            header('Location: /');
        }
        return new Response('home.html.twig');
    }
}
