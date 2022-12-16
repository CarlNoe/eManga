<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderQuantityRepository;

#[ORM\Entity(repositoryClass: OrderQuantityRepository::class)]
#[ORM\Table(name: 'order_quantity')]
class OrderQuantity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(type: 'integer', name: 'quantity')]
    protected int $quantity;

    #[ORM\Column(type: 'float', name: 'price')]
    protected float $price;

    #[ORM\ManyToOne(targetEntity: Order::class, cascade: ['persist'])]
    private Order|null $order = null;

    #[ORM\ManyToOne(targetEntity: Manga::class, cascade: ['persist'])]
    private Manga|null $manga = null;

    public function __construct(
        Order $order,
        Manga $manga,
        int $quantity,
        float $price
    ) {
        $this->order = $order;
        $this->manga = $manga;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getOrder(): Order|null
    {
        return $this->order;
    }

    public function setOrder(Order|null $order): void
    {
        $this->order = $order;
    }

    public function getManga(): Manga|null
    {
        return $this->manga;
    }

    public function setManga(Manga $manga): void
    {
        $this->manga = $manga;
    }

    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function removeQuantity(int $quantity): void
    {
        $this->quantity -= $quantity;
    }
}
