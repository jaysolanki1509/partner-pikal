<?php namespace App\Http\Controllers\Api\v3;

use App\order_details;
use App\OrderItem;
use App\OutletMapper;
use App\Owner;
use App\Tables;
use App\Utils;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TablesController extends Controller
{
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this

    }

    public function tablesList() {

        $owner_id = Auth::id();
        //$admin_id = Owner::menuOwner();
        $sess_outlet_id = Session::get('outlet_session');

        $tables = Tables::join('outlets as o', 'o.id', '=', 'tables.outlet_id')->select('tables.*', 'o.name as name','o.order_lable as order_lable')
                            ->where('outlet_id', $sess_outlet_id)
                            ->get();

        return Response::json(array(
            'status' => 'success',
            'data'=>$tables,
            'statuscode' => 200,
            200));

    }

    public function tableForm(){

        $user_id = Auth::id();
        $outlets = OutletMapper::join('outlets as ot','ot.id','=','outlets_mapper.outlet_id')
            ->where('outlets_mapper.owner_id',$user_id)->get();

        $outlet_list = array();
        foreach($outlets as $outlet) {
            $outlet_list[$outlet->id]=$outlet->name;
        }

        $shapes = Utils::tableShapeArray();

        $data['outlets'] = $outlet_list;
        $data['shape'] = $shapes;

        return Response::json(array(
            'status' => 'success',
            'data'=>$data,
            'statuscode' => 200,
            200));
    }

    public function tableStore(){
        $rules = array(
            'outlet_id' => 'Required',
            'table_no' => 'Required',
            'no_of_person' => 'Integer|Min:1',
        );

        $validate = Validator::make(Request::all(), $rules);

        if ( $validate ) {

            $outlet_id = Request::get('outlet_id');
            $table_no = strtoupper(Request::get('table_no'));
            $table_level_id = strtolower(Request::get('table_level_id'));

            $same_table_number = Tables::where('outlet_id','=',$outlet_id)
                                ->where('table_no','=',$table_no)->first();

            if(isset($same_table_number) && sizeof($same_table_number)>0){

                return Response::json(array(
                    'message' => 'Table number is already available.',
                    'status' => 'error',
                    'data' => 'sametable',
                    'statuscode' => 501,
                    501));

            }

            $owner_id = Auth::id();
            $error = 0;
            $table = new Tables();
            $table->shape = Request::get('shape');
            $table->outlet_id = $outlet_id;
            $table->table_no = $table_no;
            $table->table_level_id = $table_level_id;
            $table->no_of_person = Request::get('no_of_person');
            $table->created_by = $owner_id;
            $table->updated_by = $owner_id;
            $success = $table->save();

            if ($success) {
                return Response::json(array(
                    'message' => 'Table saved successfully.',
                    'status' => 'success',
                    'data' => $success,
                    'statuscode' => 200,
                    200));
            } else {
                return Response::json(array(
                    'message' => 'Some Problem is Detacted Please Try Again.',
                    'status' => 'error',
                    'data' => $success,
                    'statuscode' => 500,
                    500));
            }
        }else{

            return Response::json(array(
                'message' => 'Please check input value and try again.',
                'status' => 'error',
                'data' => 'validation',
                'statuscode' => 500,
                500));

        }
    }

    public function editForm($id){

        $table_details = Tables::find($id);

        if(isset($table_details) && sizeof($table_details)>0){

            return Response::json(array(
                'status' => 'success',
                'data' => $table_details,
                'statuscode' => 200,
                200));

        }else{

            return Response::json(array(
                'message' => 'Table not found',
                'status' => 'error',
                'data' => 'notable',
                'statuscode' => 402,
                402));
        }
    }

    public function updateTable(){

        $rules = array(
            'outlet_id' => 'Required',
            'table_no' => 'Required',
            'no_of_person' => 'Integer|Min:1',
        );

        $validate = Validator::make(Request::all(), $rules);

        if ( $validate ) {

            $outlet_id = Request::get('outlet_id');
            $table_no = strtoupper(Request::get('table_no'));
            $table_level_id = Request::get('table_level_id');

            $table_id = Request::get('table_id');
            $same_table_number = Tables::where('outlet_id','=',$outlet_id)
                ->where('table_no','=',$table_no)->where('id',"!=",$table_id)->first();
            if(isset($same_table_number) && sizeof($same_table_number)>0){

                return Response::json(array(
                    'message' => 'Table number is already available.',
                    'status' => 'error',
                    'data' => 'sametable',
                    'statuscode' => 501,
                    501));

            }

            $owner_id = Auth::id();
            $error = 0;
            $table = Tables::find($table_id);
            $table->shape = Request::get('shape');
            $table->outlet_id = $outlet_id;
            $table->table_level_id = $table_level_id;
            $table->table_no = $table_no;
            $table->no_of_person = Request::get('no_of_person');
            $table->updated_by = $owner_id;
            $success = $table->save();

            if ($success) {
                return Response::json(array(
                    'message' => 'Table saved successfully.',
                    'status' => 'success',
                    'data' => $success,
                    'statuscode' => 200,
                    200));
            } else {
                return Response::json(array(
                    'message' => 'Some Problem is Detacted Please Try Again.',
                    'status' => 'error',
                    'data' => $success,
                    'statuscode' => 500,
                    500));
            }
        }else{
            return Response::json(array(
                'message' => 'Please check input value and try again.',
                'status' => 'error',
                'data' => 'validation',
                'statuscode' => 500,
                500));
        }
    }

    public function destroyTable($id){

        if(isset($id)) {

            $result = Tables::where('id', $id)->delete();
            return Response::json(array(
                'message' => 'Table delete successfully.',
                'status' => 'success',
                'data' => $result,
                'statuscode' => 200,
                200));
        }else{

            return Response::json(array(
                'message' => 'Please check input value and try again.',
                'status' => 'error',
                'data' => 'url',
                'statuscode' => 500,
                500));
        }
    }

    public function unoccupyTable($id){

        if(isset($id)) {

            $table = Tables::find($id);
            $table->status = null;
            $table->occupied_by = null;
            $table->occupied_mobile = null;
            $table->updated_by = Auth::id();
            $result = $table->save();

            if($result) {

                return Response::json(array(
                    'message' => 'Table unoccupy successfully.',
                    'status' => 'success',
                    'data' => $result,
                    'statuscode' => 200,
                    200));
            }else{

                return Response::json(array(
                    'message' => 'Table is still unoccupied please try again.',
                    'status' => 'error',
                    'data' => 'data',
                    'statuscode' => 501,
                    501));

            }
        }else{

            return Response::json(array(
                'message' => 'Please check input value and try again.',
                'status' => 'error',
                'data' => 'url',
                'statuscode' => 500,
                500));
        }
    }

}