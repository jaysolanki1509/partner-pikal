<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Pasword_reset extends Model {

    protected $table = 'password_resets';
    protected $timestamp = true;
    protected $dates =['created_at'];
}
