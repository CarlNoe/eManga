<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\CategoriesManga;
use Framework\Response\Response;
use App\Entity\Manga;
use Framework\Doctrine\EntityManager;
use Framework\HttpMethode\Session;
use App\utils\rules\ruleManga;

class NewManga
{
    public function __invoke()
    {
        $se = Session::getInstance();
        $se->start();
        $errors = [];
        $role = ' ';
        if ($se->has('user')) {
            $role = $se->get('user')->getRole();
        }
        if ($role !== 'admin') {
            header('Location: /');
        }
        $categoriesRepository = EntityManager::getRepository(Categories::class);
        $allCategories = $categoriesRepository->findAll();

        if (!empty($_POST)) {
            !isset($_POST['categories']) ? ($_POST['categories'] = []) : ' ';
            $ruleRegister = new ruleManga(true);

            $errors = $ruleRegister->isValidateManga($_POST);
            if (empty($errors)) {
                $manga = new Manga($_POST);

                EntityManager::getRepository(Manga::class)->insertManga($manga);

                EntityManager::getRepository(
                    CategoriesManga::class
                )->insertMangaCategories($_POST['categories'], $manga);

                if (
                    isset($_POST['newCategorie']) &&
                    !empty($_POST['newCategorie'])
                ) {
                    EntityManager::getRepository(
                        Categories::class
                    )->insertManyCategories($_POST['newCategorie']);
                    EntityManager::getRepository(
                        CategoriesManga::class
                    )->insertMangaCategories($_POST['newCategorie'], $manga);
                }
            }
        }
        return new Response('newManga.html.twig', [
            'errors' => $errors,
            'allCategories' => $allCategories,
            'isConnected' => $se->has('user'),
        ]);
    }
}
