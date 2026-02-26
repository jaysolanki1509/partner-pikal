<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\SummaryReportMail',
		'App\Console\Commands\SendCounterStatus',
		'App\Console\Commands\DashboardMail',
		//'App\Console\Commands\SendOrderKotDiff',
		'App\Console\Commands\SendDuplicateInvoice',
		'App\Console\Commands\SendAllOutletSummary',
		'App\Console\Commands\DeleteOrders',
        //'App\Console\Commands\FetchOnlineOrderEmail',
        'App\Console\Commands\UploadSalesDataToZoho'
		//'App\Console\Commands\SMSConsumed'
		//'App\Console\Commands\DailyDetailPdfReport'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')->hourly();
		$schedule->command('pikal:summary')->dailyAt('04:00');
		//$schedule->command('foodklub:smsconsumed')->dailyAt('01:30');
		$schedule->command('pikal:counterstatus')->cron('* * * * *');
		$schedule->command('pikal:dashboardmail')->dailyAt('04:00');
		//$schedule->command('foodklub:orderkotdiff')->dailyAt('04:00');
		$schedule->command('pikal:sendduplicateinvoice')->dailyAt('04:00');
		$schedule->command('pikal:alloutletsummary')->dailyAt('05:30');
        $schedule->command('pikal:uploadsalesdatatozoho')->dailyAt('04:00');
        $schedule->command('pikal:deleteOrders')->dailyAt('06:00');
        //$schedule->command('foodklub:fetchOnlineOrderEmail')->cron('* * * * *');

	}

}
