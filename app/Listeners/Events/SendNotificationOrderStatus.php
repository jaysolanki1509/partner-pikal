<?php namespace App\Listeners\Events;

use App\Events\OrderNotification;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Response;

class SendNotificationOrderStatus {

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
	 * @param  OrderNotification  $event
	 * @return void
	 */
	public function handle(OrderNotification $event)
	{
        $url = 'https://android.googleapis.com/gcm/send';

        // Open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_NOBODY, 1);

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $event->headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $event->deviceid ) );

        // Execute post
        $result = curl_exec($ch);
        if(curl_errno($ch)){ echo 'Curl error: ' . curl_error($ch); }
        // Close connection

        curl_close($ch);

        return Response::json(array(
            'message' => 'ok',
            'statuscode' => 200,
            'nextstatus'=>$event->currentstatus,
            'buttonstatus'=>$event->status,
            'position'=>$event->position
        ),
            200);
	}

}
