<?php

declare(strict_types=1);

namespace App\Module\Shop\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Literato\Bundle\PaymentBundle\PayableInterface;

#[Entity]
#[Table(name: '`order`')]
# AGGREGATE ENTITY згідно DDD
class Order implements PayableInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(length: 20)]
    private string $orderNumber;

    /** @var Collection<int, OrderItem> */
    #[OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ["persist"] )]
    private Collection $items;

    #[Column(type: 'integer')]
    private int $totalCost = 0;

    #[Column(length: 15)]
    private string $status = 'new';

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addItem(OrderItem $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrder($this);
        }
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function calculateTotalCost(): void
    {
        $this->totalCost = 0;
        foreach ($this->items as $item) {
            $item->calculatePrice();
            $this->totalCost += $item->getPrice();
        }
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    public function getTotalCost(): int
    {
        return $this->totalCost;
    }

    public function getPaymentAmount(): int
    {
        return $this->getTotalCost();
    }

    public function getPaymentSubject(): string
    {
        return "Payment for order #$this->orderNumber";
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isNew(): bool
    {
        return $this->status == 'new';
    }
}