<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Outlet;


class MenusTitlesTableSeeder extends Seeder
{

    public function run()
    {

        // TestDummy::times(20)->create('App\Post');
        DB::table('menu_titles')->delete();


        // Menu Titles for wow Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','WOW')->first();
        $OutletId=$Outlet->id;

        //Menu Titles for jungle bhookh Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','jungle bhookh')->first();
        $OutletId1=$Outlet->id;

        //Menu Titles for Shree Mehfil Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Shree Mehfil')->first();
        $OutletId2=$Outlet->id;

        //Menu Titles for Pleasure Trove Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Pleasure Trove')->first();
        $OutletId3=$Outlet->id;

        //Menu Titles for Shambhus Coffee Bar Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Shambhus Coffee Bar')->first();
        $OutletId4=$Outlet->id;

        //Menu Titles for Cafe Coffee Day Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Cafe Coffee Day')->first();
        $OutletId5=$Outlet->id;

        //Menu Titles for Saffron Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Saffron Outlet')->first();
        $OutletId6=$Outlet->id;

        //Menu Titles for Sankalp Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Sankalp Outlet')->first();
        $OutletId7=$Outlet->id;

        //Menu Titles for Havmor Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Havmor Outlet')->first();
        $OutletId8=$Outlet->id;

        //Menu Titles for Not Just Grill from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Not Just Grill')->first();
        $OutletId9=$Outlet->id;

        //Menu Titles for Dinner Bell2 from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Dinner Bell2')->first();
        $OutletId10=$Outlet->id;

        //Menu Titles for Saffron Outlet from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Cafe Upper Crust')->first();
        $OutletId11=$Outlet->id;

        //Menu Titles for Cellad Eatery from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Cellad Eatery')->first();
        $OutletId12=$Outlet->id;

        //Menu Titles for Toritos  from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Toritos')->first();
        $OutletId13=$Outlet->id;


        //Menu Titles for Tomatos  from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Tomatos')->first();
        $OutletId14=$Outlet->id;


        //Menu Titles for Page One from OutletId of Outletdb
        $Outlet=Outlet::where('Outlet_name','Page One')->first();
        $OutletId15=$Outlet->id;

        $menu_titles = array(
            array('outlet_id'=>$OutletId,'title'=>'APPETISERS'),
            array('outlet_id'=>$OutletId,'title'=>'MOCKTAILS'),
            array('outlet_id'=>$OutletId,'title'=>'ADD-ONS'),
            array('outlet_id'=>$OutletId,'title'=>'SALADS(VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'SALADS(NON-VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'SOUPS(NON-VEG)'),
            array('outlet_id'=>$OutletId,'title'=>'SOUPES(VEG)'),
            array('outlet_id'=>$OutletId,'title'=>'CHINESE STATER (NON-VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'SIZZLERS(NON-VEG)'),
            array('outlet_id'=>$OutletId,'title'=>'CHINESE STATER(VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'SIZZLERS(VEG)'),
            array('outlet_id'=>$OutletId,'title'=>'STATER(VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'STATER(NON VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'NOODLES&RICE BOWL(NON-VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'NOODLES&RICE BOWL(VEG.)'),
            array('outlet_id'=>$OutletId,'title'=>'INDIAN CURRIES'),
            array('outlet_id'=>$OutletId,'title'=>'Chinese Curriess'),
            array('outlet_id'=>$OutletId,'title'=>'Chicken Curries'),
            array('outlet_id'=>$OutletId,'title'=>'Mutton Curries'),
            array('outlet_id'=>$OutletId,'title'=>'Sea food curries'),
            array('outlet_id'=>$OutletId,'title'=>'Chinese Curries'),
            array('outlet_id'=>$OutletId,'title'=>'SHAKES "N" SHAKE'),
            array('outlet_id'=>$OutletId,'title'=>'RICE/PULAV(NON-VEG)'),
            array('outlet_id'=>$OutletId,'title'=>'RICE/PULAV(VEG)'),
            array('outlet_id'=>$OutletId,'title'=>'TANDOORI BREADSS'),

            array('outlet_id'=>$OutletId1,'title'=>'Fuel up & Enter'),
            array('outlet_id'=>$OutletId1,'title'=>'Soup'),
            array('outlet_id'=>$OutletId1,'title'=>'Starter'),
            array('outlet_id'=>$OutletId1,'title'=>'Chinese Starter'),
            array('outlet_id'=>$OutletId1,'title'=>'Green Gravy'),
            array('outlet_id'=>$OutletId1,'title'=>'Red Gravy'),
            array('outlet_id'=>$OutletId1,'title'=>'Cashewnut Gravyy'),
            array('outlet_id'=>$OutletId1,'title'=>'Brown Gravy'),
            array('outlet_id'=>$OutletId1,'title'=>'Green Gravyy'),
            array('outlet_id'=>$OutletId1,'title'=>'Red Gravyy'),
            array('outlet_id'=>$OutletId1,'title'=>'Cashewnut Gravy'),
            array('outlet_id'=>$OutletId1,'title'=>'Brown Gravyy'),
            array('outlet_id'=>$OutletId1,'title'=>'Dal'),
            array('outlet_id'=>$OutletId1,'title'=>'Rice'),
            array('outlet_id'=>$OutletId1,'title'=>'Salad & Raita'),
            array('outlet_id'=>$OutletId1,'title'=>'Roti'),
            array('outlet_id'=>$OutletId1,'title'=>'paratha'),
            array('outlet_id'=>$OutletId1,'title'=>'Naan'),
            array('outlet_id'=>$OutletId1,'title'=>'Kulcha'),
            array('outlet_id'=>$OutletId1,'title'=>'Baked Dish'),
            array('outlet_id'=>$OutletId1,'title'=>'Chinese Veg.'),
            array('outlet_id'=>$OutletId1,'title'=>'Chinese Rice'),
            array('outlet_id'=>$OutletId1,'title'=>'Noodles'),
            array('outlet_id'=>$OutletId1,'title'=>'Welcome Drinks'),
            array('outlet_id'=>$OutletId1,'title'=>'Sweet Regular'),
            array('outlet_id'=>$OutletId1,'title'=>'Farshaan'),
            array('outlet_id'=>$OutletId1,'title'=>'Sweet Premium'),

            array('outlet_id'=>$OutletId2,'title'=>'STARTER'),
            array('outlet_id'=>$OutletId2,'title'=>'SOUP'),
            array('outlet_id'=>$OutletId2,'title'=>'SPRING ROLL'),
            array('outlet_id'=>$OutletId2,'title'=>'HOCH-POCH'),
            array('outlet_id'=>$OutletId2,'title'=>'OVEN BAKED'),
            array('outlet_id'=>$OutletId2,'title'=>'SALAD'),
            array('outlet_id'=>$OutletId2,'title'=>'PAPAD'),
            array('outlet_id'=>$OutletId2,'title'=>'CURD/RAITA'),
            array('outlet_id'=>$OutletId2,'title'=>'PUNJABI VEGETABLE'),
            array('outlet_id'=>$OutletId2,'title'=>'PANEER'),
            array('outlet_id'=>$OutletId2,'title'=>'KOFTA'),
            array('outlet_id'=>$OutletId2,'title'=>'RICE/PULAO'),
            array('outlet_id'=>$OutletId2,'title'=>'TANDOOR SE'),
            array('outlet_id'=>$OutletId2,'title'=>'THANDA-THANDA WITH SERVICE'),
            array('outlet_id'=>$OutletId2,'title'=>'ADD-ON'),
            array('outlet_id'=>$OutletId2,'title'=>'PACKING FIX PUNJABI THALI'),
            array('outlet_id'=>$OutletId2,'title'=>'SWEET DISH'),

            array('outlet_id'=>$OutletId3,'title'=>'APPETISERES'),
            array('outlet_id'=>$OutletId3,'title'=>'THIRST TEMPTATION(MOCKTAILS)'),
            array('outlet_id'=>$OutletId3,'title'=>'SHAKES N SHAKE'),
            array('outlet_id'=>$OutletId3,'title'=>'ICE CREAM'),
            array('outlet_id'=>$OutletId3,'title'=>'ETHICH DELIGHTS'),
            array('outlet_id'=>$OutletId3,'title'=>'SOUPS'),
            array('outlet_id'=>$OutletId3,'title'=>'AATISH-E-TANDOOR(NON-VEG)'),
            array('outlet_id'=>$OutletId3,'title'=>'AATISH-E-TANDOOR(VEG)'),
            array('outlet_id'=>$OutletId3,'title'=>'SHORT EATS(NON-VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'SHORT EATS(VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'SIZZLERS(NON-VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'SIZZLERS(VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'BAKED DISHES/PASTA(NON-VEG)'),
            array('outlet_id'=>$OutletId3,'title'=>'BAKED DISHES/PASTA(VEG)'),
            array('outlet_id'=>$OutletId3,'title'=>'CHICKEN(NON-VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'MUTTON(NON-VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'SEA FOOD(NON-VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'CHINESE/THAI MAIN COURSE(NON-VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'CHINESE/THAI MAIN COURSE(VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'SUBZI KI KHASHIYAT(MAIN COURSE-VEG'),
            array('outlet_id'=>$OutletId3,'title'=>'NOODLES & RICE BOWL(NON-VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'NOODLES & RICE BOWL(VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'SALADS'),
            array('outlet_id'=>$OutletId3,'title'=>'ADD ON'),
            array('outlet_id'=>$OutletId3,'title'=>'TANDOORI BREADS'),
            array('outlet_id'=>$OutletId3,'title'=>'RICE/PULAV (NON. VEG.)'),
            array('outlet_id'=>$OutletId3,'title'=>'RICE/PULAV (VEG.)'),

            array('outlet_id'=>$OutletId4,'title'=>'Hot Bread'),
            array('outlet_id'=>$OutletId4,'title'=>'Hot Dog & Burger'),
            array('outlet_id'=>$OutletId4,'title'=>'Bhel'),
            array('outlet_id'=>$OutletId4,'title'=>'Sandwiches'),
            array('outlet_id'=>$OutletId4,'title'=>'Sp. Grill Sandwiches'),
            array('outlet_id'=>$OutletId4,'title'=>'Club Sandwiches'),
            array('outlet_id'=>$OutletId4,'title'=>'Pizza(Soft Base)'),
            array('outlet_id'=>$OutletId4,'title'=>'Pizza(Hard Base)'),
            array('outlet_id'=>$OutletId4,'title'=>'Cold Coffee'),
            array('outlet_id'=>$OutletId4,'title'=>'Frappe Cafe'),
            array('outlet_id'=>$OutletId4,'title'=>'Chocolate'),
            array('outlet_id'=>$OutletId4,'title'=>'Coco'),
            array('outlet_id'=>$OutletId4,'title'=>'Hot Espresso'),
            array('outlet_id'=>$OutletId4,'title'=>'Mocktails'),
            array('outlet_id'=>$OutletId4,'title'=>'Slush'),
            array('outlet_id'=>$OutletId4,'title'=>'Ice Tea'),
            array('outlet_id'=>$OutletId4,'title'=>'Masala Tea'),
            array('outlet_id'=>$OutletId4,'title'=>'Milk Shake'),
            array('outlet_id'=>$OutletId4,'title'=>'Lassi'),
            array('outlet_id'=>$OutletId4,'title'=>'Curacao'),
            array('outlet_id'=>$OutletId4,'title'=>'Faluda'),
            array('outlet_id'=>$OutletId4,'title'=>'Fruit Juice'),
            array('outlet_id'=>$OutletId4,'title'=>'Toast'),
            array('outlet_id'=>$OutletId4,'title'=>'French Fries'),
            array('outlet_id'=>$OutletId4,'title'=>'Combo Offer'),

            array('outlet_id'=>$OutletId5,'title'=>'Hot Coffees'),
            array('outlet_id'=>$OutletId5,'title'=>'Cold Coffees'),
            array('outlet_id'=>$OutletId5,'title'=>'International Coffees'),
            array('outlet_id'=>$OutletId5,'title'=>'Friends of Frappe'),
            array('outlet_id'=>$OutletId5,'title'=>'Coffee Top-ups'),
            array('outlet_id'=>$OutletId5,'title'=>'Chocoholicas'),
            array('outlet_id'=>$OutletId5,'title'=>'Hot Teas'),
            array('outlet_id'=>$OutletId5,'title'=>'Fruiteazers'),
            array('outlet_id'=>$OutletId5,'title'=>'Lemonades'),
            array('outlet_id'=>$OutletId5,'title'=>'Bites'),
            array('outlet_id'=>$OutletId5,'title'=>'Frosteas'),
            array('outlet_id'=>$OutletId5,'title'=>'Big Bites'),
            array('outlet_id'=>$OutletId5,'title'=>'Combo Offers'),
            array('outlet_id'=>$OutletId5,'title'=>'Sweet Treats'),
            array('outlet_id'=>$OutletId5,'title'=>'Shorts'),
            array('outlet_id'=>$OutletId5,'title'=>'Ice Cream Top-Ups'),
            array('outlet_id'=>$OutletId5,'title'=>'Sundaes'),
            array('outlet_id'=>$OutletId5,'title'=>'The Cake'),


            array('outlet_id'=>$OutletId6,'title'=>'Assorted Beverages'),
            array('outlet_id'=>$OutletId6,'title'=>'Khane Se Pehle'),
            array('outlet_id'=>$OutletId6,'title'=>'Continental Masti'),
            array('outlet_id'=>$OutletId6,'title'=>'Starter'),
            array('outlet_id'=>$OutletId6,'title'=>'Main Course'),
            array('outlet_id'=>$OutletId6,'title'=>'Taaza Tarkariyan'),
            array('outlet_id'=>$OutletId6,'title'=>'Kofta Ka Khazana'),
            array('outlet_id'=>$OutletId6,'title'=>'Tandoor Se'),
            array('outlet_id'=>$OutletId6,'title'=>'Basmati Ka Jaadu'),
            array('outlet_id'=>$OutletId6,'title'=>'Tadka Marke'),
            array('outlet_id'=>$OutletId6,'title'=>'Saathmein'),
            array('outlet_id'=>$OutletId6,'title'=>'Dahi ki Jugalbandhi'),
            array('outlet_id'=>$OutletId6,'title'=>'IceCream and Desserts'),


            array('outlet_id'=>$OutletId7,'title'=>'Assorted Beverages'),
            array('outlet_id'=>$OutletId7,'title'=>'All Time Favorite'),
            array('outlet_id'=>$OutletId7,'title'=>'Idli Stall'),
            array('outlet_id'=>$OutletId7,'title'=>'Special Idli'),
            array('outlet_id'=>$OutletId7,'title'=>'Vada'),
            array('outlet_id'=>$OutletId7,'title'=>'Dashing Dosaz'),
            array('outlet_id'=>$OutletId7,'title'=>'Speciality Dosaz'),
            array('outlet_id'=>$OutletId7,'title'=>'Ravishing Rava'),
            array('outlet_id'=>$OutletId7,'title'=>'Amezing Uthappa'),
            array('outlet_id'=>$OutletId7,'title'=>'Curries'),
            array('outlet_id'=>$OutletId7,'title'=>'Rice'),
            array('outlet_id'=>$OutletId7,'title'=>'Sweet'),
            array('outlet_id'=>$OutletId7,'title'=>'Extra Items'),
            array('outlet_id'=>$OutletId7,'title'=>'Ice Creams & Desserts'),

            array('outlet_id'=>$OutletId8,'title'=>'Starters'),
            array('outlet_id'=>$OutletId8,'title'=>'Beverages'),
            array('outlet_id'=>$OutletId8,'title'=>'Milk Shakes'),
            array('outlet_id'=>$OutletId8,'title'=>'Starters-I'),
            array('outlet_id'=>$OutletId8,'title'=>'Starters-II'),
            array('outlet_id'=>$OutletId8,'title'=>'Salads'),
            array('outlet_id'=>$OutletId8,'title'=>'Accompaniments'),
            array('outlet_id'=>$OutletId8,'title'=>'Soups'),
            array('outlet_id'=>$OutletId8,'title'=>'Sizzlers'),
            array('outlet_id'=>$OutletId8,'title'=>'Continental'),
            array('outlet_id'=>$OutletId8,'title'=>'Indian'),
            array('outlet_id'=>$OutletId8,'title'=>'Rice and Roti'),
            array('outlet_id'=>$OutletId8,'title'=>'Oriental'),
            array('outlet_id'=>$OutletId8,'title'=>'Rice and Noodles'),
            array('outlet_id'=>$OutletId8,'title'=>'Exotic Sundaes'),
            array('outlet_id'=>$OutletId8,'title'=>'Ice Cream Scoops'),


            array('outlet_id'=>$OutletId9,'title'=>'BBQ'),
            array('outlet_id'=>$OutletId9,'title'=>'Clay Oven'),
            array('outlet_id'=>$OutletId9,'title'=>'Sizzler'),
            array('outlet_id'=>$OutletId9,'title'=>'Teppanyaki & Griddle'),
            array('outlet_id'=>$OutletId9,'title'=>'Oriental'),
            array('outlet_id'=>$OutletId9,'title'=>'Soups'),
            array('outlet_id'=>$OutletId9,'title'=>'Sides'),
            array('outlet_id'=>$OutletId9,'title'=>'Indian Mains'),
            array('outlet_id'=>$OutletId9,'title'=>'Mediterranean Et Latine'),
            array('outlet_id'=>$OutletId9,'title'=>'Desserts from Turquoise Villa'),
            array('outlet_id'=>$OutletId9,'title'=>'Mocktails'),
            array('outlet_id'=>$OutletId9,'title'=>'Coolers'),
            array('outlet_id'=>$OutletId9,'title'=>'Shakes'),
            array('outlet_id'=>$OutletId9,'title'=>'Juices'),
            array('outlet_id'=>$OutletId9,'title'=>'Teas'),
            array('outlet_id'=>$OutletId9,'title'=>'Coffees'),
            array('outlet_id'=>$OutletId9,'title'=>'Chocolates'),

            array('outlet_id'=>$OutletId10,'title'=>'Appetizers'),
            array('outlet_id'=>$OutletId10,'title'=>'Mocktails'),
            array('outlet_id'=>$OutletId10,'title'=>'Soups'),
            array('outlet_id'=>$OutletId10,'title'=>'Starters'),
            array('outlet_id'=>$OutletId10,'title'=>'Tandoor Express'),
            array('outlet_id'=>$OutletId10,'title'=>'Salad'),
            array('outlet_id'=>$OutletId10,'title'=>'Accompniments'),
            array('outlet_id'=>$OutletId10,'title'=>'Continental'),
            array('outlet_id'=>$OutletId10,'title'=>'Itallian'),
            array('outlet_id'=>$OutletId10,'title'=>'Mexican'),
            array('outlet_id'=>$OutletId10,'title'=>'Indian Vegetables'),
            array('outlet_id'=>$OutletId10,'title'=>'Indian Paneer'),
            array('outlet_id'=>$OutletId10,'title'=>'Kofta'),
            array('outlet_id'=>$OutletId10,'title'=>'Dal'),
            array('outlet_id'=>$OutletId10,'title'=>'Rice'),
            array('outlet_id'=>$OutletId10,'title'=>'Roti Tawa'),
            array('outlet_id'=>$OutletId10,'title'=>'Roti Tandoori'),
            array('outlet_id'=>$OutletId10,'title'=>'Naan'),
            array('outlet_id'=>$OutletId10,'title'=>'Kulcha'),
            array('outlet_id'=>$OutletId10,'title'=>'Paratha'),
            array('outlet_id'=>$OutletId10,'title'=>'Sandwich'),
            array('outlet_id'=>$OutletId10,'title'=>'Pizza'),
            array('outlet_id'=>$OutletId10,'title'=>'Dessert'),
            array('outlet_id'=>$OutletId10,'title'=>'Milk Shake'),
            array('outlet_id'=>$OutletId10,'title'=>'Executive Power Lunch'),
            array('outlet_id'=>$OutletId10,'title'=>'With Baked Dish Power Lunch'),
            array('outlet_id'=>$OutletId10,'title'=>'Pack Lunch'),
            array('outlet_id'=>$OutletId10,'title'=>'Fix Lunch'),
            array('outlet_id'=>$OutletId10,'title'=>'Deluxe Lunch'),


            array('outlet_id'=>$OutletId11,'title'=>'Veg Starter'),
            array('outlet_id'=>$OutletId11,'title'=>'Non-Veg Starter'),
            array('outlet_id'=>$OutletId11,'title'=>'Quenchers'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Soups'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Soups'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Frankies'),
            array('outlet_id'=>$OutletId11,'title'=>'NonVeg. Frankies'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Open & Shut Cases'),
            array('outlet_id'=>$OutletId11,'title'=>'NonVeg. Open & Shut Cases'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Ovenscapes'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Pizza'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Pizza'),
            array('outlet_id'=>$OutletId11,'title'=>'French Fries'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. BBQ'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Kebabs'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. BBQ'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Kebabs'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Ovenscapes'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Pasta'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Pasta'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Chinese'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Chinese'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Thai'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Thai'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Sizzlers'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Sizzlers'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Steaks'),
            array('outlet_id'=>$OutletId11,'title'=>'Non Veg. Steaks'),
            array('outlet_id'=>$OutletId11,'title'=>'Fusion Food'),
            array('outlet_id'=>$OutletId11,'title'=>'Veg. Indian Cuisine'),
            array('outlet_id'=>$OutletId11,'title'=>'NonVeg. Indian Cuisine'),

            array('outlet_id'=>$OutletId12,'title'=>'Buffet Station'),

            array('outlet_id'=>$OutletId13,'title'=>'Starters'),
            array('outlet_id'=>$OutletId13,'title'=>'Mocktails'),
            array('outlet_id'=>$OutletId13,'title'=>'Soups'),
            array('outlet_id'=>$OutletId13,'title'=>'Italian'),
            array('outlet_id'=>$OutletId13,'title'=>'Tapas'),
            array('outlet_id'=>$OutletId13,'title'=>'Salads'),
            array('outlet_id'=>$OutletId13,'title'=>'World Cuisines'),
            array('outlet_id'=>$OutletId13,'title'=>'Mexican'),
            array('outlet_id'=>$OutletId13,'title'=>'Stone Baked Thin Crust Pizza'),
            array('outlet_id'=>$OutletId13,'title'=>'Swiss'),
            array('outlet_id'=>$OutletId13,'title'=>'Oven Baked'),
            array('outlet_id'=>$OutletId13,'title'=>'Mediterranean'),
            array('outlet_id'=>$OutletId13,'title'=>'Accompaniments'),
            array('outlet_id'=>$OutletId13,'title'=>'Desserts'),
            array('outlet_id'=>$OutletId13,'title'=>'Cold Beverages'),
            array('outlet_id'=>$OutletId13,'title'=>'Pasta Station'),
            array('outlet_id'=>$OutletId13,'title'=>'Hot Beverages'),
            array('outlet_id'=>$OutletId13,'title'=>'Coffee'),
            array('outlet_id'=>$OutletId13,'title'=>'Tea'),
            array('outlet_id'=>$OutletId13,'title'=>'High Tea'),


            array('outlet_id'=>$OutletId14,'title'=>'Global'),
            array('outlet_id'=>$OutletId14,'title'=>'Pan Asian'),
            array('outlet_id'=>$OutletId14,'title'=>'Indian'),
            array('outlet_id'=>$OutletId14,'title'=>'Veg. Soups'),
            array('outlet_id'=>$OutletId14,'title'=>'NonVeg. Soups'),
            array('outlet_id'=>$OutletId14,'title'=>'Veg. Salads'),
            array('outlet_id'=>$OutletId14,'title'=>'NonVeg. Salads'),
            array('outlet_id'=>$OutletId14,'title'=>'Global Star Attractions'),
            array('outlet_id'=>$OutletId14,'title'=>'Pan Asian Star Attractions'),
            array('outlet_id'=>$OutletId14,'title'=>'All Stir Fry Combos'),
            array('outlet_id'=>$OutletId14,'title'=>'Popular Chinese'),
            array('outlet_id'=>$OutletId14,'title'=>'Attraction From The Indian Kitchen'),
            array('outlet_id'=>$OutletId14,'title'=>'Indian Brads'),
            array('outlet_id'=>$OutletId14,'title'=>'Accompaniments'),
            array('outlet_id'=>$OutletId14,'title'=>'Pizzas (Veg.)'),
            array('outlet_id'=>$OutletId14,'title'=>'Pizzas (Non Veg.)'),

            array('outlet_id'=>$OutletId15,'title'=>'From The Soup Kettle'),
            array('outlet_id'=>$OutletId15,'title'=>'Salads'),
            array('outlet_id'=>$OutletId15,'title'=>'Starters/Continental'),
            array('outlet_id'=>$OutletId15,'title'=>'Starters/Oriental Experience'),
            array('outlet_id'=>$OutletId15,'title'=>'Starters/Tandoor Ke Salakhon  Se'),
            array('outlet_id'=>$OutletId15,'title'=>'Sizzlers'),
            array('outlet_id'=>$OutletId15,'title'=>'Pizza House'),
            array('outlet_id'=>$OutletId15,'title'=>'Oven Baked'),
            array('outlet_id'=>$OutletId15,'title'=>'Italian Pasta'),
            array('outlet_id'=>$OutletId15,'title'=>'Main Course/Continental'),
            array('outlet_id'=>$OutletId15,'title'=>'Main Course/Oriental Experience'),
            array('outlet_id'=>$OutletId15,'title'=>'Main Course/Paneer Aap Ki Pasand'),
            array('outlet_id'=>$OutletId15,'title'=>'Main Course/Sabjiyo Ka Mela'),
            array('outlet_id'=>$OutletId15,'title'=>'Basmati Ke Noor'),
            array('outlet_id'=>$OutletId15,'title'=>'Dal ki Peshkash'),
            array('outlet_id'=>$OutletId15,'title'=>'Roti and Parathavali Gali'),
            array('outlet_id'=>$OutletId15,'title'=>'Packed Lunch'),
            array('outlet_id'=>$OutletId15,'title'=>'Executive Shahi Lunch'),
            array('outlet_id'=>$OutletId15,'title'=>'Page One Lunch'),
        );


        DB::table('menu_titles')->insert($menu_titles);

    }



}


