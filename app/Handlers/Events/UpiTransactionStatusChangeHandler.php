<?php namespace App\Handlers\Events;


use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Queue;
use Savitriya\Icici_upi\UpiTransactionStatusChangeEvent;

class UpiTransactionStatusChangeHandler {

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
	 * @param  UpiTransactionStatusChangeEvent  $event
	 * @return void
	 */
	public function handle(UpiTransactionStatusChangeEvent $event)
	{

		Queue::push('App\Commands\OwnerNotification@upiTransactionStatusChange', array('txnId'=>$event->txnId,'billNumber'=>$event->billNumber,'oldStatus'=>$event->oldStatus,'oldStatusString'=>$event->oldStatusString,'status'=>$event->status,'statusString'=>$event->statusString,'amount'=>$event->amount,'playerVa'=>$event->playerVa,'note'=>$event->note,'initDate'=>$event->initDate));

	}

}
