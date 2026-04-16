<?php

namespace App\Events;

use App\OrderDetails;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderNotificationEvent extends Event
{
    //use SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(order_details $order_details)
    {
        $this->order = $order_details;
        Log::info('evnet call');
    }
}
