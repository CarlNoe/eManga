<?php

namespace App\Controller;

use Framework\Response\Response;
use App\Entity\User;
use Framework\Doctrine\EntityManager;
use App\Utils\rules\ruleRegister;
use Framework\HttpMethode\Session;

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
            $ruleRegister = new ruleRegister();
            $errors = $ruleRegister->isValidateRegister($_POST);
            var_dump($errors);
            if (empty($errors)) {
                $userRepository = EntityManager::getRepository(User::class);
                $userRepository->insertUser($_POST);
            }
        }

        return new Response('register.html.twig', ['errors' => $errors]);
    }
}
