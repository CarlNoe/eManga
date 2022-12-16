<?php

namespace App\Repository;

use App\Entity\categories;
use App\Entity\Manga;
use Doctrine\ORM\EntityRepository;

class MangaRepository extends EntityRepository
{
    public function findOneByTitle(string $title)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('m')
            ->from(Manga::class, 'm')
            ->where('m.title = :title')
            ->setParameter('title', $title);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    function insertManga(array|Manga $manga): void
    {
        if (is_array($manga)) {
            $manga = new Manga($manga);
        }

        $this->_em->persist($manga);
        $this->_em->flush();
    }

    function findOneById(int $id): ?Manga
    {
        return $this->find($id);
    }

    function findcategories(int $id)
    {
        $query = $this->_em
            ->createQuery(
                'SELECT c.name
            FROM App\Entity\categories c
            JOIN App\Entity\categoriesManga cm
            WITH c.id = cm.categories
            WHERE cm.manga = :id'
            )
            ->setParameter('id', $id);

        return $query->getSingleColumnResult();
    }

    function deleteManga(int $int): void
    {
        $manga = $this->findOneById($int);
        $this->_em->remove($manga);
        $this->_em->flush();
    }

    function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('m')->from(Manga::class, 'm');

        return $queryBuilder->getQuery()->getResult();
    }

    function find10Manga(int $page, array $categories = [])
    {
        $requestWithCategories = ' JOIN App\Entity\categoriesManga cm
        WITH m.id = cm.manga WHERE cm.categories IN (:categories)';
        $arrayIsEmpty = count($categories) === 0;
        $query = $this->_em
            ->createQuery(
                'SELECT m
            FROM App\Entity\Manga m
            ' . (!$arrayIsEmpty ? $requestWithCategories : '')
            )
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10);

        if (!$arrayIsEmpty) {
            $query->setParameter('categories', $categories);
        }

        return $query->getResult();
    }

    function getAllPages()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('count(m.id)')->from(Manga::class, 'm');

        return ceil($queryBuilder->getQuery()->getSingleScalarResult() / 10);
    }

    function updateManga(array|object $newManga, int $id = 0): void
    {
        $manga = $this->findOneById($id);
        if (is_array($newManga)) {
            $manga->setTitle($newManga['title']);
            $manga->setDescription($newManga['description']);
            $manga->setPrice($newManga['price']);
            $manga->setStock($newManga['stock']);
            $manga->setImage($newManga['image']);
        } else {
            $manga->setTitle($newManga->getTitle());
            $manga->setDescription($newManga->getDescription());
            $manga->setPrice($newManga->getPrice());
            $manga->setStock($newManga->getStock());
            $manga->setImage($newManga->getImage());
        }

        $this->_em->persist($manga);
        $this->_em->flush();
    }

    function getReserved(int $id)
    {
        $query = $this->_em->createQueryBuilder();
        $query
            ->select('m.reserved')
            ->from(Manga::class, 'm')
            ->where('m.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()->getSingleScalarResult();
    }
}
