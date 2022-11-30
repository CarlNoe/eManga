<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;

use Framework\Response\Response;

class login
{
    public function __invoke()
    {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $username = stripslashes($username);
            $password = stripslashes($password);
            $username = htmlspecialchars($username);
            $password = htmlspecialchars($password);

            $em = EntityManager::getInstance();

            /** @var UserRepository$userRepository */

            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->getUser($username, $password);
            var_dump($user);
        }

        return new Response('login.html.twig');
    }
}
