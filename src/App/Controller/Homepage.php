<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\HttpMethode\Session;
use App\Entity\Manga;
use Framework\Doctrine\EntityManager;

class Homepage
{
    public function __invoke()
    {
        $role = 'user';
        $se = Session::getInstance();
        $se->start();
        if ($se->has('user')) {
            var_dump('connecté');
            $se->get('user')->getRole() == 'admin' ? ($role = 'admin') : null;
        }
        if ($se->has('user') && isset($_GET['disconnect'])) {
            $se->remove('user');
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
        ]);
    }
}
