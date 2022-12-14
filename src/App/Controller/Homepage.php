<?php

namespace App\Controller;

use Framework\HttpMethode\Cookie;
use Framework\Response\Response;
use Framework\HttpMethode\Session;

class Homepage
{
    public function __invoke()
    {
        $se = Session::getInstance();
        $se->start();
        $role = ' ';
        if ($se->has('user') || Cookie::has('user')) {
            $user = $se->has('user')
                ? $se->get('user')
                : unserialize(Cookie::get('user'));
            $role = $user->getRole();
            password_verify('admin', $role)
                ? ($role = 'admin')
                : ($role = 'user');

            var_dump($role);
        }
        if (
            ($se->has('user') || Cookie::has('user')) &&
            isset($_GET['disconnect'])
        ) {
            $se->remove('user');
            Cookie::remove('user');
            header('Location: /');
        }

        return new Response('home.html.twig', ['role' => $role]);
    }
}
