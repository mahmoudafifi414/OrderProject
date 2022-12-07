<?php

namespace App\Listeners;

use App\Events\IngredientBelowHalfOfStockQuantityEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Package\Application\Service\Ingredient\IngredientService;

class NotificationForIngredientBelowHalfInSockListener
{
    /**
     * @var IngredientService $ingredientService
     */
    private IngredientService $ingredientService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(IngredientService $ingredientService)
    {
        $this->ingredientService = $ingredientService;
    }

    /**
     * Handle the event.
     *
     * @param IngredientBelowHalfOfStockQuantityEvent $event
     * @return void
     */
    public function handle(IngredientBelowHalfOfStockQuantityEvent $event): void
    {

        $markIngredientsAsNotified = $this->ingredientService->markIngredientsAsNotified(array_keys($event->ingredients));

        if ($markIngredientsAsNotified) {
            $message = 'The ingredients quantity is below the half ( ' . implode(' , ', array_values($event->ingredients)) . ' )';

            //todo config email with third party to send email something like Sendgrid or Mailchimp

            //todo end
        }
    }
}
