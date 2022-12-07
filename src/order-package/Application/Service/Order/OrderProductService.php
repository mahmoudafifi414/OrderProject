<?php

namespace Package\Application\Service\Order;

use Illuminate\Support\Collection;
use Package\Domain\Model\Order\IOrderProductRepository;

class OrderProductService
{
    /**
     * @var IOrderProductRepository $orderProductRepository
     */
    private IOrderProductRepository $orderProductRepository;

    public function __construct(IOrderProductRepository $orderProductRepository)
    {
        $this->orderProductRepository = $orderProductRepository;
    }

    /**
     * @param int $orderId
     * @param Collection $productIdsAndQuantities
     * @return bool
     */
    public function createOneOrMoreOrderProduct(int $orderId, Collection $productIdsAndQuantities): bool
    {
        return $this->orderProductRepository->createOneOrMoreOrderProduct($orderId, $productIdsAndQuantities);
    }
}
