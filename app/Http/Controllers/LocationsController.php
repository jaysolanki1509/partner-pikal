<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Location;
use App\Menu;
use App\MenuTitle;
use App\OutletMapper;
use App\Owner;
use App\StockLevel;
use DocuSign\eSign\Api\AuthenticationApi;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\ApiClient;
use DocuSign\eSign\ApiException;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\TemplateRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Zend\Validator\Sitemap\Loc;

class LocationsController extends Controller {

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
		$owner_id = Auth::id();
		$locations = Location::leftjoin('owners','locations.created_by', '=','owners.id')
							->where('locations.created_by',$owner_id)
							->select('locations.name as name','owners.user_name as created_by','locations.id as id','locations.outlet_id','locations.default_location')
							->get();

		return view('locations.index', array('locations' => $locations));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$owner_id = Auth::id();
		$outlets = OutletMapper::getOutletsByOwnerId();

		return view('locations.create',array('outlets'=>$outlets,'action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$messages = [
			'name.required' => 'Name is required!',
		];

		$p = Validator::make($request->all(), [
			'name' => 'required',
		],$messages);


		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Input::get('saveContinue');
			$name = Input::get('name');
			$outlet_id  = Input::get('outlet_id');
			$sess_outlet_id = Session::get('outlet_session');

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}
			$default_loc = Input::get('default_location');

			if ( isset($default_loc) && $default_loc != '' ) {
				if( isset($outlet_id) && $outlet_id != '' ) {
					$check_location = Location::where('outlet_id',$outlet_id)->where('default_location',1)->first();
					if ( isset($check_location) && sizeof($check_location) > 0 ) {
						Location::where('id',$check_location->id)->update(['default_location'=>0]);
					}
				} else {
					return redirect()->back()->withInput(Input::all())->with('error', 'Select Outlet for making location default!');
				}
			} else {
				$default_loc = 0;
			}


			$location = new Location();
			$location->name = $name;
			$location->outlet_id = $outlet_id;
			$location->default_location = $default_loc;
			$location->created_by = $owner_id;
			$location->updated_by = $owner_id;
			$result = $location->save();

