<?php

namespace App\Repository;

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
}
