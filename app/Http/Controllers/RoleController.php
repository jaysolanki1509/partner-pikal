<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 8/3/2015
 * Time: 11:09 AM
 */

namespace App\Http\Controllers;
//use App\Permission;
//use Kodeine\Acl\Models\Eloquent\Permission;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class RoleController extends Controller {

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

        $role=Role::all();

        $owners=Owner::all();

        return view('role.index',array('role'=>$role,'owner'=>$owners));
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
        $user=Owner::where('id',Input::get('user_id'))->first();
        $role_id=Input::get('role_id');
        Owner::where('id', Input::get('user_id'))->update(['role_id' => $role_id]);

        return Redirect('/check_roles')->with('success', 'Role is assigned ');
    }

    public function permissions(){
        $role=Role::all();
        $permissions=Permission::all();
        return view('role.permissions',array('role'=>$role,'permissions'=>$permissions));
    }

    public function store_permission(){
        print_r(Input::all());exit;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

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
}