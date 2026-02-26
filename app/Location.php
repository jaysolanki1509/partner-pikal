<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Location extends Model {

    protected $table ='locations';
    use SoftDeletes;

    protected $softDelete = true;

    protected $fillable = array
    (
        'name',
        'created_by',
        'updated_by',

    );

    public function user()
    {
        return $this->belongsTo('App\Owner','id','created_by');
    }

    public static function getLocations( $owner_id) {

        /*location array*/
        $locations = array('' => 'Select Location');
        //$locations_list = Location::where('created_by',$owner_id)->lists('name','id');

        $sess_outlet_id = Session::get('outlet_session');
        $login_user =Auth::id();
        $user = Owner::find($login_user);

        if(isset($sess_outlet_id) && $sess_outlet_id != '' ){
            $locations_list = Location::getLocationByOutletId($sess_outlet_id);
        }
        else if(isset($user->created_by) && $user->created_by != '') {
            $locations_list = Location::where('created_by',$user->created_by)->get();
        } else {
            $locations_list = Location::where('created_by',$user->id)->get();
        }

        if( isset($locations_list) && sizeof($locations_list) > 0 ) {
            foreach ( $locations_list as $loc) {
                $locations[$loc->id] = $loc->name;
            }
        }

        return $locations;

    }

    public static function getLocationById($id){

        $locaton = Location::find($id);
        return $locaton;

    }

    public static function getLocationByOutletId($outlet_id){

        $location = Location::where('outlet_id',$outlet_id)->get();
        return $location;

    }

}
