<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Restaurantlatlong;
use App\RestaurantType;
use App\CuisineType;
use App\Country;
use App\State;
use App\City;
use App\Restaurant;
use App\RestaurantRestaurantType;
use App\Restaurantimage;
use App\RestaurantCuisineType;
use App\Menu;
use App\MenuTitle;
use App\Http\Requests\CreateRestaurantRequest;
use App\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine;
use Illuminate\Support\Facades\Response;
use App\locality;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;




class RestaurantController extends Controller {

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
        $resttime=array();
        $user_id=Auth::User()->id;
        $restaurants = Restaurant::where('created_by',$user_id)->get();
        //print_r($restaurants);exit;

        //echo "sada";exit;

        return view('restaurants.index',array('restaurants'=>$restaurants));


	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $countries=Country::all();
        $states=State::all();
        $cities=City::all();
        $locality=locality::all();
        $restaurant_type = RestaurantType::all();
        $cuisineType_type = CuisineType::all();
        $service_type = Restaurant::all();
        $active = Restaurant::all();
//        print_r($service_type);exit;
          //$restaurant=Restaurant::find($id);
         // $timeslots =Restaurant::all();

        $timeslots = Timeslot::where('restaurant_id','=','')->get();
        return view('restaurants.create',array('timeslots'=>$timeslots,'restaurant_type'=>$restaurant_type,'cuisineType_type'=>$cuisineType_type,'service_type'=>$service_type,'active'=>$active,'countries'=>$countries,'states'=>$states,'cities'=>$cities,'locality'=>$locality,'action'=>'add','get'=>'add','set'=>'add','test'=>'add','create'=>'add','make'=>'add'));

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateRestaurantRequest $request)
	{
        //print_r($request->all());exit;
        $user_id=Auth::User()->id;
        $restaurant = new Restaurant();
        $restaurant->created_by=$user_id;
        $restaurant->restaurant_name=$request->restaurant_name;
        $restaurant->restaurant_code=$request->restaurant_code;
        $restaurant->country_id=$request->countries;
        $restaurant->state_id=$request->states;
        $restaurant->city_id=$request->cities;
        $restaurant->pincode=$request->pincode;
        $restaurant->address=$request->address;
        $restaurant->locality=$request->locality;
        $restaurant->service_type=serialize($request->service_type);
        $restaurant->active=serialize($request->active);
        $restaurant->minimum_order=$request->mininum_order_price;
        $restaurant->famous_for=$request->famous_for;
        $restaurant->contact_no=$request->contact_no;
        $restaurant->email_id=$request->email_id;
        $restaurant->web_address=$request->web_address;
        $restaurant->established_date=$request->established_date;
//        print_r($restaurant->established_date);exit;
        $restaurant->avg_cost_of_two=$request->avg_cost_of_two;


        $success=$restaurant->save();





    //        if (Request::hasFile('file')) {
    //            $file = Request::file('file');
    //            try {
    //                $path = public_path() . '/uploads';
    //                $filename = $user_id. '_' . time() . '_' . $file->getClientOriginalName();
    //                $file->move($path, $filename);
    //
    //            } catch (FileException $e) {
    //                $request->errors()->add('error', 'There was some issue in uploading your file. Please try again!');
    //                return Redirect::back()->withInput()->withErrors($request->errors());
    //            }
    //        }

        if($success)
        {
            $restaurantId=$restaurant->id;
            $rest_types=$request->restaurant_type;
            $cuisine_type=$request->cuisine_type;
            $restaurant->opening_time=$request->opening_time;
            $restaurant->closing_time=$request->closing_time;


            if (($request->count + $request->countf) == 0) {
                $no = 0;
            }  else {
                $no = $request->count;
            }

           //    print_r($no);exit;

            for ($i=0; $i <= $no; $i++)
            {
                $fnm=Request::get('opening_time'.$i);
                $fval=Request::get('closing_time'.$i);

                if(isset($fnm)|| isset($fval))
                {

                    $timeslot = new Timeslot();
                    $timeslot->restaurant_id = $restaurant->id;
                    $timeslot->from_time = $fnm;
                    $timeslot->to_time = $fval;
                    $timeslot->save();
//                    print_r($timeslot);exit;
                }
            }
            //exit;

            if(sizeof($rest_types)>0){
                foreach($rest_types as $rest_type){
                    $restaurant_type=new RestaurantRestaurantType();
                    $restaurant_type->restaurant_id = $restaurant->id;
                    $restaurant_type->restaurant_type_id=$rest_type;
                    //print_r($restaurant_type);
                    $restaurant_type->save();
                   // print_r($restaurant_type);exit;
                }
            }
            if(sizeof($cuisine_type)>0){
                foreach($cuisine_type as $cui_type)
                {
                    $cuisi_type=new RestaurantCuisineType();
                    $cuisi_type->restaurant_id=$restaurant->id;
                    $cuisi_type->cuisine_type_id=$cui_type;
                    $cuisi_type->save();
                }
            }



            return Redirect('/restaurant')->with('success', 'Restaurant added successfully ');
        }
        else
        {
            return Redirect('/restaurant')->with('error', 'Failed');
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 **/


	public function show($id)
	{
        $restaurant=Restaurant::find($id);

        if(isset($restaurant->active)) {
            $active = unserialize($restaurant->active);
        }else{
            $active = array();
        }

        if(isset($restaurant->service_type)){
            $service_type =unserialize($restaurant->service_type);
        }else{
            $service_type=array();
        }

        //print_r(@$service_type);exit;
        // print_r($restaurant);exit;
        $restaurantmenu=[];
        $restaurantinformation=[];
        $restauranttype=RestaurantRestaurantType::where('restaurant_id',$id)->get();

        if(sizeof($restauranttype)>0){
            $restaurant_type=$restaurant->restaurantrestauranttype->all();
        }
        else{
            $restaurant_type=array();
     }

        $cuisinetype=RestaurantCuisineType::where('restaurant_id',$id)->get();

        if(sizeof($cuisinetype)>0) {
//        if(sizeof($restauranttype)>0) {
            $cuisinetype = $restaurant->restaurantcuisinetype->all();
        }else{
            $cuisinetype=array();
        }

        if(isset($restaurant->restaurant_image)){
         $image=$restaurant->restaurant_image;
        }else{
            $image='';
        }

        $menuitems=Menu::where('restaurant_id',$id)->get();

        $restaurantsectioname=MenuTitle::where('restaurant_id',$id)->get();

        $timeslot=Timeslot::where('restaurant_id','=',$id)->get();

        if(isset($restaurant->country_id)){
            $countries=Country::where('id','=',$restaurant->country_id)->get();
        }else{
            $countries=array();
        }

        if(isset($restaurant->state_id)){
            $states=State::where('id','=',$restaurant->state_id)->get();

        }else{
            $states=array();
        }
        if(isset($restaurant->city_id)){
        $cities=City::where('id','=',$restaurant->city_id)->get();
        }else{
            $cities=array();
        }


        if(isset($restaurant->locality)){
            $locality=locality::where('locality_id','=',$restaurant->locality)->first();
        }else{
            $locality='';
        }


//        print_r($locality);exit;
        $rest_images=Restaurantimage::where('restaurant_id',$id)->get();
        $rest_latlong=Restaurantlatlong::where('restaurant_id',$id)->get();

       // print_r($restaurant->service_type);exit;
        return view('restaurants.show',array('restaurant'=>$restaurant,'service_type'=>$service_type,'active'=>$active,'restauranttype'=>$restaurant_type,'cuisinetypes'=>$cuisinetype,'menuitems'=>$menuitems,'restaurantimages'=>$rest_images,'image'=>$image,'restaurant_latlong'=>$rest_latlong,'restaurantsectioname'=>$restaurantsectioname,'timeslot'=>$timeslot,'countries'=>$countries,'states'=>$states,'cities'=>$cities,'locality'=>$locality));
    }




	/**
	 * Show the form for editing the specified resource.
	 *
     *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $restaurant = Restaurant::find($id);
        $countries=Country::all();
        $states=State::all();
        $cities=City::all();

//        print_r($cities);exit;
//        $cities=City::where('_id','=',$restaurant->city_id)->get();

        $locality=locality::all();
//        $locality=locality::where('_id','=',$restaurant->locality)->get();
       // print_r($locality);exit;


        $restaurant_type = RestaurantType::all();
        //print_r($restaurant_type);exit;
        $selectedRestaurantType=RestaurantRestaurantType::where('restaurant_id','=',$id)->get();


        $cuisineType_type = CuisineType::all();
        $selectedCuisineType=RestaurantCuisineType::where('restaurant_id','=',$id)->get();
         //print_r($selectedCuisineType);exit;

       // $restaurant=Restaurant::find($id);
        $service_types = Restaurant::all();
        $selectedservice_type=Restaurant::where('id','=',$id)->get();
    //print_r($selectedservice_type);exit;

        $active = Restaurant::all();
        $selectedactive = Restaurant::where('id','=',$id)->get();

//       $selectedservice_type = Restaurant::where('_id',$restaurant->service_type)->get();
        $timeslots = Timeslot::where('restaurant_id','=',$id)->get();
//      print_r($timeslots);exit;
 //  print_r($action);exit;
//
            return view('restaurants.edit',array('restaurant'=>$restaurant,'timeslots'=>$timeslots,'restaurant_type'=>$restaurant_type,'active'=>$active,'selectedactive'=>$selectedactive,'service_types'=>$service_types,'selectedservice_type'=>$selectedservice_type,'selectedRestaurantType'=>$selectedRestaurantType,'selectedCuisineType'=>$selectedCuisineType,'cuisineType_type'=>$cuisineType_type,'countries'=>$countries,'states'=>$states,'cities'=>$cities,'locality'=>$locality,'action'=>'edit','get'=>'edit','set'=>'edit','test'=>'edit','create'=>'edit','make'=>'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 **/


	public function update($id,CreateRestaurantRequest $request)
	{

        $user_id=Auth::User()->id;
        $restaurant = Restaurant::find($id);
        $restaurant->created_by=$user_id;
        $restaurant->restaurant_name=$request->restaurant_name;
        $restaurant->restaurant_code=$request->restaurant_code;
        $restaurant->country_id=$request->countries;
        $restaurant->state_id=$request->states;
        $restaurant->city_id=$request->cities;
        $restaurant->pincode=$request->pincode;
        $restaurant->address=$request->address;
        $restaurant->locality=$request->locality;
        $restaurant->famous_for=$request->famous_for;
        $restaurant->contact_no=$request->contact_no;
        $restaurant->email_id=$request->email_id;
        $restaurant->web_address=$request->web_address;
        $restaurant->avg_cost_of_two=$request->avg_cost_of_two;
        $restaurant->minimum_order=$request->mininum_order_price;
        $restaurant->opening_time=$request->opening_time;
        $restaurant->closing_time=$request->closing_time;
        $restaurant->established_date=$request->established_date;
        $restaurant->service_type=serialize($request->service_type);
        $restaurant->active=serialize($request->active);


        $success=$restaurant->save();



        if($success)

            {
                //$restaurantId=$restaurant->id;
                $old_restaurant_type=RestaurantRestaurantType::where('restaurant_id','=',$id)->delete();
                $old_cuisine_type=RestaurantCuisineType::where('restaurant_id','=',$id)->delete();
                $old_timeslot=Timeslot::where('restaurant_id','=',$id)->delete();
//                $rest_types=$request->restaurant_type;
//                $cuisine_type=$request->cuisine_type;

                $restaurantId=$restaurant->id;
                $rest_types=$request->restaurant_type;
                $cuisine_type=$request->cuisine_type;


                if (($request->count + $request->countf) == 0) {
                    $no = 0;
                }  else {
                    $no = $request->count;
                }

                //    print_r($no);exit;

                for ($i=0; $i <= $no; $i++)
                {
                    $fnm=Request::get('opening_time'.$i);
                    $fval=Request::get('closing_time'.$i);


                    if(isset($fnm)|| isset($fval))
                    {
                        $timeslot = new Timeslot();
                        $timeslot->restaurant_id = $restaurant->id;

                        $timeslot->from_time = $fnm;
//                        print_r($timeslot->from_time);exit;//13/15
                        $timeslot->to_time = $fval;

//                        print_r($timeslot->to_time);exit;
                    }
                    $timeslot->save();
                }

                if(isset($rest_types)){
            foreach($rest_types as $rest_type)
            {
                $restaurant_type=new RestaurantRestaurantType();
                $restaurant_type->restaurant_id = $id;
                $restaurant_type->restaurant_type_id=$rest_type;
                //print_r($restaurant_type);
                $restaurant_type->save();

            }
}
            if(isset($cuisine_type)){
            foreach($cuisine_type as $cui_type){
                $cuisi_type=new RestaurantCuisineType();
                $cuisi_type->restaurant_id=$id;
                $cuisi_type->cuisine_type_id=$cui_type;
                $cuisi_type->save();
            }
}

            return Redirect('/restaurant')->with('success', 'Restaurant Updated successfully ');
        }
        else
        {
            return Redirect('/restaurant')->with('error', 'Failed');
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
		//
	}

    public function addlocation()
    {
        $getpreviouslatlong=Restaurantlatlong::where('restaurant_id',Request::get('restaurant_id'))->get();

        //updated if previously added or added if the restaurant is new restaurant latitude longitude
        if(sizeof($getpreviouslatlong)>0){
            $restaurant_latlong=Restaurantlatlong::where('restaurant_id',Request::get('restaurant_id'));
            $restaurant_latlong->latitude=Request::get('latitude');
            $restaurant_latlong->longitude=Request::get('longitude');
            $restaurant_latlong->save();
        }else{
            $restaurant_latlong=new Restaurantlatlong();
            $restaurant_latlong->restaurant_id=Request::get('restaurant_id');
            $restaurant_latlong->latitude=Request::get('latitude');
            $restaurant_latlong->longitude=Request::get('longitude');
            $restaurant_latlong->save();
        }

    //    return "added Restaurant Location";
        return view('restaurants.show',array('restaurant_latlong'=>$restaurant_latlong));
    }

    public function importrestaurantexcel()
    {
        if (Request::hasFile('file1'))
        {
            $file = Request::file('file1');
            $type =($file->getMimetype());
//            print_r($type);exit;
            if ($type == 'application/vnd.ms-office' || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $type =='application/zip'){
                $path = $file->getRealPath();
               // print_r($path);exit;

                Excel::selectSheets('Sheet1')->load($path, function($reader)
                {
                    // Getting all results
                    $results = $reader->get();

                    $title="";
                    $restaurant_id="";
                    foreach($results as $result)
                    {
                        $existingrestaurants=DB::table('restaurants')->where('restaurant_name',$result['restaurant_name'])->first();
                       // print_r($existingrestaurants->id);exit;
                        if(sizeof($existingrestaurants)<=0)
                        {
                            $restaurant = new Restaurant();
                            $restaurant->restaurant_name = $result['restaurant_name'];
                            $restaurant->created_by = Auth::user()->id;
                            $restaurant->save();
                            $restaurant_id=$restaurant['id'];
                        }else
                        {
                            $restaurant_id=$existingrestaurants->id;
                        }
                        $menu = new Menu();

                        $menutitle = new MenuTitle();
                        $menutitlestored = DB::table('menu_titles')->where('restaurant_id', $restaurant_id)->where('title', $result['title'])->first();
                        
                        if (isset($result['title']) && $result['title'] != "")
                        {
                            if (sizeof($menutitlestored) <= 0)
                            {

                                $menutitle->restaurant_id = $restaurant_id;
                                $menutitle->title = $result['title'];
                                $success = $menutitle->save();
                                $title = $menutitle->id;



                            } else
                            {
                                $title = $menutitlestored->id;
                            }

                            if ($success)
                            {
                                $cuisine_typeid = CuisineType::where('type', $result->cuisine_type)->first();
                                $menu->menu_title_id = $title;
                                $menu->restaurant_id = $restaurant_id;
                                $menu->item = $result->item;
                                $menu->price = $result->price;
                                $menu->cuisine_type_id = $cuisine_typeid['id'];
                                $menu->options = $result->options;


                                $menu->save();
                            }
                        }

                    }

                });

                return Redirect('/restaurant')->with('success','Record Added successfully');
            }else{
                return Redirect::back()->with('failure','Only ".xls" file is acceptable');
            }

        }
    }

//    public function addlocation(){
//        $restaurant_latlong=new Restaurantlatlong();
//        $restaurant_latlong->restaurant_id=Request::get('restaurant_id');
//        $restaurant_latlong->latitude=Request::get('latitude');
//        $restaurant_latlong->longitude=Request::get('longitude');
//        $restaurant_latlong->save();
//        //    return "added Restaurant Location";
//        return view('restaurants.show',array('restaurant_latlong'=>$restaurant_latlong));
//    }
}
