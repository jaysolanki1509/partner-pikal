<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Customer extends Model {

    protected $table ='users';

    protected $softDelete = true;

    protected $fillable = array
    (
    );

}
