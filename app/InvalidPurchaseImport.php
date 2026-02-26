<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvalidPurchaseImport extends Model {

    protected $table ='invalid_purchase_import';
    use SoftDeletes;

    protected $softDelete = true;
}
