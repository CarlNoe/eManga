<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Address;

class AddressRepository extends EntityRepository
{
    public function getAdressesOfUser(int $id): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from(Address::class, 'a')
            ->join('a.users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getResult();
    }

    public function insertAddress(array|Address $address): void
    {
        $this->_em->persist($address);
        $this->_em->flush();
    }
}
