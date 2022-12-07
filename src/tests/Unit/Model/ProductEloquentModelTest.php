<?php

namespace Tests\Unit\Model;

use Database\Factories\ProductFactory;
use Package\Infrastructure\Mysql\Model\Eloquent\Product;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ProductEloquentModelTest extends TestCase
{
   /**
     * @return void
     * @throws ReflectionException
     */
    public function testNewFactory(): void
    {
        $reflectionClass = new \ReflectionClass(Product::class);
        $method = $reflectionClass->getMethod('newFactory');
        $method->setAccessible(true);
        $this->assertInstanceOf(ProductFactory::class,$method->invoke($reflectionClass));
    }
}
