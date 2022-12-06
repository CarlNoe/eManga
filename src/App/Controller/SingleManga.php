<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use App\Entity\Manga;
use App\utils\Session;
use App\Entity\CategoriesManga;

class SingleManga
{
    public function __invoke()
    {
        $se = Session::getInstance();
        $se->start();
        $role = ' ';
        if ($se->has('user')) {
            $role = $se->get('user')->getRole();
        }

        if (isset($_GET['delete']) && $role == 'admin') {
            $em = EntityManager::getInstance();

            $categoriesMangaRepository = $em->getRepository(
                CategoriesManga::class
            );
            $categoriesMangaRepository->deleteMangaCategories($_GET['id']);
            $mangaRepository = $em->getRepository(Manga::class);
            $mangaRepository->deleteManga($_GET['id']);

            header('Location: /');
        }

        $em = EntityManager::getInstance();
        $mangaRepository = $em->getRepository(Manga::class);
        $manga = $mangaRepository->findOneById($_GET['id']);
        $categories = $mangaRepository->findCategories($manga->getId());
        return new Response(
            'singleManga.html.twig',
            ['manga' => $manga] + ['categories' => $categories] + [
                    'role' => $role,
                ]
        );
    }
}
