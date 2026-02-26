<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Menu;
use App\Unit;
use App\Vendor;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use Illuminate\Support\Facades\Response;
class RestController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
    /**
     * Display a listing of the resource.
     * GET /rest
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    public function autocomplete() {

        $term = Request::get('searchTerm');
        $flag = Request::get('flag');
        $parent_id = Request::get('parent_id');

        $user_id = Auth::id();
        $admin = Auth::user()->created_by;

        $user_arr[] = $user_id;

        if( isset($admin)) {
            $user_arr[] = $admin;

        }

        $response = array();
        $result = array();

        if ( $flag == 'language' ) {
            $result = Language::whereRaw('name like '."'$term%'")->get();
        }
        else if( $flag == 'item')
        {
            $result = Menu::leftjoin('menu_titles','menu_titles.id', '=','menus.menu_title_id')
                ->leftjoin('unit','unit.id','=','menus.unit_id')
                ->whereRaw('menus.item like '."'%$term%'")
                ->whereIn('menus.created_by',$user_arr)
                ->select('menus.id as id','menus.item as item','menus.secondary_units as other_units','menus.order_unit as order_unit','menu_titles.title as category','menus.unit_id as unit_id','unit.name as unit_name');

            if ( isset($parent_id) && $parent_id != '' ) {
                $result = $result->where('menu_titles.id',$parent_id)->get();
            } else {
                $result = $result->get();
            }

        }
        else if ( $flag == 'vendor' )
        {
            $result = Vendor::whereRaw('name like '."'$term%'")
                            ->where('created_by',$user_id)
                            ->get();

        }

        if ( isset($result) && sizeof($result) > 0 ) {
            $i = 0;
            //$response['Total'] = sizeof($result);
            foreach ( $result as $res ) {

                if ($flag == 'language') {

                    $response[$i]['id'] = $res->id;
                    $response[$i]['code'] = $res->code;
                    $response[$i]['text'] = $res->name;

                }
                if ( $flag == 'vendor' )
                {
                    $response[$i]['id'] = $res->id;
                    $response[$i]['text'] = $res->name;
                }
                if ( $flag == 'item' )
                {
                    $response[$i]['id'] = $res->id;
                    $response[$i]['text'] = $res->item." (".$res->category.")";
                    $response[$i]['unit_id'] = $res->unit_id;
                    $response[$i]['unit_name'] = $res->unit_name;
                    $response[$i]['order_unit'] = $res->order_unit;

                    $unit = array();
                    $j = 0;
                    $unit[$j][$res->unit_id] = $res->unit_name;

                    if ( isset($res->other_units) && sizeof($res->other_units) > 0 ) {

                        if( isset($res->other_units) && $res->other_units != '' ) {
                            $j++;
                            $units = json_decode($res->other_units);
                            foreach( $units as $key=>$u ) {
                                $unit[$j][$key] = Unit::find($key)->name;
                                $j++;
                            }
                        }
                        $response[$i]['other_units'] = $unit;
                    } else {
                        $response[$i]['other_units'] = $unit;
                    }
                }
                $i++;
            }


        }
        return Response::json($response);
    }


}