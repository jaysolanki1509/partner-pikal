<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 8/3/2015
 * Time: 11:09 AM
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Roles;
use App\Permissions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Termsandcondition;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;


class TermsandconditionController extends Controller {

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
        $terms=Termsandcondition::all();

        return view('termsandcondition.index',array('terms'=>$terms[0]->terms_condition));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {


        return view('termsandcondition.create',array('action'=>'add'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $messages = [
            'terms.required' => 'Terms is required!',
        ];

        $p = Validator::make(Request::all(), [
            'terms' => 'required',
        ], $messages);
        if (isset($p) && $p->passes()) {
            $terms = Termsandcondition::all();
            if (sizeof($terms) > 0) {
                $termsandcondition = Termsandcondition::find($terms[0]->{'id'});
                $termsandcondition->terms_condition = Request::get('terms');
                $termsandcondition->save();
                return view('termsandcondition.index', array('terms' => $termsandcondition));
            } else {
                $termsandcondition = new Termsandcondition();
                $termsandcondition->terms_condition = Request::get('terms');
                $termsandcondition->save();
                return view('termsandcondition.index', array('terms' => $termsandcondition));
            }
        } else {
            return redirect()->back()->withInput(Request::all())->withErrors($p->errors());
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