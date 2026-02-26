<?php namespace App\Console\Commands;

use App\DailySummary;
use App\Kot;
use App\order_details;
use App\order_item_attributes;
use App\OrderCancellation;
use App\ordercouponmapper;
use App\OrderHistory;
use App\OrderItem;
use App\OrderItemOption;
use App\OrderPaymentMode;
use App\Outlet;
use App\OutletSetting;
use App\Owner;
use App\payumoney;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeleteOrders extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pikal:deleteOrders';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete daily orders.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

        $outlets = Outlet::all();

        if (sizeof($outlets) > 0) {
            foreach ($outlets as $outlet) {

                if ($outlet->active == "Yes" && OutletSetting::checkAppSetting($outlet->id, "deleteYesterdayOrders")) {

                    $to_date = date('Y-m-d H:i:s');
                    $from_date =  date('Y-m-d H:i:s',strtotime('-1 day'));
                    $summary_date =  date('Y-m-d',strtotime('-1 day'));

                    $orders = order_details::where('orders.table_end_date', '>=', $from_date)
                        ->where('orders.table_end_date', '<=', $to_date)
                        ->where('outlet_id', '=', $outlet->id)->pluck('order_id');

                    if(isset($orders) && sizeof($orders)>0) {

                        foreach ($orders as $order) {

                            ordercouponmapper::where('order_id', $order)->forceDelete();
                            payumoney::where('order_id', $order)->forceDelete();
                            OrderHistory::where('order_id', $order)->forceDelete();
                            OrderCancellation::where('order_id', $order)->forceDelete();
                            OrderPaymentMode::where('order_id', $order)->forceDelete();
                            DailySummary::where('report_date',$summary_date)->where('outlet_id',$outlet->id)->forceDelete();

                            $order_items = OrderItem::where("order_id",$order)->get();
                            if ( isset($order_items) && sizeof($order_items) > 0 ) {

                                foreach ( $order_items as  $or_itm ) {

                                    //delete order item attributes
                                    order_item_attributes::where('order_item_id',$or_itm->id )->forceDelete();

                                    //delete order item options
                                    OrderItemOption::where('order_item_id',$or_itm->id )->forceDelete();

                                }

                            }

                            $order_detail = order_details::find($order);
                            if(isset($order_detail) && sizeof($order_detail)>0) {
                                //Order Kot Delete
                                $kots = Kot::where("order_unique_id", $order_detail->order_unique_id)->forceDelete();

                                //Order Delete
                                $order_detail->forceDelete();
                            }

                        }
                    }

                }
            }
        }
	}
}
