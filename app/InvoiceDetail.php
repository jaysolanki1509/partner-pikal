<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model {
    protected $table ='invoice_details';
    protected $fillable = array
    (
        'order_id',
        'sub_total',
        'total',
        'taxes',
        'round_off',
        'discount'
    );
}