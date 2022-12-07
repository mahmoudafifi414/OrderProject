<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Package\Infrastructure\Mysql\Model\Eloquent\Order;
use Tests\TestCase;

class FullOrderCreationCycleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testOrderCreatedSuccessfully(): void
    {
        $response = $this->post('/api/v1/order',
            [
            'products' => [[
                'product_id' => 1,
                'quantity' => 2
            ]]
        ]);

        $orderId = json_decode($response->getContent())->orderId;
        //we shouldn't check another tables because if order created all will be created because of the transaction.
        $order =  Order::find($orderId);
        $response->assertStatus(201);
        $this->assertNotNull($order);
    }

    /**
     * @return void
     */
    public function testOrderNotValidRequestData(): void
    {
        $response = $this->post('/api/v1/order',
            [
                'products' => [[
                    'quantity' => 2
                ]]
            ]);

        $response->assertStatus(400);
    }

    /**
     * @return void
     */
    public function testNotSufficientIngredients(): void
    {
        $response = $this->post('/api/v1/order',
            [
                'products' => [[
                    'product_id' => 1,
                    'quantity' => 6624454
                ]]
            ]);

        $response->assertStatus(422);
    }
}
