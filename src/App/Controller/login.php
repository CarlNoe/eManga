<?php

namespace App\Controller;

use App\Entity\User;
use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use Framework\HttpMethode\Cookie;
use Framework\HttpMethode\Session;

class login
{
    public function __invoke()
    {
        $errors = [];
        Session::start();
        if (Session::get('user') != null || Cookie::get('user') != null) {
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
                $user->setRole(
                    password_hash($user->getRole(), PASSWORD_DEFAULT)
                );
                if ($_POST['remember_me'] == '1') {
                    Cookie::set(
                        'user',
                        serialize($user),
                        time() + 365 * 24 * 3600
                    );
                }
                Session::set('user', $user);
                header('Location: /');
            }
        }
        return new Response('login.html.twig', ['errors' => $errors]);
    }
}
