<?php
namespace Savitriya\Icici_upi;

use Illuminate\Database\Eloquent\Model;

class IciciUpiTxn extends Model {
    protected $table = 'icici_upi_transaction';
    protected $guarded = [];
    protected $primaryKey = 'txnid';
}