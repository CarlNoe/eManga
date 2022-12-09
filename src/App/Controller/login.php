<?php

namespace App\Controller;

use App\Entity\User;
use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use Framework\HttpMethode\Session;

class login
{
    public function __invoke()
    {
        $se = Session::getInstance();
        $se->start();
        $errors = [];
        if ($se->has('user')) {
            header('Location: /');
        }
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $userRepository = EntityManager::getRepository(User::class);
            $user = $userRepository->getUser(
                strip_tags(htmlspecialchars($_POST['email'])),
                strip_tags(htmlspecialchars($_POST['password']))
            );
            if ($user == null) {
                $errors = 'Email ou mot de passe incorrect';
            } else {
                if ($_POST['remember_me'] == '1') {
                    $_SESSION['user'] = $user;
                }
                header('Location: /');
            }
        }
        return new Response('login.html.twig', ['errors' => $errors]);
    }
}
