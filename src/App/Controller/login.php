<?php
namespace App\Controller;
use Framework\Response\Response;

class login
{
    public function __invoke()
    {
        return new Response('login.html.twig');
    }
}
