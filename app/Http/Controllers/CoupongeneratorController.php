<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\order_details;
use App\Outlet;
use App\Menu;
use App\OutletMapper;
use App\Owner;
use App\Status;
use App\OrderItem;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Piyushpatil\Androidpushnotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\CouponCodes;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CreateCouponRequest;
use Illuminate\Support\Facades\Redirect;

class CoupongeneratorController extends Controller
{

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
        $menu_owner = Owner::menuOwner();
        $coupon = CouponCodes::where('created_by', $menu_owner)->get();

        return view('coupongenerator.index', array('coupon' => $coupon));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $user_id = Auth::id();
        $outlets = OutletMapper::join('outlets as ot', 'ot.id', '=', 'outlets_mapper.outlet_id')
            ->where('outlets_mapper.owner_id', $user_id)->get();

        $data = array();

        foreach ($outlets as $outlet) {
            $data[$outlet->id] = $outlet->name;
        }

        //print_r($outlet_array);exit;
        return view('coupongenerator.create', array('outlets' => $data, 'action' => 'add', 'get' => 'add', 'set' => 'add', 'test' => 'add', 'create' => 'add', 'make' => 'add'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Requests\CreateCouponRequest $request)
    {
        $coupons = CouponCodes::getMyCouponCode();
        foreach ($coupons as $coupon) {
            if (strcmp($coupon->coupon_code, $request->coupon_code) == 0) {
                return redirect()->back()->withInput(Input::all())->with('error', 'Coupon code is already taken.');
            }
        }
        $menu_owner = Owner::menuOwner();
        $coupon_code = new CouponCodes();
        $coupon_code->coupon_code = $request->coupon_code;
        $coupon_code->min_value = $request->min_value;
        $coupon_code->max_value = $request->max_value;
        $coupon_code->activated_datetime = $request->activated_datetime;
        $coupon_code->expire_datetime = $request->expire_datetime;

        if ($request->Browser == 1) {
            if (is_numeric($request->percentage) && $request->percentage != null && $request->percentage > 0 && $request->percentage <= 100)
                $coupon_code->percentage = $request->percentage;
            else
                return Redirect::back()->withInput(Input::all())->with('error', 'Numeric Percentage between 1 and 100 value require.');
            $coupon_code->min_value = $request->min_value;
            if ($request->max_value != null)
                $coupon_code->max_value = $request->max_value;
            else
                return Redirect::back()->withInput(Input::all())->with('error', '');
        } elseif ($request->Browser == 2) {
            if (is_numeric($request->min_value) && $request->value != null)
                $coupon_code->value = $request->value;
            else
                return Redirect::back()->withInput(Input::all())->with('error', 'Numeric Fixed value require.');
            $coupon_code->min_value = $request->min_value;
            $coupon_code->percentage = '';
            $coupon_code->max_value = 0.00;
        }
        $coupon_code->value = $request->value;
        $coupon_code->created_by = $menu_owner;
        $outlet_id = Session::get('outlet_session');
        $coupon_code->outlet_ids = $outlet_id;

        $coupon_code->no_of_users = $request->no_of_users;

        $success = $coupon_code->save();
        if ($success) {
            return Redirect('/coupongenerator')->with('success', 'Coupon added successfully');
        }

        //        return view('coupongenerator.index');

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
        $coupon = CouponCodes::find($id);
        $user_id = Auth::id();
        $outlets = OutletMapper::join('outlets as ot', 'ot.id', '=', 'outlets_mapper.outlet_id')
            ->where('outlets_mapper.owner_id', $user_id)->get();

        $data = array();

        foreach ($outlets as $outlet) {
            $data[$outlet->id] = $outlet->name;
        }
        return view('coupongenerator.edit', array('outlets' => $data, 'coupon' => $coupon, 'action' => 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, CreateCouponRequest $request)
    {
        $coupons = CouponCodes::getMyCouponCode();
        $current_coupon = CouponCodes::select('coupon_code')->where('id', $id)->first();
        foreach ($coupons as $coupon) {
            if (strcmp($current_coupon->coupon_code, $request->coupon_code) != 0) {
                if (strcmp($coupon->coupon_code, $request->coupon_code) == 0) {
                    return Redirect::back()->withInput(Input::all())->with('error', 'Coupon code is already taken.');
                }
            }
        }
        $user_id = Auth::user()->id;
        $coupon_code = CouponCodes::find($id);
        if ($request->Browser == 1) {
            if (is_numeric($request->percentage) && $request->percentage != null && $request->percentage > 0 && $request->percentage <= 100)
                $coupon_code->percentage = $request->percentage;
            else
                return Redirect::back()->withInput(Input::all())->with('error', 'Numeric Percentage between 1 and 100 value require.');
            $coupon_code->min_value = $request->min_value;
            if ($request->max_value != null)
                $coupon_code->max_value = $request->max_value;
            else
                return Redirect::back()->withInput(Input::all())->with('error', '');

            $coupon_code->value = '';
        } elseif ($request->Browser == 2) {
            if (is_numeric($request->min_value) && $request->value != null)
                $coupon_code->value = $request->value;
            else
                return Redirect::back()->withInput(Input::all())->with('error', 'Numeric Fixed value require.');
            $coupon_code->min_value = $request->min_value;
            $coupon_code->max_value = '';
            $coupon_code->percentage = '';
        }
        $coupon_code->coupon_code = $request->coupon_code;

        $coupon_code->activated_datetime = $request->activated_datetime;
        $coupon_code->expire_datetime = $request->expire_datetime;


        if ($request->outlets != null)
            $coupon_code->outlet_ids = implode(",", $request->outlets);
        else
            return Redirect::back()->with('error', 'Select Outlet field');

        $coupon_code->no_of_users = $request->no_of_users;
        if (isset($coupon_code->no_of_users) == '') {
            $coupon_code->no_of_users = '';
        }
        $success = $coupon_code->save();

        if ($success)
            return Redirect('/coupongenerator')->with('success', 'Coupon updated successfully');
        else
            return Redirect('/coupongenerator')->with('error', 'Failed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        CouponCodes::where('id', $id)->delete();
        Session::flash('flash_message', 'Successfully deleted Coupon!');
        return Redirect::to('coupongenerator');
    }
}
