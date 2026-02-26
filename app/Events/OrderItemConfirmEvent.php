<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderItemConfirmEvent extends Event {

	//use SerializesModels;

	public $order_id;
	public $kot_arr;
	public $outlet_id;
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct( $outlet_id, $order_id, $kot_arr )
	{
		$this->order_id = $order_id;
		$this->kot_arr = $kot_arr;
		$this->outlet_id = $outlet_id;

	}

}
