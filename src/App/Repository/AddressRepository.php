<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Address;

class AddressRepository extends EntityRepository
{
    public function insertAddress(array|Address $address): void
    {
        $this->_em->persist($address);
        $this->_em->flush();
    }
}
