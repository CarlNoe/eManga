<?php

namespace App\Controller;

use Framework\Response\Response;
use App\Entity\User;
use Framework\Doctrine\EntityManager;
use App\Utils\ruleRegister;

class Register
{
    public function __invoke()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            header('Location: /');
        }
        if (isset($_POST)) {
            $ruleRegister = new ruleRegister($_POST);
            $ruleRegister->validate();
            $errors = $ruleRegister->getErrors();

            if (empty($errors)) {
                $em = EntityManager::getInstance();
                $userRepository = $em->getRepository(User::class);
                $userRepository->insertUser($_POST);
            }

            return new Response('register.html.twig', ['errors' => $errors]);
        }
    }
}
