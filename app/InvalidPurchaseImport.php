<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class InvalidPurchaseImport extends Model {

    protected $table ='invalid_purchase_import';
    use SoftDeletingTrait;

    protected $softDelete = true;
}
