<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'order')]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(type: 'datetime', length: 255, name: 'order_date')]
    protected DateTime $orderDate;

    #[ORM\Column(type: 'string', length: 255, name: 'order_status')]
    protected string $orderStatus;

    #[ORM\Column(type: 'float', name: 'order_shipping')]
    protected float $shippingCost;

    #[ORM\Column(type: 'float', name: 'order_total')]
    protected float $orderTotal;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User|null $user = null;

    public function __construct(
        User $user,
        float $shippingCost,
        float $orderTotal
    ) {
        $this->user = $user;
        $this->orderDate = new DateTime();
        $this->orderStatus = 'pending';
        $this->shippingCost = $shippingCost;
        $this->orderTotal = $orderTotal;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOrderDate(): DateTime
    {
        return $this->orderDate;
    }

    public function setOrderDate(DateTime $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    public function getOrderStatus(): string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(string $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    public function getShippingCost(): float
    {
        return $this->shippingCost;
    }

    public function setShippingCost(float $shippingCost): void
    {
        $this->shippingCost = $shippingCost;
    }

    public function getOrderTotal(): float
    {
        return $this->orderTotal;
    }

    public function setOrderTotal(float $orderTotal): void
    {
        $this->orderTotal = $orderTotal;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
