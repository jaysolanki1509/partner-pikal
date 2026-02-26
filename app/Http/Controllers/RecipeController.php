<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Outlet;
use Illuminate\Support\Facades\Session;

class RecipeController extends Controller {

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
        $loggedinuserid=Auth::user()->id;
        $recipe=Recipe::where('owner_id',$loggedinuserid)->get();

        return view('recipe.index',array('recipe'=>$recipe));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $owner_id=Auth::user()->id;
        $Outlet=Outlet::getoutletbyownerid($owner_id);
        $totalOutletunderuser=count($Outlet);

        return view('recipe.create',array('Outlet'=>$Outlet,'totalOutletcount'=>$totalOutletunderuser,'action'=>'add','get'=>'add','set'=>'add','test'=>'add','create'=>'add','make'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $recipe=new Recipe();
        $recipe->owner_id=Auth::user()->id;
        //$recipe->outlet_id=Input::get('Outlet_name');
        $recipe->title=Input::get('title');
        $recipe->ingrediants=Input::get('recipeingrediants');
        $recipe->recipes=Input::get('recipe');
        $recipe->shop_url=Input::get('shop_url');
        $recipe->ingrediants_url=Input::get('ingrediants_url');
        $success = $recipe->save();
        if($success)
        {
            return Redirect('/recipe')->with('success', 'Recipe added successfully');
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $owner_id=Auth::user()->id;
        $Outlet=Outlet::getoutletbyownerid($owner_id);
        $totalOutletunderuser=count($Outlet);
        $recipe=Recipe::find($id);

        return view('recipe.edit', array('recipe'=>$recipe,'Outlet'=>$Outlet,'totalOutletcount'=>$totalOutletunderuser,'action' => 'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $user_id=Auth::user()->id;
        $recipe=Recipe::find($id);
        $recipe->title=Input::get('title');
        $recipe->ingrediants=Input::get('recipeingrediants');
        $recipe->recipe=Input::get('recipe');
        $recipe->shop_url=Input::get('shop_url');
        $recipe->ingrediants_url=Input::get('ingrediants_url');


        $success=$recipe->save();

        if($success){
            return Redirect('/recipe')->with('success', 'Recipe updated successfully');
        }
        else
        {
            return Redirect('/recipe')->with('error', 'Failed');
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        Recipe::where('id',$id)->delete();
        Session::flash('flash_message', 'Successfully deleted Recipe!');
        return Redirect::to('recipe');
	}

}
