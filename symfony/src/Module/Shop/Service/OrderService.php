<?php

declare(strict_types=1);

namespace App\Module\Shop\Service;

use App\Module\Shop\Entity\Edition;
use App\Module\Shop\Entity\Order;
use App\Module\Shop\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class OrderService
{
    public function __construct(private EntityManagerInterface $shopEntityManager, private LoggerInterface $logger)
    {
    }

    public function createOrder(Edition $edition, int $quantity = 1): Order
    {
        $order = new Order();
        $order->setOrderNumber(uniqid('10000'));
        $order->addItem(new OrderItem($edition, $quantity));
        $order->calculateTotalCost();

        $this->shopEntityManager->persist($order);

        try {
            $this->shopEntityManager->flush();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Can not create order', 0, $e);
        }

        return $order;
    }
}