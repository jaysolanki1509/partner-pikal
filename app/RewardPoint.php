<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RewardPoint extends Model {

//    use SoftDeletingTrait;
    protected  $fillable =['user_id','outlet_id','debit','credit','balance','desc'];

}
