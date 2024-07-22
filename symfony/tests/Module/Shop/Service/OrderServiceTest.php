<?php

declare(strict_types=1);

namespace App\Tests\Module\Shop\Service;

use App\Module\Shop\Entity\Edition;
use App\Module\Shop\Entity\Order;
use App\Module\Shop\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class OrderServiceTest extends TestCase
{
    /**
     * Returns sets of [edition price, ordered quantity, total cost]
     */
    public function createOrderProvider(): array
    {
        return [
            [2050, 1, 2050],
            [100, 2, 200],
            [9999, 5, 49995],
        ];
    }

    /**
     * @dataProvider createOrderProvider
     */
    public function testCreateOrder(int $editionPrice, int $itemQuantity, int $totalCost)
    {
        $order = null;
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($o) use (&$order) {
                $this->assertInstanceOf(Order::class, $order = $o);
                return true;
            }));

        $edition = new Edition('Best story', 'Big Ben', 'All Publishing', '234-2334-1-6', $editionPrice);

        $orderServiceSut = new OrderService($entityManagerMock, $this->createStub(LoggerInterface::class));
        $resultOrder = $orderServiceSut->createOrder($edition, $itemQuantity);

        $this->assertSame($order, $resultOrder);
        $this->assertEquals($totalCost, $resultOrder->getTotalCost());
        $this->assertStringStartsWith('10000', $resultOrder->getOrderNumber());
    }


    public function testCreateOrderException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Can not create order/');

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->once())
            ->method('flush')
            ->willThrowException(new \Exception('Entity manager flush error'));

        $edition = new Edition('Best story', 'Big Ben', 'All Publishing', '234-2334-1-6', 100);

        $orderServiceSut = new OrderService($entityManagerMock, $this->createStub(LoggerInterface::class));
        $orderServiceSut->createOrder($edition);
    }
}