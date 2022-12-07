<?php

namespace Package\Domain\Model\Order;

use Illuminate\Support\Collection;

interface IOrderProductRepository
{
    /**
     * @param int $orderId
     * @param Collection $productIdsAndQuantities
     * @return bool
     */
    public function createOneOrMoreOrderProduct(int $orderId, Collection $productIdsAndQuantities): bool;
}
