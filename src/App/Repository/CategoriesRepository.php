<?php

namespace App\Repository;

use App\Entity\Categories;
use Doctrine\ORM\EntityRepository;

class CategoriesRepository extends EntityRepository
{
    public function findOneByTitle(string $name)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('m')
            ->from(Categories::class, 'm')
            ->where('m.name = :name')
            ->setParameter('name', $name);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    function insertCategorie(string $data): void
    {
        $categorie = new Categories();
        $categorie->setName($data);

        $this->_em->persist($categorie);
        $this->_em->flush();
    }

    function insertCategorieObject(Categories $categorie): void
    {
        $this->_em->persist($categorie);
        $this->_em->flush();
    }
}
