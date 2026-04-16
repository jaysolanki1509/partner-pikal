<?php

namespace App\Console\Commands;

use App\OrderDetails;
use App\Outlet;
use Illuminate\Console\Command;

class UploadSalesDataToZoho extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pikal:uploadsalesdatatozoho';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload Sales data to zoho.';

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

        $outlets = Outlet::where('zoho_config', 1)->get();

        $to = date('Y:m:d H:i:s');
        $from =  date('Y-m-d H:i:s', strtotime('-1 day'));

        if (isset($outlets) && sizeof($outlets) > 0) {

            foreach ($outlets as $ot) {

                OrderDetails::syncZohoOrders($ot->id, $from, $to);
            }
        }
    }
}
