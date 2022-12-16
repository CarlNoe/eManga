<?php

namespace App\Entity;

use App\Repository\MangaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MangaRepository::class)]
#[ORM\Table(name: 'manga')]
class Manga
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255, name: 'title')]
    protected string $title;

    #[ORM\Column(type: 'string', length: 255, name: 'description')]
    protected string $description;

    #[ORM\Column(type: 'string', length: 255, name: 'image')]
    protected string $image;

    #[ORM\Column(type: 'float', name: 'price')]
    protected string $price;

    #[ORM\Column(type: 'integer', name: 'stock')]
    protected int $stock;

    #[ORM\Column(type: 'integer', name: 'reserve')]
    protected int $reseverd = 0;

    public function __construct(array $data = [])
    {
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->image = $data['image'];
        $this->price = $data['price'];
        $this->stock = $data['stock'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getReseverd(): int
    {
        return $this->reseverd;
    }

    public function setReseverd(int $reseverd): void
    {
        $this->reseverd = $reseverd;
    }
}
