<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class OrderNotification extends Event {

	use SerializesModels;

   public $deviceid,$headers,$currentstatus,$status,$position;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($deviceid, $headers,$currentstatus,$status,$position)
	{
       // print_r($deviceid);print_r($headers);exit;
		$this->deviceid=$deviceid;
        $this->headers=$headers;
        $this->position=$position;
        $this->currentstatus=$currentstatus;
        $this->status=$status;
	}

}
