<?php

namespace App\Controller;

use Framework\Response\Response;
use App\utils\Session;
use App\Entity\User;

class Homepage
{
    public function __invoke()
    {
        $role = 'user';
        $se = Session::getInstance();
        $se->start();
        if ($se->has('user')) {
            var_dump('connecté');
            $se->get('user')->getRole() == 'admin' ? ($role = 'admin') : null;
        }
        if ($se->has('user') && isset($_GET['disconnect'])) {
            $se->remove('user');
            header('Location: /');
        }

        return new Response('home.html.twig', ['role' => $role]);
    }
}
