<?php

namespace Tests\Feature\Repository\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Package\Infrastructure\Mysql\Model\Eloquent\OrderProduct;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order\OrderProductRepository;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order\OrderRepository;
use ReflectionException;
use Tests\TestCase;
use ReflectionClass;

class OrderProductRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @return void
     */
    public function testCreateOneOrMoreOrderProduct(): void
    {
        $orderProductRepository =  new OrderProductRepository();
        $orderRepository =  new OrderRepository();
        $productIdsAndQuantities = collect([
                [
                    'productId' => 1,
                    'quantity' => 2
                ],
                [
                    'productId' => 2,
                    'quantity' => 2
                ]
            ]
        );

        $orderDomainModel = $orderRepository->createOrder();
        $orderId = $orderDomainModel->getOrderId()->getValue();
        $orderProductRepository->createOneOrMoreOrderProduct($orderId, $productIdsAndQuantities);
        $createdOrderProductsCount = OrderProduct::where(function ($query) use ($orderId){
            $query->where('order_id','=',$orderId)->where('product_id','=',1);
        })->orWhere(function ($query) use ($orderId){
            $query->where('order_id','=',$orderId)->where('product_id','=',2);
        })->count();
        $this->assertEquals(2, $createdOrderProductsCount);
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testMakeBulkArrayOfOrderProducts(): void
    {
        $productIdsAndQuantities = collect([
                [
                    'productId' => 1,
                    'quantity' => 2
                ],
                [
                    'productId' => 2,
                    'quantity' => 2
                ]
            ]
        );

        $reflectionClass = new ReflectionClass(OrderProductRepository::class);
        $method = $reflectionClass->getMethod('makeBulkArrayOfOrderProducts');
        $method->setAccessible(true);
        $expected =[
            [
                'order_id' => 1,
                'product_id' => 1,
                'product_quantity' => 2
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'product_quantity' => 2
            ]
        ];

        $result = $method->invokeArgs((new OrderProductRepository()), [1, $productIdsAndQuantities]);
        $this->assertEquals($expected, $result);
    }
}
