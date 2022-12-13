<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\CategoriesManga;
use App\Entity\Manga;
use Doctrine\ORM\EntityRepository;

class CategoriesMangaRepository extends EntityRepository
{
    function insertMangaCategoriesObject(
        Categories $categorie,
        Manga $manga
    ): void {
        $categorie = new CategoriesManga($categorie, $manga);

        $this->_em->persist($categorie);
        $this->_em->flush();
    }

    function deleteMangaCategoriesObject(
        Categories $categorie,
        Manga $manga
    ): void {
        $categorie = new CategoriesManga($categorie, $manga);

        $this->_em->remove($categorie);
        $this->_em->flush();
    }

    function deleteMangaCategories(int $id): void
    {
        $query = $this->_em
            ->createQuery(
                'DELETE FROM App\Entity\CategoriesManga cm
                WHERE cm.manga = :id'
            )
            ->setParameter('id', $id);

        $query->getResult();
    }

    function insertMangaCategories(array $categories, Manga $manga): void
    {
        foreach ($categories as $categorie) {
            if (is_numeric($categorie)) {
                $categorie = $this->_em
                    ->getRepository(Categories::class)
                    ->findOneById($categorie);
            } elseif (is_string($categorie)) {
                $categorie = $this->_em
                    ->getRepository(Categories::class)
                    ->findOneByTitle($categorie);
            }
            $this->insertMangaCategoriesObject($categorie, $manga);
        }
    }

    function updateMangaCategories(array $categories, Manga $manga): void
    {
        $this->deleteMangaCategories($manga->getId());
        $this->insertMangaCategories($categories, $manga);
    }
}
