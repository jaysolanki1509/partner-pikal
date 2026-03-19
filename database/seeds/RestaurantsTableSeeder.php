<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\User;
use App\State;
use App\City;
use App\locality;
use App\Country;



class RestaurantsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('restaurants')->delete();

        //User Authentication For Restaurants
//        $user=User::where('user_name','parag')->first();
//        $userID=$user->id;
//
//        $user=User::where('user_name','moin')->first();
//        $userID1=$user->id;

        $user = User::where('user_name','dadaji')->first();
        $userID2=$user->id;

        $user =User::where('user_name','punjabi_bites')->first();
        $userID3=$user->id;

        $user =User::where('user_name','purohit_sandwich')->first();
        $userID4=$user->id;

        $user =User::where('user_name','grill_inn')->first();
        $userID5=$user->id;

        $user =User::where('user_name','biryani')->first();
        $userID6=$user->id;


        $user =User::where('user_name','knife_and_fork')->first();
        $userID7=$user->id;



//        $user =User::where('user_name','WOW')->first();
//        $userID8=$user->id;


        $country= Country::where('name','India')->first();
        $countryId=$country->id;

        $state= State::where('name','Gujarat')->first();
        $stateId=  $state->id;

        $city = City::where('name','Ahmedabad')->first();
        $cityId = $city->id;

        $locality11=locality::where('locality','South Bopal')->first();
        $localityId11 = $locality11->id;


        $restaurants = array(

//            array('created_by'=>$userID8,'restaurant_name'=>'WOW','restaurant_code'=>'WO','web_address'=>'www.wowrestaurant.co.in','state_id'=>$state,'city_id'=>$city,'pincode'=>'380006','address'=>'1st Floor, Rangoli Complex, Opposite V S Hospital','locality'=>'Ellis Bridge','famous_for'=>'The Non- Veg spread offered here','contact_no'=>'07930641674','email_id'=>'wowrestaurant440@gmail.com','avg_cost_of_two'=>'800','established_date'=>'12/06/2008','restaurant_image'=>'wow.jpeg','service_type'=>[ "take_away","dine_in","home_delivery" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'jungle bhookh','restaurant_code'=>'JB','web_address'=>'www.junglebhookh.com','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'101, Wall Street Annexe, Opposite Orient Club, Ellis Bridge','locality'=>'Ellis Bridge','famous_for'=>'Its jungle theme ambiance','contact_no'=>'07926408200','email_id'=>'kasamdauwa@ymail.com','avg_cost_of_two'=>'700','established_date'=>'17/3/2008','restaurant_image'=>'jb.jpeg','service_type'=>[ "take_away","dine_in","home_delivery" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Shree Mehfil','restaurant_code'=>'SM','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380061','address'=>'Near HP Petrol Pump, Sattadhar Cross Road','locality'=>'Sola Road','famous_for'=>'Chinese','contact_no'=>'07927498700','email_id'=>'harjitbhatia24@yahoo.co.in','avg_cost_of_two'=>'500','established_date'=>'11/09/1998','restaurant_image'=>'sm.jpeg','service_type'=>[ "take_away" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Pleasure Trove','restaurant_code'=>'PT','web_address'=>'http://www.pleasuretrove.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'B 101-104, Sakar 7, Nehru Bridge Corner, Near Patang Restaurant, Ashram Road','locality'=>'Navarangpura','famous_for'=>'Its Non - Veg Spread.','contact_no'=>'07930641621','email_id'=>'','avg_cost_of_two'=>'1100','established_date'=>'26/05/2010','restaurant_image'=>'pt.jpeg','service_type'=>[ "dine_in" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Shambhus Coffee Bar','restaurant_code'=>'SCB','web_address'=>'http://www.shambhuscoffeebar.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'G 1, Camps Corner, Opposite Prahlad Nagar Garden','locality'=>'Prahlad Nagar','famous_for'=>'Coffee','contact_no'=>'07965490863','email_id'=>'','avg_cost_of_two'=>'400','established_date'=>'20/08/2003','restaurant_image'=>'smbhu.jpeg','service_type'=>[ "home_delivery" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Cafe Coffee Day','restaurant_code'=>'CCD','web_address'=>'http://www.cafecoffeeday.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'Ground Floor, Sigma Excellence, Vastrapur Mansi Road','locality'=>'Vastrapur','famous_for'=>'Coffee','contact_no'=>'07965050082','email_id'=>'','avg_cost_of_two'=>'450','established_date'=>'26/08/1996','restaurant_image'=>'cafe.jpeg','service_type'=>[ "take_away" ]),
//
//            array('created_by'=>$userID,'restaurant_name'=>'Saffron Restaurant','restaurant_code'=>'SFR','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'Ground Floor, Samudra Complex, Near Hotel Klassic Gold, Off C G Road','locality'=>'C G Road','famous_for'=>'Its Non - Veg Spread.','contact_no'=>'079 26430999','email_id'=>'','avg_cost_of_two'=>'800','established_date'=>'26/05/2005','restaurant_image'=>'saf.jpeg','service_type'=>[ "home_delivery" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Sankalp Restaurant','restaurant_code'=>'SNK','web_address'=>'www.sankalponline.com','state_id'=>$state,'city_id'=>$city,'pincode'=>'380009','address'=>'India address Embassy Market, Near Dinesh Hall','locality'=>'Ashram Road','famous_for'=>'South Indian Delights Collection','contact_no'=>' 079 26583550','email_id'=>'','avg_cost_of_two'=>'600','established_date'=>'25/11/1981','restaurant_image'=>'snk.jpeg','service_type'=>[ "take_away" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Havmor Restaurant','restaurant_code'=>'HR','web_address'=>'www.havmor.com/restaurant/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380054','address'=>'7 B,Circle B,Opposite Rajpath Club,Judges Bungalow Road','locality'=>'Bodakdev','famous_for'=>'Punjabi Food','contact_no'=>'079 26871155','email_id'=>'','avg_cost_of_two'=>'800','established_date'=>'15/08/2011','restaurant_image'=>'hm.jpeg','service_type'=>[ "dine_in" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Not Just Grill','restaurant_code'=>'NJG','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'103, Pushpak Complex, 100 Feet Road','locality'=>'Prahlad Nagar','famous_for'=>'Chinese','contact_no'=>' 079 40323000','email_id'=>'','avg_cost_of_two'=>'1000','established_date'=>'15/09/2007','restaurant_image'=>'njg.jpeg','service_type'=>[ "dine_in" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Dinner Bell2','restaurant_code'=>'DB','web_address'=>'http://www.dinnerbell.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380052','address'=>'Atlantis Enclave, Near IDBI Bank, Gurukul Road','locality'=>'Ashram Road','famous_for'=>'Punjabi','contact_no'=>'079 65121600','email_id'=>'','avg_cost_of_two'=>'500','established_date'=>'12/08/2009','restaurant_image'=>'db.jpeg','service_type'=>[ "take_away","home_delivery" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Cafe Upper Crust','restaurant_code'=>'CUC','web_address'=>'http://www.cafeuppercrust.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'5 & 6, Shubham Complex, Sunrise Park Corner,Near Vastrapur Lake','locality'=>'Vastrapur','famous_for'=>'Sizzlers and Desserts.','contact_no'=>'079 30641904','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'12/10/2002','restaurant_image'=>'cafeu.jpeg','service_type'=>[ "dine_in","home_delivery"]),
//            array('created_by'=>$userID,'restaurant_name'=>'Cellad Eatery','restaurant_code'=>'CE','web_address'=>'http://www.celladeatery.com/','state_id'=>$state,'city_id'=>$city,'pincode'=>'380001','address'=>'Top Floor, City Square Building, University Area, Gulbai Tekra, Ahmedabad','locality'=>'Gulabi Tekra','famous_for'=>'North Indian','contact_no'=>'079 26305533','email_id'=>'','avg_cost_of_two'=>'700','established_date'=>'12/01/2005','restaurant_image'=>'ce.jpeg','service_type'=>[ "home_delivery" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Toritos','restaurant_code'=>'TOR','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380006','address'=>'Shivalik Building, Near Panchvati Cross Roads, Opposite Bank Of Baroda','locality'=>'AmbaVadi','famous_for'=>'Its Italian Spread.','contact_no'=>' 079 26400730','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'12/01/2005','restaurant_image'=>'toritos.jpeg','service_type'=>[ "dine_in" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Tomatos','restaurant_code'=>'TMT','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'38009','address'=>'1-3, Mardia Plaza','locality'=>'C G Road','famous_for'=>'Its classic retro diner theme and Mexican food.','contact_no'=>' 079 30641721','email_id'=>'','avg_cost_of_two'=>'1200','established_date'=>'12/01/2005','restaurant_image'=>'tmt.jpeg','service_type'=>[ "take_away","dine_in","home_delivery" ]),
//            array('created_by'=>$userID,'restaurant_name'=>'Page One','restaurant_code'=>'PO','web_address'=>'','state_id'=>$state,'city_id'=>$city,'pincode'=>'380015','address'=>'Prarabdh Complex, Sandesh Press Road, Opposite Premier Apartments','locality'=>'Vastrapur','famous_for'=>'The Vegetarian spread and Live Pasta served at your table.','contact_no'=>'079 29297842','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'12/01/2005','restaurant_image'=>'pone.jpeg','service_type'=>[ "take_away","dine_in" ]),

            array('created_by'=>$userID2,'restaurant_name'=>'Dadaji','restaurant_code'=>'DAD','web_address'=>'','country_id'=>$countryId,'state_id'=>$stateId,'city_id'=>$cityId,'locality'=>$localityId11,'pincode'=>'380058','address'=>'B/29, Sobo Center, Gala Gymkhana Road, Bopal, Ahmedabad','famous_for'=>'Fast Food','contact_no'=>'9426086870','email_id'=>'','avg_cost_of_two'=>'300','established_date'=>'','restaurant_image'=>'Dadaji-01.jpg','service_type'=>'','active'=>''),
            array('created_by'=>$userID3,'restaurant_name'=>'Punjabi Bites','restaurant_code'=>'PB','web_address'=>'','country_id'=>$countryId,'state_id'=>$stateId,'city_id'=>$cityId,'locality'=>$localityId11,'pincode'=>'380058','address'=>'C-45, Sobo center, south Bopal','famous_for'=>'North Indian Cuisine','contact_no'=>'8140452751','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'','restaurant_image'=>'punjabi-01.jpg','service_type'=>'','active'=>''),
            array('created_by'=>$userID4,'restaurant_name'=>'Purohit Sandwich','restaurant_code'=>'PS','web_address'=>'','country_id'=>$countryId,'state_id'=>$stateId,'city_id'=>$cityId,'locality'=>$localityId11,'pincode'=>'380015','address'=>'A/G-6, Sobo Center, Nr. Aarohivila Bunglows, S.P. Ring Road, South Bopal, Bopal, Ahmedabad','famous_for'=>'Fast Food, Street Food','contact_no'=>'079 26769099','email_id'=>'','avg_cost_of_two'=>'900','established_date'=>'','restaurant_image'=>'purohit-01.jpg','service_type'=>'','active'=>''),
            array('created_by'=>$userID5,'restaurant_name'=>'Grill INN','restaurant_code'=>'GI','web_address'=>'www.grillinn.in','country_id'=>$countryId,'state_id'=>$stateId,'city_id'=>$cityId,'locality'=>$localityId11,'pincode'=>'380058','address'=>'149 , Sobo Centre , South Bopal Road , Ahmedabad - 380058','famous_for'=>'Fast Food','contact_no'=>'02717-401000','email_id'=>'','avg_cost_of_two'=>'','established_date'=>'','restaurant_image'=>'cover1.jpg','service_type'=>'','active'=>''),
            array('created_by'=>$userID6,'restaurant_name'=>'Biryani','restaurant_code'=>'BR','web_address'=>'','country_id'=>$countryId,'state_id'=>$stateId,'city_id'=>$cityId,'locality'=>$localityId11,'pincode'=>'380058','address'=>'140 Sobo Center, Gala Gymkhana Road, South Bopal, Ahmedabad','famous_for'=>'Biryani','contact_no'=>'9825051383','email_id'=>'','avg_cost_of_two'=>'450','established_date'=>'','restaurant_image'=>'','service_type'=>'','active'=>''),
            array('created_by'=>$userID7,'restaurant_name'=>'Knife & Fork','restaurant_code'=>'K & F','web_address'=>'','country_id'=>$countryId,'state_id'=>$stateId,'city_id'=>$cityId,'locality'=>$localityId11,'pincode'=>'380058','address'=>'60/61, SOBO Center, Near Sun City, South Bopal, Ahmedabad','famous_for'=>'Flavours of Punjab Collection','contact_no'=>'9924051116','email_id'=>'','avg_cost_of_two'=>'750','established_date'=>'','restaurant_image'=>'','service_type'=>'','active'=>''),
            );

//        print_r($restaurants);exit;
        DB::table('restaurants')->insert($restaurants);
    }

}




