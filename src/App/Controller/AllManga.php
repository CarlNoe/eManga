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
        $em = EntityManager::getInstance();
        $mangaRepository = $em->getRepository(Manga::class);
        $mangas = $mangaRepository->find10Manga();

        foreach ($mangas as $manga) {
            $categories[$manga->getTitle()] = $mangaRepository->findCategories(
                $manga->getId()
            );
        }
        return new Response(
            'allManga.html.twig',
            ['mangas' => $mangas] + ['categories' => $categories]
        );
    }
}
