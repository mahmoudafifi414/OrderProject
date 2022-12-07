<?php

namespace Package\Application\Controller\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorContract;
use Package\Application\Service\Order\OrderCreationService;
use Package\Domain\Exception\NotEnoughInStockIngredientsException;

class OrderCreationController extends Controller
{
    /**
     * @param Request $request
     * @param OrderCreationService $orderCreationService
     * @return JsonResponse
     */
    public function process(Request $request, OrderCreationService $orderCreationService): JsonResponse
    {
        try {
            $validation = $this->validateRequest($request);

            if (!$validation->valid()) {
                return response()->json($validation->errors(), 400);
            }
            $productInRequest = $request->all()['products'];
            $order = $orderCreationService($productInRequest);

            return response()->json(['orderId' => $order->getOrderId()->getValue()], 201);
        } catch (NotEnoughInStockIngredientsException $exception){
            return response()->json([
                'message' => $exception->getMessage()
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return ValidatorContract
     */
    public function validateRequest(Request $request): ValidatorContract
    {
        return Validator::make(
            $request->all(),
            [
               'products' => 'required|array',
               'products.*.product_id' => 'required|exists:Package\Infrastructure\Mysql\Model\Eloquent\Product,id|int',
               'products.*.quantity' => 'required|int'
            ]
        );
    }
}
