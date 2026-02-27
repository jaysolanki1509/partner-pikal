<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
// use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
//use Zizaco\Entrust\Traits\EntrustUserTrait;
//use Kodeine\Acl\Traits\HasPermission;
//use Kodeine\Acl\Traits\HasRole;
use Bican\Roles\Contracts\HasRoleAndPermissionContract;
use Bican\Roles\Traits\HasRoleAndPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Owner extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissionContract {

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
	protected $fillable = ['account_id','user_name', 'email','timezone', 'password','role_id','created_by','contact_no','gender','state','city'];

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

    /*public function roles()
    {
        return $this->belongsToMany('App\Role','role_user','owner_id','role_id');
    }*/
    public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
    public function hasRole($roles)
    {
       /* $this->have_role = $this->getUserRole();
        // Check if the user is a root account
        //print_r($this->have_role->name);
        //print_r(Owner::checkIfUserHasRole("Root"));exit;
        if($this->have_role->name == $roles) {
            return true;
        }*/
        /*if(is_array($roles)){
            foreach($roles as $need_role){
                if($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else{
            return $this->checkIfUserHasRole($roles);
        }*/
        return false;
    }
    private function getUserRole()
    {
        return $this->role()->getResults();
    }
    private function checkIfUserHasRole($need_role)
    {
        return true;
        //return (strtolower($need_role)==strtolower($this->have_role->name)) ? true : false;
    }

    public static function ownerById($owner_id){
        $owner=Owner::where('id',$owner_id)->first();
        return $owner;
    }
    public static function outletByOwner($owner_id){
        $owner=DB::table('outlets_mapper')
                ->select('outlets.*')
                ->join('outlets','outlets.id','=','outlets_mapper.outlet_id')
                ->where('outlets_mapper.owner_id',$owner_id)
                ->where('outlets.active','Yes')
                ->get();
        return $owner;
    }

    public static function menuOwner(){
        $login_user =Auth::id();
        $owner= Owner::where('id',$login_user)->select('created_by')->first();
        if( isset($owner) && $owner->created_by!=""){
            $menu_owner=$owner->created_by;
        }else{
            $menu_owner=$login_user;
        }
        return $menu_owner;
    }

    public static function hasCreatedBy(){
        $login_user =Auth::id();
        $owner= Owner::where('id',$login_user)->select('created_by')->first();
        if($owner->created_by != "")
            return true;
        else
            return false;

    }

}
