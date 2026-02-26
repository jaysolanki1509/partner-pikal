<?php
/**
 * Created by PhpStorm.
 * User: tarika
 * Date: 15/4/15
 * Time: 3:24 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model {

    protected $table = 'timeslot';


    public static function gettimeslotbyoutletid($id){
        $timeslots=Timeslot::where('outlet_id',$id)->get();
        return $timeslots;
    }

    public static function deletetimeslotbyoutletid($id){
        Timeslot::where('outlet_id','=',$id)->delete();
    }
}
