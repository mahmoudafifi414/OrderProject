<?php

namespace Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order;

use Illuminate\Support\Collection;
use Package\Domain\Model\Order\IOrderProductRepository;
use Package\Infrastructure\Mysql\Model\Eloquent\OrderProduct;

class OrderProductRepository implements IOrderProductRepository
{
    /**
     * this method create bulk data of order products data
     * @param int $orderId
     * @param Collection $productIdsAndQuantities
     * @return bool
     */
    public function createOneOrMoreOrderProduct(int $orderId, Collection $productIdsAndQuantities): bool
    {
        $orderProductsBulkDataToInsert = $this->makeBulkArrayOfOrderProducts($orderId, $productIdsAndQuantities);
        return OrderProduct::insert($orderProductsBulkDataToInsert);
    }

    /**
     * prepare data for bulk insertion
     * @param int $orderId
     * @param Collection $productIdsAndQuantities
     * @return array
     */
    private function makeBulkArrayOfOrderProducts(int $orderId, Collection $productIdsAndQuantities): array
    {
        return $productIdsAndQuantities->map(function ($productIdAndQuantity) use ($orderId){
            return [
                'order_id' => $orderId,
                'product_id' => $productIdAndQuantity['productId'],
                'product_quantity' => $productIdAndQuantity['quantity']
            ];
        })->all();
    }
}
