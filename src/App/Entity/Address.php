<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'address')]
class Address
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255, name: 'street')]
    protected string $street;

    #[ORM\Column(type: 'string', length: 255, name: 'city')]
    protected string $city;

    #[ORM\Column(type: 'string', length: 255, name: 'zip_code')]
    protected string $zipCode;

    #[ORM\Column(type: 'string', length: 255, name: 'country')]
    protected string $country;

    public function __construct(array $data)
    {
        $this->street = $data['street'];
        $this->city = $data['city'];
        $this->zipCode = $data['zipCode'];
        $this->country = $data['country'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }
}
