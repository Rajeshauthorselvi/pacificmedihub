<?php

namespace App\Listerners;

use App\Events\StockNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Orders;
use App\Models\ProductVariantVendor;
class StockNotificationListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StockNotificationEvent  $event
     * @return void
     */
    public function handle(StockNotificationEvent $event)
    {
        //
    }
}
