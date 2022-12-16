<?php

namespace App\Controller;

use Framework\HttpMethode\Cookie;
use Framework\Response\Response;
use Framework\HttpMethode\Session;
use App\Entity\Manga;
use Framework\Doctrine\EntityManager;

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
            password_verify('admin', $role)
                ? ($role = 'admin')
                : ($role = 'user');
        }
        if (
            ($se->has('user') || Cookie::has('user')) &&
            isset($_GET['disconnect'])
        ) {
            $se->remove('user');
            Cookie::remove('user');
            header('Location: /');
        }

        $categories = [];
        $mangaRepository = EntityManager::getRepository(Manga::class);
        $mangas = $mangaRepository->find10Manga($_GET['page'] ?? 1);

        foreach ($mangas as $manga) {
            $categories[$manga->getTitle()] = $mangaRepository->findCategories(
                $manga->getId()
            );
        }
        return new Response('home.html.twig', [
            'role' => $role,
            'mangas' => $mangas,
            'categories' => $categories,
            'page' => $_GET['page'] ?? 1,
            'allPages' => $mangaRepository->getAllPages(),
            'js' => ['addManga.js'],
        ]);
    }
}
