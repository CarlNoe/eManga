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
}
