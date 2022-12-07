<?php

namespace Tests\Unit\Model;

use Database\Factories\IngredientFactory;
use Package\Infrastructure\Mysql\Model\Eloquent\Ingredient;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class IngredientEloquentModelTest extends TestCase
{
   /**
     * @return void
     * @throws ReflectionException
     */
    public function testNewFactory(): void
    {
        $reflectionClass = new \ReflectionClass(Ingredient::class);
        $method = $reflectionClass->getMethod('newFactory');
        $method->setAccessible(true);
        $this->assertInstanceOf(IngredientFactory::class,$method->invoke($reflectionClass));
    }
}
