<?php

namespace Savitriya\Icici_upi;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class UpiTransactionInitiatedEvent extends Event
{
    use SerializesModels;

    public $txnId;
    public $billNumber;
    public $status;
    public $statusString;
    public $amount;
    public $playerVa;
    public $note;
    public $initDate;
    public $success;
    public $successMessage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
}
