<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\UsersAddresses;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UsersAddressesRepository extends EntityRepository
{
    public function insertUserAddress(User $user, Address $address): void
    {
        $UsersAddresses = new UsersAddresses($user, $address);

        $this->_em->persist($UsersAddresses);
        $this->_em->flush();
    }

    public function deleteUserAddress(int $id, int $addressId): void
    {
        $query = $this->_em
            ->createQuery(
                'DELETE FROM App\Entity\UsersAddresses u
                WHERE u.user = :id AND u.address = :addressId'
            )
            ->setParameter('id', $id)
            ->setParameter('addressId', $addressId);

        $query->getResult();
    }

    public function getAdressesOfUser(int $id): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from(Address::class, 'a')
            ->join('a.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findAddress(int $id)
    {
        $query = $this->_em
            ->createQuery(
                'SELECT a FROM App\Entity\Address a
            INNER JOIN App\Entity\UsersAddresses ua WITH ua.address = a
            INNER JOIN App\Entity\User u WITH ua.user = u
            WHERE u.id = :userId
            ORDER BY u.id ASC'
            )
            ->setParameter('userId', $id);

        return $query->getResult();
    }
}
