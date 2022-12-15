<?php

namespace App\Entity;

use App\Repository\UserAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: UserAddressRepository::class)]
#[ORM\Table(name: 'user_addresses')]
class UserAdresses
{
    #[Id, ManyToOne(targetEntity: Address::class)]
    private Address|null $address = null;

    #[Id, ManyToOne(targetEntity: User::class)]
    private User|null $user = null;

    public function __construct(Address $address, User $user)
    {
        $this->addresss = $address;
        $this->user = $user;
    }
}
