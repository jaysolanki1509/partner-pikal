<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Company;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckPermission extends Model
{

    public static function Check_Permission($userid,$controller_name)
    {
        //return "true";
        $has_permission = "false";
        if($userid){
         $user=Owner::where('id',$userid)->first();
            if(!empty($user)){
                $role = $user->role;
                if(!empty($role)){
                    $role_permissions = $role->role_permissions;
                  if(!empty($role_permissions)){
                        $has_permission = "false";
                        foreach($role_permissions as $role_permission){
                            if($controller_name == $role_permission->permission->controller_name && $action_name == $role_permission->permission->action_name){
                                // if access found then redirect to that page
                                $has_permission = "true";
                            }
                        }
                        // if access not found then redirect to company dashboard page
                        return $has_permission;
                    }else{
                        // if access not found then redirect to company dashboard page
                        return $has_permission;
                    }

                }
            }
        }
    }


}
