<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LogLevel extends Model
{


    protected $table = 'log_level';
    use SoftDeletes;
    protected $softDelete = true;

    public function insertRecord($outlet_id,$owner_id,$level){

        $log_level = new LogLevel();
        $log_level->outlet_id = $outlet_id;
        $log_level->owner_id = $owner_id;
        $log_level->level = $level;
        $log_level->save();


    }

}