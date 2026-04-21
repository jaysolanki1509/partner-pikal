<?php

namespace Savitriya\Icici_upi;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;


class UpiTransactionStatusChangeEvent extends Event
{
    use SerializesModels;

    public $txnId;
    public $billNumber;
    public $oldStatus;
    public $oldStatusString;
    public $status;
    public $statusString;
    public $amount;
    public $playerVa;
    public $note;
    public $initDate;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {

    }



}
