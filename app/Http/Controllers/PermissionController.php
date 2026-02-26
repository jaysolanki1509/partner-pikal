<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 8/3/2015
 * Time: 11:09 AM
 */

namespace App\Http\Controllers;
use App\Owner;
use Illuminate\Support\Facades\Auth;
use App\Roles;
use App\Permissions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Kodeine\Acl\Models\Eloquent\Permission;


class PermissionController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $role=Roles::all();
        $permission=Permissions::all();
        return view('permission.index',array('role'=>$role,'permission'=>$permission));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return view('owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //print_r(Input::all());
        DB::table('permission_role')->where('role_id',Input::get('role_id'))->delete();
        $max_permissions=DB::table('permissions')->max('id');
        for($i=0;$i<=$max_permissions;$i++)
        {
            if(Input::get($i)!='')
                DB::table('permission_role')->insert(
                    ['permission_id' => $i, 'role_id' => Input::get('role_id')]
                );
        }
        return Redirect('/map_permissions')->with('success', 'Permissions Assigned to Role');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function hasPermission($permission)
    {
        $role_id=Auth::user()->get('role_id');
        return $role_id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    public function permission_role(){
        $role_id=Input::get('role_id');
        $permission=DB::table('permission_role')->select('permission_id')->where('role_id',$role_id)->get();
        //print_r($permission);
        return json_encode($permission);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getselectedpermissions(){
        $role_id=Input::get('role_id');

        $permissions=DB::table('permission_role')->where('role_id',$role_id)->get();

        return $permissions;
    }
}