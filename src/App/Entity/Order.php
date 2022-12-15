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
    protected float $orderSubTotal;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User|null $user = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderQuantity::class)]
    private array $orderQuantities = [];

    public function __construct(
        User $user,
        float $shippingCost,
        float $orderSubTotal
    ) {
        $this->user = $user;
        $this->orderDate = new DateTime();
        $this->orderStatus = 'pending';
        $this->shippingCost = $shippingCost;
        $this->orderSubTotal = $orderSubTotal;
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

    public function getOrderSubTotal(): float
    {
        return $this->orderSubTotal;
    }

    public function setOrderSubTotal(float $orderSubTotal): void
    {
        $this->orderTotal = $orderSubTotal;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getOrderQuantities(): array
    {
        return $this->orderQuantities;
    }

    public function setOrderQuantities(array $orderQuantities): void
    {
        $this->orderQuantities = $orderQuantities;
    }

    public function addOrderSubTotal(float $orderSubTotal): void
    {
        $this->orderSubTotal += $orderSubTotal;
    }

    public function addShippingCost(float $shippingCost): void
    {
        $this->shippingCost += $shippingCost;
    }

    public function addOrderQuantity(OrderQuantity $orderQuantity): void
    {
        $this->orderQuantities[] = $orderQuantity;
    }

    public function removeOrderQuantity(OrderQuantity $orderQuantity): void
    {
        $this->orderQuantities = array_filter(
            $this->orderQuantities,
            fn($oq) => $oq->getId() !== $orderQuantity->getId()
        );
    }
}
