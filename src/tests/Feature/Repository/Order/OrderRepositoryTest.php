<?php

namespace Tests\Feature\Repository\Order;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Package\Infrastructure\Mysql\Model\Eloquent\Order;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order\OrderRepository;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     *
     * @return void
     */
    public function testCreateOrder()
    {
        $orderRepository = new OrderRepository();

        $orderDomainModel = $orderRepository->createOrder();
        $createdOrder = Order::find($orderDomainModel->getOrderId()->getValue());
        $this->assertEquals($orderDomainModel->getOrderId()->getValue(), $createdOrder->id);
    }
}
