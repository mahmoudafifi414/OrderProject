<?php

namespace Package\Domain\Model\Order;

use Package\Domain\Model\DomainModel;

interface IOrderRepository
{
    public function createOrder(): DomainModel;
}
