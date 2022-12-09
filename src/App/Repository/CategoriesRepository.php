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

    function insertCategorie(string|Categories $categorie): void
    {
        if (is_string($categorie)) {
            $categorie = new Categories($categorie);
        }

        $this->_em->persist($categorie);
        $this->_em->flush();
    }

    function deleteCategorie(string|Categories $data): void
    {
        if (is_string($data)) {
            $categorie = $this->findOneByTitle($data);
        }

        $this->_em->remove($categorie);
        $this->_em->flush();
    }

    function updateCategorie(
        string|Categories $oldCategorie,
        string|Categories $newCategorie
    ): void {
        if (is_string($oldCategorie)) {
            $categorie = $this->findOneByTitle($oldCategorie);
        }
        if (is_string($newCategorie)) {
            $categorie->setName($newCategorie);
        }

        $this->_em->persist($categorie);
        $this->_em->flush();
    }

    function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('m')->from(Categories::class, 'm');

        return $queryBuilder->getQuery()->getResult();
    }

    function insertManyCategories(array $categories): void
    {
        foreach ($categories as $categorie) {
            $this->insertCategorie($categorie);
        }
    }

    function findOneById(int $id)
    {
        return $this->find($id);
    }
}
