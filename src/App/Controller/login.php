<?php

namespace App\Controller;

use App\Entity\User;
use Framework\Response\Response;
use Framework\Doctrine\EntityManager;

class login
{
    public function __invoke()
    {
        session_start();
        $errors = [];
        if (isset($_SESSION['user'])) {
            header('Location: /');
        }
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $em = EntityManager::getInstance();
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->getUser(
                htmlspecialchars($_POST['email']),
                htmlspecialchars($_POST['password'])
            );
            if ($user == null) {
                $errors = 'Email ou mot de passe incorrect';
            } else {
                if ($_POST['remember_me'] == '1') {
                    session_start();
                    $_SESSION['user'] = $user;
                }
                header('Location: /');
            }
        }
        return new Response('login.html.twig', ['errors' => $errors]);
    }
}
