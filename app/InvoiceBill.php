<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class InvoiceBill extends Model {


    protected $table ='invoice_bills';
    use SoftDeletingTrait;

    protected $fillable = array
    (
        'invoice_no',
        'invoice_id',
        'vendor_id',
        'total',
        'status',
        'invoice_date',
        'verified_by',
        'verified_date',
        'created_by',
        'updated_by'
    );


}
