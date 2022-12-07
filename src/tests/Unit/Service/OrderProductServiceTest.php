<?php

namespace Tests\Unit\Service;

use Package\Application\Service\Order\OrderProductService;
use Package\Infrastructure\Mysql\Repository\Domain\Mysql\Order\OrderProductRepository;
use PHPUnit\Framework\TestCase;

class OrderProductServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateOneOrMoreOrderProductIsTrue(): void
    {
        $orderProductRepository = $this->getMockBuilder(OrderProductRepository::class)
            ->onlyMethods(['createOneOrMoreOrderProduct'])
            ->disableOriginalConstructor()
            ->getMock();
        $orderProductRepository->method('createOneOrMoreOrderProduct')->willReturn(true);
        $orderProductService = new OrderProductService($orderProductRepository);
        $this->assertTrue($orderProductService->createOneOrMoreOrderProduct(1,collect([])));
    }

    /**
     * @return void
     */
    public function testCreateOneOrMoreOrderProductIsFalse(): void
    {
        $orderProductRepository = $this->getMockBuilder(OrderProductRepository::class)
            ->onlyMethods(['createOneOrMoreOrderProduct'])
            ->disableOriginalConstructor()
            ->getMock();
        $orderProductRepository->method('createOneOrMoreOrderProduct')->willReturn(false);
        $orderProductService = new OrderProductService($orderProductRepository);
        $this->assertFalse($orderProductService->createOneOrMoreOrderProduct(1,collect([])));
    }
}
