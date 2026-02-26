<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Owner;
use App\State;
use App\City;
use App\locality;
use App\Outlet;
use App\Country;



class OutletsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('outlets')->delete();

        //User Authentication For Outlets
//        $user=User::where('user_name','parag')->first();
//        $userID=$user->id;
//
//        $user=User::where('user_name','moin')->first();
//        $userID1=$user->id;

        $user = Owner::where('user_name','dadaji')->first();
        $userID2=$user->id;

        $user =Owner::where('user_name','punjabi_bites')->first();
        $userID3=$user->id;

        $user =Owner::where('user_name','purohit_sandwich')->first();
        $userID4=$user->id;

        $user =Owner::where('user_name','grill_inn')->first();
        $userID5=$user->id;

        $user =Owner::where('user_name','biryani')->first();
        $userID6=$user->id;


        $user =Owner::where('user_name','knife_and_fork')->first();
        $userID7=$user->id;


//        $user =User::where('user_name','WOW')->first();
//        $userID8=$user->id;
        $state= State::where('name','Gujarat')->first();
        $stateId=  $state->id;

        $city = City::where('name','Ahmedabad')->first();
        $cityId = $city->id;

        $country = Country::where('name','India')->first();
        $countryId = $country->id;

        $locality=locality::where('locality','South Bopal')->first();
        $localityId = $locality->locality_id;



        $Outlets = array(

//            array('owner_id'=>$userID8,'Outlet_name'=>'WOW','Outlet_code'=>'WO','web_address'=>'www.wowOutlet.co.in','state_id'=>$state,'city_id'=>$city,'pincode'=>'380006','address'=>'1st Floor, Rangoli Complex, Opposite V S Hospital','locality'=>'Ellis Bridge','famous_for'=>'The Non- Veg spread offered here','contact_no'=>'07930641674','email_id'=>'wowOutlet440@gmail.com','avg_cost_of_two'=>'800','established_date'=>'12/06/2008','Outlet_image'=>'wow.jpeg','service_type'=>[ "take_away","dine_in","home_delivery" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'jungle bhookh','Outlet_code'=>'JB','web_address'=>'www.junglebhookh.com','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'101, Wall Street Annexe, Opposite Orient Club, Ellis Bridge','locality'=>'Ellis Bridge','famous_for'=>'Its jungle theme ambiance','contact_no'=>'07926408200','email_id'=>'kasamdauwa@ymail.com','avg_cost_of_two'=>'700','established_date'=>'17/3/2008','Outlet_image'=>'jb.jpeg','service_type'=>[ "take_away","dine_in","home_delivery" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Shree Mehfil','Outlet_code'=>'SM','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380061','address'=>'Near HP Petrol Pump, Sattadhar Cross Road','locality'=>'Sola Road','famous_for'=>'Chinese','contact_no'=>'07927498700','email_id'=>'harjitbhatia24@yahoo.co.in','avg_cost_of_two'=>'500','established_date'=>'11/09/1998','Outlet_image'=>'sm.jpeg','service_type'=>[ "take_away" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Pleasure Trove','Outlet_code'=>'PT','web_address'=>'http://www.pleasuretrove.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'B 101-104, Sakar 7, Nehru Bridge Corner, Near Patang Outlet, Ashram Road','locality'=>'Navarangpura','famous_for'=>'Its Non - Veg Spread.','contact_no'=>'07930641621','email_id'=>'','avg_cost_of_two'=>'1100','established_date'=>'26/05/2010','Outlet_image'=>'pt.jpeg','service_type'=>[ "dine_in" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Shambhus Coffee Bar','Outlet_code'=>'SCB','web_address'=>'http://www.shambhuscoffeebar.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'G 1, Camps Corner, Opposite Prahlad Nagar Garden','locality'=>'Prahlad Nagar','famous_for'=>'Coffee','contact_no'=>'07965490863','email_id'=>'','avg_cost_of_two'=>'400','established_date'=>'20/08/2003','Outlet_image'=>'smbhu.jpeg','service_type'=>[ "home_delivery" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Cafe Coffee Day','Outlet_code'=>'CCD','web_address'=>'http://www.cafecoffeeday.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'Ground Floor, Sigma Excellence, Vastrapur Mansi Road','locality'=>'Vastrapur','famous_for'=>'Coffee','contact_no'=>'07965050082','email_id'=>'','avg_cost_of_two'=>'450','established_date'=>'26/08/1996','Outlet_image'=>'cafe.jpeg','service_type'=>[ "take_away" ]),
//
//            array('owner_id'=>$userID,'Outlet_name'=>'Saffron Outlet','Outlet_code'=>'SFR','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'Ground Floor, Samudra Complex, Near Hotel Klassic Gold, Off C G Road','locality'=>'C G Road','famous_for'=>'Its Non - Veg Spread.','contact_no'=>'079 26430999','email_id'=>'','avg_cost_of_two'=>'800','established_date'=>'26/05/2005','Outlet_image'=>'saf.jpeg','service_type'=>[ "home_delivery" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Sankalp Outlet','Outlet_code'=>'SNK','web_address'=>'www.sankalponline.com','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'India address Embassy Market, Near Dinesh Hall','locality'=>'Ashram Road','famous_for'=>'South Indian Delights Collection','contact_no'=>' 079 26583550','email_id'=>'','avg_cost_of_two'=>'600','established_date'=>'25/11/1981','Outlet_image'=>'snk.jpeg','service_type'=>[ "take_away" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Havmor Outlet','Outlet_code'=>'HR','web_address'=>'www.havmor.com/Outlet/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380054','address'=>'7 B,Circle B,Opposite Rajpath Club,Judges Bungalow Road','locality'=>'Bodakdev','famous_for'=>'Punjabi Food','contact_no'=>'079 26871155','email_id'=>'','avg_cost_of_two'=>'800','established_date'=>'15/08/2011','Outlet_image'=>'hm.jpeg','service_type'=>[ "dine_in" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Not Just Grill','Outlet_code'=>'NJG','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'103, Pushpak Complex, 100 Feet Road','locality'=>'Prahlad Nagar','famous_for'=>'Chinese','contact_no'=>' 079 40323000','email_id'=>'','avg_cost_of_two'=>'1000','established_date'=>'15/09/2007','Outlet_image'=>'njg.jpeg','service_type'=>[ "dine_in" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Dinner Bell2','Outlet_code'=>'DB','web_address'=>'http://www.dinnerbell.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380052','address'=>'Atlantis Enclave, Near IDBI Bank, Gurukul Road','locality'=>'Ashram Road','famous_for'=>'Punjabi','contact_no'=>'079 65121600','email_id'=>'','avg_cost_of_two'=>'500','established_date'=>'12/08/2009','Outlet_image'=>'db.jpeg','service_type'=>[ "take_away","home_delivery" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Cafe Upper Crust','Outlet_code'=>'CUC','web_address'=>'http://www.cafeuppercrust.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'5 & 6, Shubham Complex, Sunrise Park Corner,Near Vastrapur Lake','locality'=>'Vastrapur','famous_for'=>'Sizzlers and Desserts.','contact_no'=>'079 30641904','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'12/10/2002','Outlet_image'=>'cafeu.jpeg','service_type'=>[ "dine_in","home_delivery"]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Cellad Eatery','Outlet_code'=>'CE','web_address'=>'http://www.celladeatery.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380001','address'=>'Top Floor, City Square Building, University Area, Gulbai Tekra, Ahmedabad','locality'=>'Gulabi Tekra','famous_for'=>'North Indian','contact_no'=>'079 26305533','email_id'=>'','avg_cost_of_two'=>'700','established_date'=>'12/01/2005','Outlet_image'=>'ce.jpeg','service_type'=>[ "home_delivery" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Toritos','Outlet_code'=>'TOR','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380006','address'=>'Shivalik Building, Near Panchvati Cross Roads, Opposite Bank Of Baroda','locality'=>'AmbaVadi','famous_for'=>'Its Italian Spread.','contact_no'=>' 079 26400730','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'12/01/2005','Outlet_image'=>'toritos.jpeg','service_type'=>[ "dine_in" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Tomatos','Outlet_code'=>'TMT','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'38009','address'=>'1-3, Mardia Plaza','locality'=>'C G Road','famous_for'=>'Its classic retro diner theme and Mexican food.','contact_no'=>' 079 30641721','email_id'=>'','avg_cost_of_two'=>'1200','established_date'=>'12/01/2005','Outlet_image'=>'tmt.jpeg','service_type'=>[ "take_away","dine_in","home_delivery" ]),
//            array('owner_id'=>$userID,'Outlet_name'=>'Page One','Outlet_code'=>'PO','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'Prarabdh Complex, Sandesh Press Road, Opposite Premier Apartments','locality'=>'Vastrapur','famous_for'=>'The Vegetarian spread and Live Pasta served at your table.','contact_no'=>'079 29297842','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'12/01/2005','Outlet_image'=>'pone.jpeg','service_type'=>[ "take_away","dine_in" ]),

            array('owner_id'=>$userID2,'name'=>'Dadaji','code'=>'DAD','url'=>'','state_id'=>$stateId,'city_id'=>$cityId,'country_id'=>$countryId,'locality'=>$localityId,'pincode'=>'380058','address'=>'B/29, Sobo Center, Gala Gymkhana Road, Bopal, Ahmedabad','famous_for'=>'Fast Food','contact_no'=>'9426086870','email_id'=>'','avg_cost_of_two'=>'300','established_date'=>'','Outlet_image'=>'Dadaji-01.jpg','service_type'=>'','lat'=>'','long'=>'','active'=> 'No'),
            array('owner_id'=>$userID3,'name'=>'Punjabi Bites','code'=>'PB','url'=>'','state_id'=>$stateId,'city_id'=>$cityId,'country_id'=>$countryId,'locality'=>$localityId,'pincode'=>'380058','address'=>'C-45, Sobo center, south Bopal','famous_for'=>'North Indian Cuisine','contact_no'=>'8140452751','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'','Outlet_image'=>'punjabi-01.jpg','service_type'=>'','lat'=>'','long'=>'','active'=>'No'),
            array('owner_id'=>$userID4,'name'=>'Purohit Sandwich','code'=>'PS','url'=>'','state_id'=>$stateId,'city_id'=>$cityId,'country_id'=>$countryId,'locality'=>$localityId,'pincode'=>'380015','address'=>'A/G-6, Sobo Center, Nr. Aarohivila Bunglows, S.P. Ring Road, South Bopal, Bopal, Ahmedabad','famous_for'=>'Fast Food, Street Food','contact_no'=>'079 26769099','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'','Outlet_image'=>'purohit-01.jpg','service_type'=>'','lat'=>'','long'=>'','active'=>'No'),
            array('owner_id'=>$userID5,'name'=>'Grill INN','code'=>'GI','url'=>'www.grillinn.in','state_id'=>$stateId,'city_id'=>$cityId,'country_id'=>$countryId,'locality'=>$localityId,'pincode'=>'380058','address'=>'149 , Sobo Centre , South Bopal Road , Ahmedabad - 380058','famous_for'=>'Fast Food','contact_no'=>'02717-401000','email_id'=>'','avg_cost_of_two'=>'','established_date'=>'','Outlet_image'=>'cover1.jpg','service_type'=>'','lat'=>'','long'=>'','active'=>'No'),
            array('owner_id'=>$userID6,'name'=>'Biryani','code'=>'BR','url'=>'','state_id'=>$stateId,'city_id'=>$cityId,'country_id'=>$countryId,'locality'=>$localityId,'pincode'=>'380058','address'=>'140 Sobo Center, Gala Gymkhana Road, South Bopal, Ahmedabad','famous_for'=>'Biryani','contact_no'=>'9825051383','email_id'=>'','avg_cost_of_two'=>'450','established_date'=>'','Outlet_image'=>'','service_type'=>'','lat'=>'','long'=>'','active'=>'No'),
            array('owner_id'=>$userID7,'name'=>'Knife & Fork','code'=>'K & F','url'=>'','state_id'=>$stateId,'city_id'=>$cityId,'country_id'=>$countryId,'locality'=>$localityId,'pincode'=>'380058','address'=>'60/61, SOBO Center, Near Sun City, South Bopal, Ahmedabad','famous_for'=>'Flavours of Punjab Collection','contact_no'=>'9924051116','email_id'=>'','avg_cost_of_two'=>'750','established_date'=>'','Outlet_image'=>'','service_type'=>'','lat'=>'','long'=>'','active'=>'No'),
        );
        DB::table('outlets')->insert($Outlets);

    }
}