			if ( $result ) {
				if ( isset($save_continue) && $save_continue == 'true' ) {
					return Redirect::route('location.create')->with('success','New Location has been added....');
				} else {
					return Redirect::route('location.index')->with('success','New Location has been added....');
				}

			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
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
		$owner_id = Auth::id();
		$location = Location::find($id);

		$outlets = OutletMapper::getOutletsByOwnerId();

		return view('locations.edit',array('outlets'=>$outlets,'location'=>$location,'action'=>'edit'));

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$messages = [
			'name.required' => 'Name is required!',
		];

		$p = Validator::make(Input::all(), [
			'name' => 'required',
		],$messages);

		if (isset($p) && $p->passes())
		{
			$owner_id = Auth::id();
			$save_continue = Input::get('saveContinue');
			$name = Input::get('name');
			$outlet_id  = Input::get('outlet_id');
			$sess_outlet_id = Session::get('outlet_session');

			if (isset($sess_outlet_id) && $sess_outlet_id != '') {
				$outlet_id = $sess_outlet_id;
			}
			$default_loc  = Input::get('default_location');

			if ( isset($default_loc) && $default_loc != '' ) {
				if( isset($outlet_id) && $outlet_id != '' ) {
					$check_location = Location::where('outlet_id',$outlet_id)->where('default_location',1)->first();
					if ( isset($check_location) && sizeof($check_location) > 0 ) {
						Location::where('id',$check_location->id)->update(['default_location'=>0]);
					}
				} else {
					return redirect()->back()->withInput(Input::all())->with('error', 'Select Outlet for making location default!');
				}
			} else {
				$default_loc = 0;
			}

			$location = Location::find($id);
			$location->updated_by = $owner_id;
			$location->name = $name;
			$location->outlet_id = $outlet_id;
			$location->default_location = $default_loc;
			$result = $location->save();

			if ( $result ) {
				return Redirect::route('location.index')->with('success', 'Location has been updated successfully!');
			}

		} else {
			return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
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
		Location::where('id',$id)->delete();
		Session::flash('success', 'Location has been deleted successfully!');
		return Redirect::to('location');
	}

	public function setStockLevel(Request $request) {

		$admin_id = Owner::menuOwner();
		$owner_id = Auth::id();

		if ($request->ajax()) {

			$loc_id = Input::get('loc_id');
			$cat_id = Input::get('cat_id');

			$arr = array();

			$menus = Menu::leftjoin('menu_titles as mt','mt.id', '=','menus.menu_title_id')
				->join('unit','unit.id','=','menus.unit_id')
				->select('menus.id as id','menus.item as item','mt.title as title','menus.menu_title_id as cat_id','unit.id as unit_id','unit.name as unit')
				->where('menus.created_by',$admin_id)
				->orderBy('menus.menu_title_id','asc')
				->orderBy('menus.id','asc');

			if ( isset($cat_id) && $cat_id != '' ) {
				$result = $menus->where('mt.id',$cat_id)->get();
			} else {
				$result = $menus->get();
			}

			if ( isset($result) && sizeof($result) > 0 ) {
				foreach( $result as $res ) {

					$arr[$res->id]['id'] = $res->id;
					$arr[$res->id]['item'] = $res->item;
					$arr[$res->id]['cat_id'] = $res->cat_id;
					$arr[$res->id]['cat_name'] = $res->title;
					$arr[$res->id]['unit_id'] = $res->unit_id;
					$arr[$res->id]['unit'] = $res->unit;
					//get stock level
					$stock = StockLevel::where('location_id',$loc_id)
										->where('item_id',$res->id)
										->first();

					if( isset($stock) && sizeof($stock) > 0 ) {
						$arr[$res->id]['order_qty'] = $stock->order_qty;
						$arr[$res->id]['reserved_qty'] = $stock->reserved_qty;
						$arr[$res->id]['opening_qty'] = $stock->opening_qty;
						$arr[$res->id]['request_item'] = $stock->request_item;
						$arr[$res->id]['req_fav_item'] = $stock->req_fav_item;
					} else {
						$arr[$res->id]['order_qty'] = '';
						$arr[$res->id]['reserved_qty'] = '';
						$arr[$res->id]['opening_qty'] = '';
                        $arr[$res->id]['req_fav_item'] = '';
					}
				}
			}

			return view('locations.stockLevelList',array('arr'=>$arr,'loc_id'=>$loc_id,'cat_id'=>$cat_id));

		}

		$locations = Location::getLocations($admin_id);
		$categories = MenuTitle::getCategoriesDropdown($admin_id);
		$categories[''] = 'All Categories';

		return view('locations.stockLevel',array('locations'=>$locations,'categories'=>$categories));

	}

	public function storeStockLevel() {

		$owner_id = Auth::id();
        //print_r(Input::all());exit;
		$loc_id = Input::get('loc_id');
		$cat_id = Input::get('cat_id');
		$item_id = Input::get('item_id');
		$opening_qty = Input::get('opening_qty');
		$reserved_qty = Input::get('reserved_qty');
		$order_qty = Input::get('order_qty');
        $request_item = Input::get('request_item');
		$req_fav_item = Input::get('req_fav_item');

		if( isset($item_id) && sizeof($item_id) > 0 ) {
			foreach( $item_id as $key=>$val ) {


				$check_item = StockLevel::where('item_id',$val)->where('location_id',$loc_id)->first();

				if ( isset($check_item) && sizeof($check_item) > 0 ) {

					$check_item->opening_qty = $opening_qty[$key];
					$check_item->order_qty = $order_qty[$key];
					$check_item->reserved_qty = $reserved_qty[$key];
					$check_item->category_id = $cat_id[$key];
					$check_item->updated_by = $owner_id;
					$check_item->request_item = isset($request_item[$val])?'true':'false';
					$check_item->req_fav_item = isset($req_fav_item[$val])?true:false;
					$check_item->save();

				} else {

					$add_item = new StockLevel();
					$add_item->category_id = $cat_id[$key];
					$add_item->item_id = $val;
					$add_item->location_id = $loc_id;
					$add_item->opening_qty = $opening_qty[$key];
					$add_item->order_qty = $order_qty[$key];
					$add_item->reserved_qty = $reserved_qty[$key];
					$add_item->created_by = $owner_id;
					$add_item->updated_by = $owner_id;
					$add_item->request_item = isset($request_item[$val])?'true':'false';
					$add_item->req_fav_item = isset($req_fav_item[$val])?true:false;
					$add_item->save();

				}

			}
		}

		return Redirect::to('/location/stock-level')->with('success','Stock level has been set successfully');

	}

	public function docSign() {



        return view('locations.doc', array());
    }

    public function docSignPdf() {

        $name = Input::get('name');
        $email = Input::get('email');
        //require_once(public_path('docusign-php-client-master/autoload.php'));
        // DocuSign account credentials & Integrator Key
        $username = "np@savitriya.com";
        $password = "nitin123";
        $integrator_key = "6f6eaa1d-ff7c-46f6-9458-3ea3dffbc401";

        // DocuSign environment we are using
        $host = "https://demo.docusign.net/restapi";
        // create a new DocuSign configuration and assign host and header(s)
        $config = new Configuration();
        $config->setHost($host);
        $config->addDefaultHeader("X-DocuSign-Authentication", "{\"Username\":\"" . $username . "\",\"Password\":\"" . $password . "\",\"IntegratorKey\":\"" . $integrator_key . "\"}");
        // instantiate a new docusign api client
        $apiClient = new ApiClient($config);
        // we will first make the Login() call which exists in the AuthenticationApi...
        $authenticationApi = new AuthenticationApi($apiClient);
        // optional login parameters
        $options = new AuthenticationApi\LoginOptions();
        // call the login() API
        $loginInformation = $authenticationApi->login($options);
        // parse the login results
        if(isset($loginInformation) && count($loginInformation) > 0)
        {
            // note: defaulting to first account found, user might be a
            // member of multiple accounts
            $loginAccount = $loginInformation->getLoginAccounts()[0];
            $host = $loginAccount->getBaseUrl();
            $host = explode("/v2",$host);
            $host = $host[0];

            // UPDATE configuration object
            $config->setHost($host);

            // instantiate a NEW docusign api client (that has the correct baseUrl/host)
            $apiClient = new ApiClient($config);

            if(isset($loginInformation))
            {
                $accountId = $loginAccount->getAccountId();
                if(!empty($accountId))
                {

                    try {
                        //*** STEP 2 - Signature Request from a Template
                        // create envelope call is available in the EnvelopesApi
                        $envelopeApi = new EnvelopesApi($apiClient);
                        // assign recipient to template role by setting name, email, and role name.  Note that the
                        // template role name must match the placeholder role name saved in your account template.
                        $templateRole = new TemplateRole();
                        $templateRole->setEmail($email);
                        $templateRole->setName($name);
                        $templateRole->setRoleName("Admin");

                        // instantiate a new envelope object and configure settings
                        $envelop_definition = new EnvelopeDefinition();
                        $envelop_definition->setEmailSubject("[DocuSign PHP SDK] - Signature Request Sample");
                        $envelop_definition->setTemplateId("00b3204c-779d-4e4f-b5f9-fbbe3938a7d9");
                        $envelop_definition->setTemplateRoles(array($templateRole));

                        // set envelope status to "sent" to immediately send the signature request
                        $envelop_definition->setStatus("sent");

                        // optional envelope parameters
                        $options = new EnvelopesApi\CreateEnvelopeOptions();
                        $options->setCdseMode(null);
                        $options->setMergeRolesOnDraft(null);

                        // create and send the envelope (aka signature request)
                        $envelop_summary = $envelopeApi->createEnvelope($accountId, $envelop_definition, $options);
                        if (!empty($envelop_summary)) {
                            return Redirect::to('/docsign')->with('success','success');
                        }
                    } catch ( ApiException $e) {
                        return Redirect::to('/docsign')->with('error','error');
                        echo $e->getMessage();exit;
                    }
                }
            }
        }


    }

}
