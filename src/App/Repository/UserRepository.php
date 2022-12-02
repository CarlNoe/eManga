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
        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setFirstName($data['firstname']);
        $user->setLastName($data['lastname']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        $user->setRole('user');
        $user->setCity($data['city']);
        $user->setPostalCode($data['zipcode']);
        $user->setAddress($data['address']);

        $this->_em->persist($user);
        $this->_em->flush();
    }
}
