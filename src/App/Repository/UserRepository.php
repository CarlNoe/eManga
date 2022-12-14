<?php

namespace App\Repository;

use App\Entity\User;
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
        var_dump(gettype($user->getAddresses()));

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
}
