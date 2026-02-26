<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class CreditNote extends Model {

    use SoftDeletingTrait;
    protected $softDelete = true;
    protected $table ='credit_notes';


}
