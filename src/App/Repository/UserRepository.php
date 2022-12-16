<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderQuantity;
use App\Entity\Manga;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findOneByEmail(string $email)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function isUniqEmail(string $email): bool
    {
        $user = $this->findOneByEmail($email);
        if ($user == null) {
            return true;
        } else {
            return false;
        }
    }

    function getUser(string $email, string $password)
    {
        $user = $this->findOneByEmail($email);
        if ($user == null) {
            return null;
        } else {
            if (password_verify($password, $user->getPassword())) {
                return $user;
            } else {
                return null;
            }
        }
    }

    function insertUser(array $data): void
    {
        $user = new User($data);

        $this->_em->persist($user);
        $this->_em->flush();
    }

    function isUserUniq(string $username): bool
    {
        $user = $this->findOneByUsername($username);
        if ($user == null) {
            return true;
        } else {
            return false;
        }
    }

    public function findOneByUsername(string $username)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.username = :username')
            ->setParameter('username', $username);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

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

    public function getOrderList(int $id): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('o', 'm', 'oq')
            ->from(Order::class, 'o')
            ->join('o.user', 'u')
            ->join('o.orderQuantities', 'oq')
            ->join('oq.manga', 'm')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function getAllOrderList(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('o', 'm', 'oq')
            ->from(Order::class, 'o')
            ->join('o.orderQuantities', 'oq')
            ->join('oq.manga', 'm');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
