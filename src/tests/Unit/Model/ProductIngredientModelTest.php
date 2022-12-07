<?php

namespace Tests\Unit\Model;

use Database\Factories\ProductIngredientFactory;
use Package\Infrastructure\Mysql\Model\Eloquent\ProductIngredient;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ProductIngredientModelTest extends TestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function testNewFactory(): void
    {
        $reflectionClass = new \ReflectionClass(ProductIngredient::class);
        $method = $reflectionClass->getMethod('newFactory');
        $method->setAccessible(true);
        $this->assertInstanceOf(ProductIngredientFactory::class,$method->invoke($reflectionClass));
    }
}
