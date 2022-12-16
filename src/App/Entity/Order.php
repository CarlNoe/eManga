<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use DateTime;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
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
    protected User $user;

    #[
        ORM\OneToMany(
            mappedBy: 'order',
            targetEntity: OrderQuantity::class,
            cascade: ['persist', 'remove'],
            orphanRemoval: true
        )
    ]
    private $orderQuantities;

    #[ORM\ManyToOne(targetEntity: Address::class, cascade: ['persist'])]
    private Address|null $address = null;

    public function __construct(
        User $user,
        float $shippingCost,
        float $orderSubTotal,
        Address|null $address
    ) {
        $this->user = $user;
        $this->orderDate = new DateTime();
        $this->orderStatus = 'pending';
        $this->shippingCost = $shippingCost;
        $this->orderSubTotal = $orderSubTotal;
        $this->orderQuantities = new ArrayCollection();
        $this->address = $address;
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

    public function getOrderQuantities()
    {
        return $this->orderQuantities;
    }

    public function setOrderQuantities(ArrayCollection $orderQuantities): void
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

    public function addOrderQuantity(OrderQuantity $quantity): self
    {
        if (!$this->orderQuantities->contains($quantity)) {
            $this->orderQuantities[] = $quantity;
            $quantity->setOrder($this);
        }

        return $this;
    }

    public function removeOrderQuantity(OrderQuantity $quantity): self
    {
        if ($this->orderQuantities->contains($quantity)) {
            $this->orderQuantities->removeElement($quantity);
            $quantity->setOrder(null);
        }

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }
}
