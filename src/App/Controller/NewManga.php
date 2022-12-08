<?php

namespace App\Controller;

use App\Entity\Categories;
use Framework\Response\Response;
use App\Entity\Manga;
use Framework\Doctrine\EntityManager;
use App\utils\Session;
use App\Entity\CategoriesManga;
use App\utils\rules\ruleManga;
use Doctrine\ORM\NonUniqueResultException;

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
        if ($role == 'admin') {
            $em = EntityManager::getInstance();
            $mangaRepository = $em->getRepository(Manga::class);
            $categoriesRepository = $em->getRepository(Categories::class);
            $mangaCategoriesRepository = $em->getRepository(
                CategoriesManga::class
            );
            $allCategories = $categoriesRepository->findAll();
            if (isset($_POST) && !empty($_POST)) {
                $ruleInsertManga = new ruleManga($_POST);
                $ruleInsertManga->validate();
                $errors = $ruleInsertManga->getErrors();
                try {
                    $manga = $mangaRepository->findOneByTitle($_POST['title']);
                } catch (NonUniqueResultException $e) {
                    $errors['title'] = 'Ce titre existe déjà';
                }
                if (empty($errors)) {
                    $mangaRepository->insertManga($_POST);

                    foreach ($_POST['categories'] as $categorie) {
                        $manga = $mangaRepository->findOneByTitle(
                            $_POST['title']
                        );
                        $categorie = $categoriesRepository->findOneById(
                            $categorie
                        );
                        if ($categorie == null) {
                            $categorie = new Categories();
                            var_dump($_POST['categories']['newCategorie']);
                            $categorie->setName(
                                $_POST['categories']['newCategorie']
                            );
                            $categoriesRepository->insertCategorieObject(
                                $categorie
                            );
                            $categorie = $categoriesRepository->findOneByName(
                                $_POST['categories']['newCategorie']
                            );
                        }
                        $mangaCategoriesRepository->insertMangaCategoriesObject(
                            $categorie,
                            $manga
                        );
                    }

                    header('Location: /');
                } else {
                    var_dump($errors);
                }
            }
            return new Response(
                'newManga.html.twig',
                ['errors' => $errors] + ['allCategories' => $allCategories]
            );
        } else {
            header('Location: /');
        }
    }
}
