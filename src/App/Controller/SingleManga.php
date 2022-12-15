<?php

namespace App\Controller;

use App\Entity\Categories;
use Framework\Response\Response;
use Framework\Doctrine\EntityManager;
use App\Entity\Manga;
use Framework\HttpMethode\Post;
use Framework\HttpMethode\Session;
use App\utils\rules\ruleManga;
use App\Entity\CategoriesManga;

class SingleManga
{
    public function __invoke()
    {
        $se = Session::getInstance();
        $post = Post::getInstance();
        $se->start();
        $mangaRepository = EntityManager::getRepository(Manga::class);
        $role = ' ';
        if ($se->has('user')) {
            $role = $se->get('user')->getRole();
        }

        //Si l'utilisateur est admin et qu'il a cliqué sur le bouton supprimer
        if (isset($_GET['delete']) && $role == 'admin') {
            EntityManager::getRepository(
                CategoriesManga::class
            )->deleteMangaCategories($_GET['id']);

            $mangaRepository->deleteManga($_GET['id']);

            header('Location: /');
        }
        //Si l'utilisateur est admin et qu'il a cliqué sur le bouton modifier
        isset($_GET['id']) ? $se->set('id_manga', $_GET['id']) : ' ';
        $manga = $mangaRepository->findOneById($se->get('id_manga'));
        $categories = $mangaRepository->findCategories($manga->getId());

        $allCategories = EntityManager::getRepository(
            Categories::class
        )->findAll();

        if ($post->hasPost()) {
            $ruleRegister = new ruleManga(false);
            $errors = $ruleRegister->isValidateManga($_POST);
            var_dump($errors);
            if (empty($errors)) {
                EntityManager::getRepository(Manga::class)->updateManga(
                    $_POST,
                    $manga->getId()
                );

                EntityManager::getRepository(
                    CategoriesManga::class
                )->UpdateMangaCategories($_POST['categories'], $manga);
            }
        }
        return new Response('singleManga.html.twig', [
            'manga' => $manga,
            'categories' => $categories,
            'role' => $role,
            'allCategories' => $allCategories,
            'js' => ['addManga.js'],
        ]);
    }
}
