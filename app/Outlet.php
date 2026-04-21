<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Outlet extends Model
{
    protected $table = 'outlets';
    protected $fillable = array(
        'name',
        'company_name',
        'pincode',
        'address',
        'famous_for',
        'contact_no',
        'email_id',
        'url',
        'service_type',
        'active',
        'avg_cost_of_two',
        'minimum_order_price',
        'contact_no',
        'outlet_image',
        'tinno',
        'servicetax_no',
        /*'service_tax',*/
        'taxes',
        'vat',
        'upi',
        'report_emails',
        'biller_emails',
        'authorised_users'
    );

    public function user()
    {
        return $this->belongsTo('App\Owner', 'id', 'id');
    }
    public function outletcuisinetype()
    {
        return $this->hasMany('App\OutletCuisineType', 'outlet_id');
    }
    public function outlettypemapper()
    {
        return $this->hasMany('App\OutletTypeMapper', 'outlet_id');
    }

    public function outletlatlong()
    {
        return $this->hasMany('App\Outletlatlong', 'outlet_id');
    }

    public function orderdetail()
    {
        return $this->hasMany('App\OrderDetails', 'outlet_id');
    }
    public function time_slots()
    {
        return $this->hasMany('App\Timeslot', 'outlet_id');
    }

    public static function Outletbyid($outlet_id)
    {
        echo "Hello" . $outlet_id;
        exit;
        $Outlet = Outlet::where('id', $outlet_id)->get();
        return $Outlet;
    }
    public static function Outletbyid2($outlet_id)
    {
        $Outlet = Outlet::where('id', $outlet_id)->first();
        return $Outlet;
    }
    public static function Outletbyownerid($owner_id)
    {
        $Outlet = Outlet::where('owner_id', $owner_id)->get();
        return $Outlet;
    }
    public static function findOutlet($outlet_id)
    {
        $Outlet = Outlet::find($outlet_id);
        return $Outlet;
    }

    public static function searchoutlet($start, $limit, $location, $cuistype, $resttype, $restname, $costmin, $costmax, $lat, $long, $delivery_type)
    {
        $rest = DB::table('outlets');
        $distancearray = '';
        if (isset($start) && $start != "") {
            $rest->skip($start)->take($limit);
        }
        if (isset($location) && $location != "") {
            $getlocality = locality::where('locality', 'LIKE', '%' . htmlentities($location) . '%')->get();
            $rest->where('locality', '=', $getlocality[0]->locality_id);
        }
        if (isset($delivery_type) && $delivery_type != "") {
            $rest->where('service_type', 'LIKE', '%' . $delivery_type . '%');
        }
        if ($costmin != "" ||  $costmax != "") {
            $rest->whereBetween('avg_cost_of_two', array($costmin, $costmax));
        }

        if (isset($restname) && $restname != "") {
            $rest->where('name', 'LIKE', '%' . $restname . '%');
        }

        if (isset($cuistype) && $cuistype != "") {
            $cutya = explode(',', $cuistype);
            $cuitypeid = array();
            foreach ($cutya as $c) {
                $cuitype = DB::table('cuisine_types')->where('type', '=', htmlentities($c))->get();

                array_push($cuitypeid, $cuitype[0]->id);
            }
            $Outletcuisinetypeid = array();
            foreach ($cuitypeid as $cty) {

                $cuirestid = DB::table('outlet_cuisine_types')->where('cuisine_type_id', '=', $cty)->get();

                foreach ($cuirestid as $asct) {
                    //print_r($asct);
                    array_push($Outletcuisinetypeid, $asct->outlet_id);
                }
            }

            $rest->whereIn('id', $Outletcuisinetypeid);
        }
        if (isset($resttype) && $resttype != "") {
            $cutya = explode(',', $resttype);
            $cuitypeid = array();
            foreach ($cutya as $c) {
                $cuitype = DB::table('outlet_types')->where('type', '=', $c)->get();

                array_push($cuitypeid, $cuitype[0]->id);
            }
            $Outletcuisinetypeid = array();
            foreach ($cuitypeid as $cty) {

                $cuirestid = DB::table('outlet_types_mapper')->where('outlet_type_id', '=', $cty)->get();


                foreach ($cuirestid as $asct) {

                    array_push($Outletcuisinetypeid, $asct->outlet_id);
                }
            }
            $rest->whereIn('id', $Outletcuisinetypeid);
        }


        $restdetails = $rest->where('active', '!=', 'No')->orderBy('id', 'desc')->groupBy('name')->get();

        return array('restaurantdetails' => $restdetails, 'distancearray' => $distancearray);
    }
    //for calculating distance between user and Outlet
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public static function getoutletbyownerid($id)
    {
        $Outlet = Outlet::where('owner_id', $id)->get();
        return $Outlet;
    }

    public static function getoutletbyname($name)
    {
        $existingOutlets = DB::table('outlets')->where('name', $name)->first();
        return $existingOutlets;
    }

    public static function getfirstoutletbystatus($id)
    {
        $Outlet = Outlet::where('id', $id)->first();
        return $Outlet;
    }

    public static function getOutletIdByOwnerId($owner_id)
    {
        $Outlet_id = Outlet::where('owner_id', $owner_id)->first();
        return $Outlet_id;
    }

    public static function getTotalHours($outlet_id, $start_date)
    {
        $first = OrderDetails::where('outlet_id', $outlet_id)->wherebetween('table_start_date', array($start_date, Carbon::parse($start_date)->endOfDay()))
            ->first();
        $last = OrderDetails::where('outlet_id', $outlet_id)->wherebetween('table_start_date', array($start_date, Carbon::parse($start_date)->endOfDay()))
            ->orderby('table_end_date', 'desc')
            ->first();

        if (isset($first) && sizeof($first) > 0) {
            $diff = ((new \DateTime($first->table_start_date))->diff(new \DateTime($last->table_end_date)));
            $diff = $diff->format("%H:%I");
        } else {
            $diff = "00:00";
        }


        return $diff;
    }

    public static function getOutletInfo($outlet_id)
    {

        $info = array();
    }
}
