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
}
