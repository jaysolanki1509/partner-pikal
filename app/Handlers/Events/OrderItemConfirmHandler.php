<?php namespace App\Handlers\Events;

use App\Events\OrderItemConfirmEvent;

use App\Outlet;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderItemConfirmHandler implements ShouldBeQueued {

	//use InteractsWithQueue;

	/**
	 * Create the event handler.
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
	 * @param  OrderItemConfirmEvent  $event
	 * @return void
	 */
	public function handle(OrderItemConfirmEvent $event)
	{

		$order_id = $event->order_id;
		$kot_arr = $event->kot_arr;
		$outlet_id = $event->outlet_id;

		$outlet = Outlet::find($outlet_id);


		if ( isset($outlet) && sizeof($outlet) > 0 ) {

			$emails = explode(',',$outlet->biller_emails);

			if ( isset($emails) && sizeof($emails) > 0 ) {

				foreach( $emails as $em ) {

					Mail::send('emails.orderitemconfirm', ['kot' => $kot_arr,'order_id'=>$order_id], function($message) use ($em, $order_id)
					{
						$message->from('we@pikal.io', 'Pikal');
						$message->to($em, 'Pikal');
						$message->subject('Order item confirm for order - #'.$order_id);
					});

				}

			}

		}

	}

}
