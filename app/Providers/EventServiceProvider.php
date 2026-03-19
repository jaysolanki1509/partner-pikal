<?php namespace App\Providers;
use App\Events\OrderItemConfirmEvent;
use App\Events\OrderNotificationEvent;
use App\Handlers\Events\ConsumerOrderNotificationToOwnerHandler;
use App\Handlers\Events\OrderItemConfirmHandler;
use Savitriya\Icici_upi\UpiTransactionStatusChangeEvent;
use App\Handlers\Events\UpiTransactionStatusChangeHandler;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
class EventServiceProvider extends ServiceProvider {
	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		UpiTransactionStatusChangeEvent::class => [
			UpiTransactionStatusChangeHandler::class
		],
		OrderNotificationEvent::class => [
			ConsumerOrderNotificationToOwnerHandler::class
		],
		OrderItemConfirmEvent::class => [
			OrderItemConfirmHandler::class
		]
	];
	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);
		//
	}
}