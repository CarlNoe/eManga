<?php

namespace App\Controller;

use Framework\Response\Response;
use App\Entity\Manga;
use Framework\Doctrine\EntityManager;
use App\Entity\categories;

class AllManga
{
    public function __invoke()
    {
        $categoriesManga = [];
        $allCategories = EntityManager::getRepository(
            categories::class
        )->findAll();
        $mangaRepository = EntityManager::getRepository(Manga::class);
        if (!empty($this->GetUrlIntoArray())) {
            $mangas = $mangaRepository->find10Manga(
                $_GET['page'],
                $this->GetUrlIntoArray()
            );
        } else {
            $mangas = $mangaRepository->find10Manga($_GET['page']);
        }

        foreach ($mangas as $manga) {
            $categoriesManga[
                $manga->getTitle()
            ] = $mangaRepository->findCategories($manga->getId());
        }
        return new Response(
            'allManga.html.twig',
            ['mangas' => $mangas] + ['categoriesManga' => $categoriesManga] + [
                    'page' => $_GET['page'],
                ] + ['allPages' => $mangaRepository->getAllPages()] + [
                    'allCategories' => $allCategories,
                ] + ['cats' => $this->GetUrlIntoArray()]
        );
    }

    public function GetUrlIntoArray()
    {
        $query = explode('&', $_SERVER['QUERY_STRING']);
        $params = [];
        foreach ($query as $param) {
            [$name, $value] = explode('=', $param, 2);
            $params[urldecode($name)][] = urldecode($value);
        }

        return isset($params['cats'])
            ? $params['cats']
            : ($params['cats'] = []);
    }
}
