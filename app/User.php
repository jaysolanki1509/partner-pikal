<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
//use Zizaco\Entrust\Traits\EntrustUserTrait;
//use Kodeine\Acl\Traits\HasPermission;
//use Kodeine\Acl\Traits\HasRole;
use Bican\Roles\Contracts\HasRoleAndPermissionContract;
use Bican\Roles\Traits\HasRoleAndPermission;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissionContract {

	use Authenticatable, CanResetPassword, HasRoleAndPermission;//,HasRole; //EntrustUserTrait;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 **/
   // protected $connection = 'mongodb';
    protected $table = 'owners';
    protected $softDelete = true;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_name', 'email','timezone', 'password','role_id','contact_no','gender','state','city'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    //A user can have many Outlets.

    public function outlet()
    {
        return $this->hasMany('App\Outlet','owner_id');
    }


    public static function ownerById($owner_id){
        $owner=Owner::where('id',$owner_id)->first();
        return $owner;
    }

    public static function getOwnerOrderReceive($owner_id, $res_id) {

        $order_receive = '';

        $owner = OutletMapper::where("owner_id",$owner_id)->where("outlet_id",$res_id)->get();
        if ( sizeof($owner) > 0 && isset($owner->order_receive) && $owner->order_receive != '' ) {
            $order_receive = json_decode($owner[0]->order_receive);
        }
        return $order_receive;
    }



}
