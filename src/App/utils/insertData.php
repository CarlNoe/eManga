<?php

namespace App\utils;

use App\Entity\Categories;
use App\Entity\CategoriesManga;
use App\Entity\Manga;
use Doctrine\ORM\EntityManagerInterface;
use Framework\Doctrine\EntityManager;

require '../../../vendor/autoload.php';
$api_url = 'https://kitsu.io/api/edge/manga?page[limit]=15&page[offset]=10';

$data = getDataFromApi($api_url);

$entityManager = EntityManager::getInstance();

insertAllDataFromApi($data, $entityManager);

function insertAllDataFromApi($data, $entityManager)
{
    foreach ($data as $manga) {
        $categoriesData = getDataFromApi(
            $manga->relationships->categories->links->related
        );
        $manga = insertMangaFromApi($manga, $entityManager);
        foreach ($categoriesData as $categorie) {
            $categorie = insertCategorieFromApi($categorie, $entityManager);
            insertMangaCategoriesFromApi($manga, $categorie, $entityManager);
        }
    }
}

function insertMangaFromApi(object $manga, EntityManagerInterface $em): Manga
{
    $MangaRepository = $em->getRepository(Manga::class);

    $newManga = new Manga();
    $newManga->setTitle($manga->attributes->canonicalTitle);
    $newManga->setDescription($manga->attributes->description);
    $newManga->setImage($manga->attributes->posterImage->original);
    $newManga->setPrice(rand(1, 15));
    $newManga->setStock(rand(1, 50));

    $MangaRepository->insertMangaObject($newManga);

    return $newManga;
}

function insertCategorieFromApi(
    object $categories,
    EntityManagerInterface $em
): Categories {
    $categorieRepository = $em->getRepository(Categories::class);

    $categorie = new Categories();
    $categorie->setName($categories->attributes->title);
    if (
        $categorieRepository->findOneByTitle($categories->attributes->title) ===
        null
    ) {
        $categorieRepository->insertCategorieObject($categorie);
    } else {
        $categorie = $categorieRepository->findOneByTitle(
            $categories->attributes->title
        );
    }
    return $categorie;
}

function insertMangaCategoriesFromApi(
    object $manga,
    object $categorie,
    EntityManagerInterface $em
): void {
    $categoriesMangaRepository = $em->getRepository(CategoriesManga::class);
    $categoriesMangaRepository->insertMangaCategoriesObject($categorie, $manga);
}

function getDataFromApi(string $api_url): array
{
    $json_data = file_get_contents($api_url);

    $response_data = json_decode($json_data);

    $data = $response_data->data;

    return $data;
}

function getNextDataFromApi(string $api_url): string
{
    $json_data = file_get_contents($api_url);

    $response_data = json_decode($json_data);

    $data = $response_data->links->next;

    return $data;
}
