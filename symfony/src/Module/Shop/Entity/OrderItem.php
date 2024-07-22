<?php

declare(strict_types=1);

namespace App\Module\Shop\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'order_item')]
class OrderItem
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[ManyToOne(targetEntity: Edition::class)]
    #[JoinColumn(name: 'edition_id', referencedColumnName: 'id')]
    private Edition $edition;

    #[ManyToOne(targetEntity: Order::class, inversedBy: 'orderItems')]
    #[JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    private Order $order;

    #[Column(type: 'smallint')]
    private int $price = 0;

    #[Column(type: 'smallint')]
    private int $quantity;

    public function __construct(Edition $edition, int $quantity = 1)
    {
        $this->edition = $edition;
        $this->quantity = $quantity;
    }


    public function getPrice(): int
    {
        return $this->price;
    }

    public function calculatePrice(): int
    {
        $this->price = $this->edition->getPrice() * $this->quantity;

        return $this->price;
    }

    public function getName(): string
    {
        return $this->edition->getName() . ' by ' . $this->edition->getAuthorName();
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

}