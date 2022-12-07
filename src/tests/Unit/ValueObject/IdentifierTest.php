<?php

namespace Tests\Unit\ValueObject;

use Package\Domain\ValueObject\Identifier;
use Package\Domain\ValueObject\Order\OrderId;
use PHPUnit\Framework\TestCase;

class IdentifierTest extends TestCase
{
    /**
     * @return void
     */
    public function testJsonSerialize(): void
    {
        //we choose here OrderId value object class, and we can choose any value object class extends Identifier
        $identifier = new OrderId(1);
        $this->assertEquals(1, json_encode($identifier));
    }
}
