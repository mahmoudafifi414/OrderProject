<?php

namespace Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order;

use Package\Domain\Model\DomainModel;
use Package\Domain\Model\Order\IOrderRepository;
use Package\Infrastructure\Mysql\Model\Eloquent\Order;

class OrderRepository implements IOrderRepository
{
    /**
     * @return DomainModel
     */
    public function createOrder(): DomainModel
    {
         return Order::create()->convertToDomainModel();
    }
}
