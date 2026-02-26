<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditNote extends Model {

    use SoftDeletes;
    protected $softDelete = true;
    protected $table ='credit_notes';


}
