<?php

namespace App\Controller;

use Framework\Response\Response;
use App\Entity\User;
use Framework\Doctrine\EntityManager;
use App\Utils\rules\ruleRegister;
use App\Utils\Session;

class Register
{
    public function __invoke()
    {
        $se = Session::getInstance();
        $se->start();
        $errors = [];
        if (isset($_SESSION['user'])) {
            header('Location: /');
        }
        if (isset($_POST) && !empty($_POST)) {
            $ruleRegister = new ruleRegister($_POST);
            $ruleRegister->validate();
            $errors = $ruleRegister->getErrors();

            if (empty($errors)) {
                $em = EntityManager::getInstance();
                $userRepository = $em->getRepository(User::class);
                $userRepository->insertUser($_POST);
                header('Location: /login');
            }
        }

        return new Response('register.html.twig', ['errors' => $errors]);
    }
}
