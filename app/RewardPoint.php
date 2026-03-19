<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardPoint extends Model {

//    use SoftDeletes;
    protected  $fillable =['user_id','outlet_id','debit','credit','balance','desc'];

}
