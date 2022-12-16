<?php

namespace App\Controller;

use Framework\HttpMethode\Cookie;
use Framework\Response\Response;
use Framework\HttpMethode\Session;
use App\Entity\User;
use Framework\Doctrine\EntityManager;

class ResumeOrder
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
            $user->getRole() === 'admin' ? ($role = 'admin') : ($role = 'user');
        }
        if (
            ($se->has('user') || Cookie::has('user')) &&
            isset($_GET['disconnect'])
        ) {
            $se->remove('user');
            Cookie::remove('user');
            header('Location: /');
        }
        if ($role === 'admin') {
            $orders = EntityManager::getRepository(
                User::class
            )->getAllResumeOrders($user->getId());
        } else {
            $orders = EntityManager::getRepository(
                User::class
            )->getResumeOrders($user->getId());
        }
        return new Response('resumeOrder.html.twig', [
            'orders' => $orders,
        ]);
    }
}
