<?php

namespace Tests\Unit\Model;

use Package\Domain\Model\Order\Order as OrderDomainModel;
use Package\Domain\ValueObject\Order\OrderId;
use Package\Infrastructure\Mysql\Model\Eloquent\Order;
use PHPUnit\Framework\TestCase;

class OrderEloquentModelTest extends TestCase
{
   /**
     * @return void
     */
    public function testConvertToDomainModel(): void
    {
        $expectedResult = new OrderDomainModel(OrderId::of(2));
        $orderModel = new Order();
        $orderModel->id = 2;
        $this->assertEquals($expectedResult, $orderModel->convertToDomainModel());
    }
}
