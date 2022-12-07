<?php

namespace Package\Domain\Model\Order;

use Package\Domain\Model\DomainModel;
use Package\Domain\ValueObject\Order\OrderId;

class Order extends DomainModel
{
    /**
     * @var OrderId
     */
    private OrderId $orderId;

    /**
     * @param OrderId $orderId
     */
    public function __construct(OrderId $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId{
        return $this->orderId;
    }
}
