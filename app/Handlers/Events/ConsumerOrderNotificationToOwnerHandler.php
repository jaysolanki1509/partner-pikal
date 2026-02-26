<?php namespace App\Handlers\Events;

use App\Events\OrderNotificationEvent;
use App\Outlet;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Mail;

class ConsumerOrderNotificationToOwnerHandler implements ShouldBeQueued {

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
	 * @param  OrderNotificationEvent  $event
	 * @return void
	 */
	public function handle(OrderNotificationEvent $event)
	{

		if ( isset($event->order) && sizeof($event->order) > 0 ) {

			$order = $event->order;
			$outlet = Outlet::find($order->outlet_id);

			if ( isset($outlet) && sizeof($outlet) > 0 ) {

				$emails = explode(',',$outlet->biller_emails);

				if ( isset($emails) && sizeof($emails) > 0 ) {

					foreach( $emails as $em ) {
						$time = date('M d, Y H:i:s',strtotime($order->created_at));
						Mail::send('emails.consumerOrderDetails', ['order' => $order], function($message) use ($em, $time)
						{
							$message->from('we@pikal.io', 'Pikal');
							$message->to($em, 'Pikal');
							$message->subject('New Customer Order Details - '.$time);
						});

					}

				}

			}


		}

	}

}
