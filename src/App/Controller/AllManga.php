<?php

namespace App\Controller;

use Framework\Response\Response;
use App\Entity\Manga;
use Framework\Doctrine\EntityManager;

class AllManga
{
    public function __invoke()
    {
        $categories = [];
        $mangaRepository = EntityManager::getRepository(Manga::class);
        var_dump(intval($_GET['page']));
        $mangas = $mangaRepository->find10Manga($_GET['page']);

        foreach ($mangas as $manga) {
            $categories[$manga->getTitle()] = $mangaRepository->findCategories(
                $manga->getId()
            );
        }
        return new Response(
            'allManga.html.twig',
            ['mangas' => $mangas] + ['categories' => $categories] + [
                    'page' => $_GET['page'],
                ] + ['allPages' => $mangaRepository->getAllPages()]
        );
    }
}
