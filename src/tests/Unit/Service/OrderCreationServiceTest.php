<?php

namespace Tests\Unit\Service;

use Exception;
use Package\Application\Service\Ingredient\IngredientService;
use Package\Application\Service\Order\OrderCreationService;
use Package\Application\Service\Order\OrderProductService;
use Package\Application\Service\Product\ProductIngredientService;
use Package\Application\Service\Product\ProductRequestToAndFromDomainModelService;
use Package\Domain\Exception\NotEnoughInStockIngredientsException;
use Package\Domain\Model\Order\Order;
use Package\Domain\ValueObject\Order\OrderId;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order\OrderRepository;
use PHPUnit\Framework\TestCase;

class OrderCreationServiceTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function testIfTheAllProcessSucceeded(): void
    {
        $orderDomainModel = new Order(OrderId::of(1));

        $productRequestToAndFromDomainModelService =  $this->getMockBuilder(ProductRequestToAndFromDomainModelService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderProductService =  $this->getMockBuilder(OrderProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productIngredientService =  $this->getMockBuilder(ProductIngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ingredientService =  $this->getMockBuilder(IngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderRepository =  $this->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderRepository->method('createOrder')->willReturn($orderDomainModel);

        $orderCreationService = new OrderCreationService(
            $productRequestToAndFromDomainModelService,
            $orderProductService,
            $productIngredientService,
            $ingredientService,
            $orderRepository
        );

        $products = [
            [
            'product_id' => 1,
            'quantity' => 2
            ]
        ];
        $result = $orderCreationService($products);
        $this->assertEquals(1, $result->getOrderId()->getValue());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testIfInvokeWillThrowNotEnoughInStockIngredientsException(): void
    {
        $this->expectException(NotEnoughInStockIngredientsException::class);

        $productRequestToAndFromDomainModelService =  $this->getMockBuilder(ProductRequestToAndFromDomainModelService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderProductService =  $this->getMockBuilder(OrderProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productIngredientService =  $this->getMockBuilder(ProductIngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productIngredientService->method('isAnyIngredientOutOfStock')->willReturn(true);
        $ingredientService =  $this->getMockBuilder(IngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderRepository =  $this->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderCreationService = new OrderCreationService(
            $productRequestToAndFromDomainModelService,
            $orderProductService,
            $productIngredientService,
            $ingredientService,
            $orderRepository
        );

        $products = [
            [
                'product_id' => 1,
                'quantity' => 2
            ]
        ];
        $orderCreationService($products);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testIfInvokeWillThrowGlobalException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Sorry we couldn't process you request right now");

        $productRequestToAndFromDomainModelService =  $this->getMockBuilder(ProductRequestToAndFromDomainModelService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderProductService =  $this->getMockBuilder(OrderProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productIngredientService =  $this->getMockBuilder(ProductIngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ingredientService =  $this->getMockBuilder(IngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderRepository =  $this->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderRepository->method('createOrder')->will($this->throwException(new Exception("Sorry we couldn't process you request right now")));

        $orderCreationService = new OrderCreationService(
            $productRequestToAndFromDomainModelService,
            $orderProductService,
            $productIngredientService,
            $ingredientService,
            $orderRepository
        );

        $products = [
            [
                'product_id' => 1,
                'quantity' => 2
            ]
        ];
        $orderCreationService($products);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testIfIngredientsBelowHalfQuantity(): void
    {
        $orderDomainModel = new Order(OrderId::of(1));

        $productRequestToAndFromDomainModelService =  $this->getMockBuilder(ProductRequestToAndFromDomainModelService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderProductService =  $this->getMockBuilder(OrderProductService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productIngredientService =  $this->getMockBuilder(ProductIngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ingredientService =  $this->getMockBuilder(IngredientService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ingredientService->method('getIngredientsBelowHalfQuantity')->willReturn([1 => 'Beef']);

        $orderRepository =  $this->getMockBuilder(OrderRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderRepository->method('createOrder')->willReturn($orderDomainModel);

        $orderCreationService = new OrderCreationService(
            $productRequestToAndFromDomainModelService,
            $orderProductService,
            $productIngredientService,
            $ingredientService,
            $orderRepository
        );

        $products = [
            [
                'product_id' => 1,
                'quantity' => 2
            ]
        ];
        $result = $orderCreationService($products);
        $this->assertEquals(1, $result->getOrderId()->getValue());
    }
}
