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

    function insertManga(array $data): void
    {
        $manga = new Manga();
        $manga->setTitle($data['title']);
        $manga->setDescription($data['description']);
        $manga->setImage($data['image']);
        $manga->setPrice($data['price']);
        $manga->setStock($data['stock']);

        $this->_em->persist($manga);
        $this->_em->flush();
    }

    function insertMangaObject(Manga $manga): void
    {
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

    function find10Manga()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('m')
            ->from(Manga::class, 'm')
            ->setMaxResults(10);

        return $queryBuilder->getQuery()->getResult();
    }
}
