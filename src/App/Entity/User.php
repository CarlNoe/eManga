<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use App\Entity\Address;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255, name: 'username')]
    protected string $username;

    #[ORM\Column(type: 'string', length: 255, name: 'first_name')]
    protected string $firstName;

    #[ORM\Column(type: 'string', length: 255, name: 'last_name')]
    protected string $lastName;

    #[ORM\Column(type: 'string', length: 255, name: 'email')]
    protected string $email;

    #[ORM\Column(type: 'string', length: 255, name: 'password')]
    protected string $password;

    #[ORM\Column(type: 'string', length: 255, name: 'role')]
    protected string $role;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->email = $data['email'];
        $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->role = 'user';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getAddresses(): mixed
    {
        return $this->addresses;
    }

    public function __toString()
    {
        return $this->id . ' ' . $this->role;
    }
}
