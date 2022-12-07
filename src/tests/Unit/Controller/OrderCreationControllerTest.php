<?php

namespace Tests\Unit\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator as ValidatorContract;
use Package\Application\Controller\Order\OrderCreationController;
use Package\Application\Service\Order\OrderCreationService;
use Package\Domain\Exception\NotEnoughInStockIngredientsException;
use Package\Domain\Model\Order\Order;
use Package\Domain\ValueObject\Order\OrderId;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class OrderCreationControllerTest extends TestCase
{
    use CreatesApplication;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->createApplication();
    }

    /**
     * @return void
     */
    public function testControllerProcessValidationFiled(): void
    {
        $controller = $this->getMockBuilder(OrderCreationController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateRequest'])
            ->getMock();


       $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

       $validatorContract = $this->getMockBuilder(ValidatorContract::class)
                                 ->onlyMethods(['valid','errors'])
                                 ->disableOriginalConstructor()
                                 ->getMock();

       $validatorContract->method('valid')
                          ->willReturn(false);
       $validatorContract->method('errors')
                          ->willReturn([
                              "products.0.product_id" => [
                                    "The products.0.product_id field is required."
                              ]
                          ]);

       $orderCreationService = $this->getMockBuilder(OrderCreationService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('validateRequest')->willReturn($validatorContract);

        $result = $controller->process($request, $orderCreationService);
        $expectedData = json_encode([
            "products.0.product_id" => [
                "The products.0.product_id field is required."
            ]
        ]);
        $this->assertEquals(400, $result->status());
        $this->assertEquals($expectedData, json_encode($result->getData()));
    }

    /**
     * @return void
     */
    public function testControllerProcessCreateOrder(): void
    {
        $controller = $this->getMockBuilder(OrderCreationController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateRequest'])
            ->getMock();

        $request = new Request();
        $request->merge(['products' => [[
            'product_id' => 1,
            'quantity' => 2
        ]]]);

        $validatorContract = $this->getMockBuilder(ValidatorContract::class)
            ->onlyMethods(['valid','errors'])
            ->disableOriginalConstructor()
            ->getMock();

        $validatorContract->method('valid')
            ->willReturn(true);

        $orderDomainModel = new Order(OrderId::of(1));

        $orderCreationService = $this->getMockBuilder(OrderCreationService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderCreationService->method('__invoke')->willReturn($orderDomainModel);

        $controller->method('validateRequest')->willReturn($validatorContract);

        $result = $controller->process($request, $orderCreationService);

        $this->assertEquals(201, $result->status());
        $expectedData = json_encode(["orderId"=> 1]);
        $this->assertEquals($expectedData, json_encode($result->getData()));
    }

    /**
     * @return void
     */
    public function testControllerProcessNotEnoughInStockIngredients(){
        $controller = $this->getMockBuilder(OrderCreationController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateRequest'])
            ->getMock();
        $validatorContract = $this->getMockBuilder(ValidatorContract::class)
            ->onlyMethods(['valid','errors'])
            ->disableOriginalConstructor()
            ->getMock();

        $validatorContract->method('valid')
            ->willReturn(true);

        $request = new Request();
        $request->merge(['products' => [[
            'product_id' => 1,
            'quantity' => 2
        ]]]);
        $orderCreationService = $this->getMockBuilder(OrderCreationService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $orderCreationService->method('__invoke')->will($this->throwException(new NotEnoughInStockIngredientsException()));
        $controller->method('validateRequest')->willReturn($validatorContract);
        $result = $controller->process($request, $orderCreationService);

        $this->assertEquals(422, $result->status());
    }

    /**
     * @return void
     */
    public function testControllerProcessServerError(): void
    {
        $controller = $this->getMockBuilder(OrderCreationController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateRequest'])
            ->getMock();
        $validatorContract = $this->getMockBuilder(ValidatorContract::class)
            ->onlyMethods(['valid','errors'])
            ->disableOriginalConstructor()
            ->getMock();

        $validatorContract->method('valid')
            ->willReturn(true);

        $request = new Request();
        $request->merge(['products' => [[
            'product_id' => 1,
            'quantity' => 2
        ]]]);
        $orderCreationService = $this->getMockBuilder(OrderCreationService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $orderCreationService->method('__invoke')->will($this->throwException(new \Exception()));
        $controller->method('validateRequest')->willReturn($validatorContract);
        $result = $controller->process($request, $orderCreationService);

        $this->assertEquals(500, $result->status());
    }

    /**
     * @return void
     */
    public function testValidateRequestIsValid(): void
    {
        $request = new Request();
        $request->merge(['products' => [[
            'product_id' => 1,
            'quantity' => 2
        ]]]);

        $controller = new OrderCreationController();
        $result = $controller->validateRequest($request);
        $this->assertTrue((bool)$result->valid());
        $this->assertEmpty($result->errors()->toArray());
    }

    /**
     * @return void
     */
    public function testValidateRequestIsNotValid(): void
    {
        $request = new Request();
        $request->merge(['products' => [
            'quantity' => 2
        ]]);

        $controller = new OrderCreationController();
        $result = $controller->validateRequest($request);
        $this->assertFalse((bool)$result->valid());
        $this->assertFalse((bool)$result->valid());
    }
}
