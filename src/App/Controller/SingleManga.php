<?php

namespace App\Controller;

use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use App\Entity\Manga;
use App\utils\Session;
use App\Entity\CategorieManga;

class SingleManga
{
    public function __invoke()
    {
        $se = Session::getInstance();
        $se->start();
        $em = EntityManager::getInstance();
        $mangaRepository = $em->getRepository(Manga::class);
        $role = ' ';
        if ($se->has('user')) {
            $role = $se->get('user')->getRole();
        }

        if (isset($_GET['delete']) && $role == 'admin') {
            $em->getRepository(CategoriesManga::class)->deleteMangaCategories(
                $_GET['id']
            );
            $mangaRepository->deleteManga($_GET['id']);

            header('Location: /');
        }

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
