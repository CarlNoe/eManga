<?php

namespace App\utils;

use App\Entity\Categories;
use App\Entity\CategoriesManga;
use App\Entity\Manga;
use Doctrine\ORM\EntityManagerInterface;
use Framework\Doctrine\EntityManager;

require '../../../vendor/autoload.php';
$api_url = 'https://kitsu.io/api/edge/manga?page[limit]=20&page[offset]=0';

$data = getDataFromApi($api_url);
$nextData = getNextDataFromApi($api_url);

$entityManager = EntityManager::getInstance();

for ($i = 0; $i < 10; $i++) {
    insertAllDataFromApi($data, $entityManager);
    $api_url = getNextDataFromApi($nextData);
    $data = getDataFromApi($api_url);
    $nextData = getNextDataFromApi($api_url);
}

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

    $parametersManga = [
        'title' => $manga->attributes->canonicalTitle,
        'description' => $manga->attributes->description,
        'image' => $manga->attributes->posterImage->small,
        'price' => rand(1, 15),
        'stock' => rand(1, 50),
    ];
    $newManga = new Manga($parametersManga);

    $MangaRepository->insertManga($newManga);

    return $newManga;
}

function insertCategorieFromApi(
    object $categories,
    EntityManagerInterface $em
): Categories {
    $categorieRepository = $em->getRepository(Categories::class);

    $categorie = new Categories($categories->attributes->title);
    if (
        $categorieRepository->findOneByTitle($categories->attributes->title) ===
        null
    ) {
        $categorieRepository->insertCategorie($categorie);
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

function getDataFromApi(string $api_url)
{
    $json_data = file_get_contents($api_url);

    $response_data = json_decode($json_data);

    $data = $response_data->data;

    return $data;
}

function getNextDataFromApi(string $api_url)
{
    $json_data = file_get_contents($api_url);

    $response_data = json_decode($json_data);

    $data = $response_data->links->next;

    return $data;
}
