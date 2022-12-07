<?php
namespace Package\Application\Service\Order;

use App\Events\IngredientBelowHalfOfStockQuantityEvent;
use Exception;
use Illuminate\Support\Facades\DB;
use Package\Application\Service\Ingredient\IngredientService;
use Package\Application\Service\Product\ProductIngredientService;
use Package\Application\Service\Product\ProductRequestToAndFromDomainModelService;
use Package\Domain\Exception\NotEnoughInStockIngredientsException;
use Package\Domain\Model\DomainModel;
use Package\Domain\Model\Order\IOrderRepository;

class OrderCreationService
{
    /**
     * @var ProductRequestToAndFromDomainModelService
     */
    private ProductRequestToAndFromDomainModelService $productRequestToAndFromDomainModelService;

    /**
     * @var OrderProductService
     */
    private OrderProductService $orderProductService;

    /**
     * @var ProductIngredientService
     */
    private ProductIngredientService $productIngredientService;

    /**
     * @var IngredientService $ingredientService
     */
    private IngredientService $ingredientService;

    /**
     * @var IOrderRepository
     */
    private IOrderRepository $orderRepository;

    /**
     * @param ProductRequestToAndFromDomainModelService $productRequestToAndFromDomainModelService
     * @param OrderProductService $orderProductService
     * @param ProductIngredientService $productIngredientService,
     * @param IngredientService $ingredientService,
     * @param IOrderRepository $orderRepository
     */
    public function __construct(
        ProductRequestToAndFromDomainModelService $productRequestToAndFromDomainModelService,
        OrderProductService                       $orderProductService,
        ProductIngredientService                  $productIngredientService,
        IngredientService                         $ingredientService,
        IOrderRepository                          $orderRepository
    )
    {
        $this->productRequestToAndFromDomainModelService = $productRequestToAndFromDomainModelService;
        $this->orderProductService = $orderProductService;
        $this->productIngredientService = $productIngredientService;
        $this->ingredientService = $ingredientService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $products
     * @return DomainModel
     * @throws Exception
     */
    public function __invoke(array $products): DomainModel
    {
        try {
            DB::beginTransaction();
            /*we made here domain model object of product (some similarities with DTO object) from product details in request,
              so we can make a clean layer to encapsulate out product data and make code cleaner,
              and we can add mutations and changes inside the domain model before convert product again to array
            */
            $productsDomainModels = $this->productRequestToAndFromDomainModelService->toDomainModel($products);

            //get product Ids and Quantities from the domain model
            $productIdsAndQuantities = $this->productRequestToAndFromDomainModelService->toProductIdsAndQuantitiesArray($productsDomainModels);

            //get the product ingredients grouped by quantity of ingredients
            $productsIngredients = $this->productIngredientService->getProductsIngredients($productIdsAndQuantities);
            //check if any ingredient out of stock step
            $isAnyIngredientOutOfStock = $this->productIngredientService->isAnyIngredientOutOfStock($productsIngredients);

            if ($isAnyIngredientOutOfStock) {
                throw new NotEnoughInStockIngredientsException("Sorry we couldn't proceed with your order because some order products can't be done right now");
            }

            //We can send the emails via job queue run every 1 hour, but we should scan the full ingredients table every 1 hour and may be valid because it is run after one hour.
            //also here I checked only the ingredients in the order not the full scan and may be good in performance.
            $ingredientsBelowHalfQuantity = $this->ingredientService->getIngredientsBelowHalfQuantity($productsIngredients);
            if (!empty($ingredientsBelowHalfQuantity)){
                event(new IngredientBelowHalfOfStockQuantityEvent($ingredientsBelowHalfQuantity));
            }
            //create order step
            $orderDomainModel = $this->orderRepository->createOrder();
            //then create order product step
            $this->orderProductService->createOneOrMoreOrderProduct($orderDomainModel->getOrderId()->getValue(), $productIdsAndQuantities);
            //update ingredients stock step
            $this->ingredientService->updateOneOrMoreIngredient($productsIngredients);
            //commit all these changes
            DB::commit();

            return $orderDomainModel;
        } catch (NotEnoughInStockIngredientsException|Exception $exception){
            DB::rollBack();
            $exception instanceof NotEnoughInStockIngredientsException ?
                throw new NotEnoughInStockIngredientsException($exception->getMessage()):
                throw new Exception("Sorry we couldn't process you request right now");
        }
    }
}
