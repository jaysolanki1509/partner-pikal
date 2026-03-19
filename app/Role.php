<?php namespace App;
use Illuminate\Database\Eloquent\Model;
class Role extends Model {
    protected $table = 'roles';
    protected $fillable = ['name','slug', 'description','level'];
    public function users()
    {
        return $this->hasMany('App\Owner', 'role_id', 'id');
    }
    public static function findRoleByName($role_name){
        return Role::where('slug',$role_name)->first();
    }
}/*namespace App;
use Illuminate\Database\Eloquent\Model;
//use Zizaco\Entrust\EntrustRole;
class Role extends EntrustRole {
    protected $table = 'roles';
    public function owners()
    {
        return $this->belongsToMany('App\Owner');
    }
    public function permissions()
    {
        return $this->belongsToMany('App\Permissions');
    }
}*/