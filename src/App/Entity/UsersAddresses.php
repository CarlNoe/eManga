<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersAddressesRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: UsersAddressesRepository::class)]
#[ORM\Table(name: 'user_addresses')]
class UsersAddresses
{
    #[Id, ManyToOne(targetEntity: User::class)]
    protected User|null $user = null;

    #[Id, ManyToOne(targetEntity: Address::class)]
    protected Address|null $address = null;

    public function __construct(User $user, Address $address)
    {
        $this->user = $user;
        $this->address = $address;
    }
}
