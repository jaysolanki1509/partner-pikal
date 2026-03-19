<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Outlet;
use App\MenuTitle;
use App\CuisineType;
class MenusTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('menus')->delete();

        //Get CuisineType from db and call CuisineTypeId from cuisinetype
        $cuisinetype=CuisineType::where('type',"North Indian")->first();
        $CuisinetypeId=$cuisinetype->id;

        $cuisinetype=CuisineType::where('type',"Chinese")->first();
        $CuisinetypeId1=$cuisinetype->id;

        $cuisinetype=CuisineType::where('type',"Mughlai")->first();
        $CuisinetypeId2=$cuisinetype->id;

        $cuisinetype=CuisineType::where('type',"Continental")->first();
        $CuisinetypeId3=$cuisinetype->id;

        $cuisinetype=CuisineType::where('type',"Fast Food")->first();
        $CuisinetypeId4=$cuisinetype->id;

        $cuisinetype=CuisineType::where('type',"Cafe")->first();
        $CuisinetypeId5=$cuisinetype->id;



        //Get OutletDb from Outlet_name ("WOW")
        $Outlet=Outlet::where('Outlet_name',"WOW")->first();
        //call OutletId from Outlet
        $OutletId=$Outlet->id;
        //Get MenuTitle from title FOR WOW Outlet
        $menuTitle = MenuTitle::where('title',"APPETISERS")->where('outlet_id',$OutletId)->first();
        $menuTitle2 = MenuTitle::where('title',"MOCKTAILS")->where('outlet_id',$OutletId)->first();
        $menuTitle3 = MenuTitle::where('title','ADD-ONS')->where('outlet_id',$OutletId)->first();
        $menuTitle4 = MenuTitle::where('title','SALADS(VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle5 = MenuTitle::where('title','SALADS(NON-VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle6 = MenuTitle::where('title','SOUPS(NON-VEG)')->where('outlet_id',$OutletId)->first();
        $menuTitle7 = MenuTitle::where('title','SOUPES(VEG)')->where('outlet_id',$OutletId)->first();
        $menuTitle8 = MenuTitle::where('title','CHINESE STATER (NON-VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle9 = MenuTitle::where('title','SIZZLERS(NON-VEG)')->where('outlet_id',$OutletId)->first();
        $menuTitle10 = MenuTitle::where('title','CHINESE STATER(VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle11 = MenuTitle::where('title','SIZZLERS(VEG)')->where('outlet_id',$OutletId)->first();
        $menuTitle12 = MenuTitle::where('title','STATER(VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle13 = MenuTitle::where('title','STATER(NON VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle14 = MenuTitle::where('title','NOODLES&RICE BOWL(NON-VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle15 = MenuTitle::where('title','NOODLES&RICE BOWL(VEG.)')->where('outlet_id',$OutletId)->first();
        $menuTitle16 = MenuTitle::where('title','INDIAN CURRIES')->where('outlet_id',$OutletId)->first();
        $menuTitle17 = MenuTitle::where('title','Chinese Curriess')->where('outlet_id',$OutletId)->first();
        $menuTitle18 = MenuTitle::where('title','Chicken Curries')->where('outlet_id',$OutletId)->first();
        $menuTitle19 = MenuTitle::where('title','Mutton Curries')->where('outlet_id',$OutletId)->first();
        $menuTitle20 = MenuTitle::where('title','Sea food curries')->where('outlet_id',$OutletId)->first();
        $menuTitle21 = MenuTitle::where('title','Chinese Curries')->where('outlet_id',$OutletId)->first();
        $menuTitle22 = MenuTitle::where('title','SHAKES "N" SHAKE')->where('outlet_id',$OutletId)->first();
        $menuTitle23 = MenuTitle::where('title','RICE/PULAV(NON-VEG)')->where('outlet_id',$OutletId)->first();
        $menuTitle24 = MenuTitle::where('title','RICE/PULAV(VEG)')->where('outlet_id',$OutletId)->first();
        $menuTitle25 = MenuTitle::where('title','TANDOORI BREADSS')->where('outlet_id',$OutletId)->first();
        //call MenuTitleId form menutitle(for wow Outlet)
        $menuTitleId=$menuTitle->id;
        $menuTitleId2=$menuTitle2->id;
        $menuTitleId3=$menuTitle3->id;
        $menuTitleId4=$menuTitle4->id;
        $menuTitleId5=$menuTitle5->id;
        $menuTitleId6=$menuTitle6->id;
        $menuTitleId7=$menuTitle7->id;
        $menuTitleId8=$menuTitle8->id;
        $menuTitleId9=$menuTitle9->id;
        $menuTitleId10=$menuTitle10->id;
        $menuTitleId11=$menuTitle11->id;
        $menuTitleId12=$menuTitle12->id;
        $menuTitleId13=$menuTitle13->id;
        $menuTitleId14=$menuTitle14->id;
        $menuTitleId15=$menuTitle15->id;
        $menuTitleId16=$menuTitle16->id;
        $menuTitleId17=$menuTitle17->id;
        $menuTitleId18=$menuTitle18->id;
        $menuTitleId19=$menuTitle19->id;
        $menuTitleId20=$menuTitle20->id;
        $menuTitleId21=$menuTitle21->id;
        $menuTitleId22=$menuTitle22->id;
        $menuTitleId23=$menuTitle23->id;
        $menuTitleId24=$menuTitle24->id;
        $menuTitleId25=$menuTitle25->id;


         //Get OutletDb from Outlet_name ("jungle bhookh")
        $Outlet=Outlet::where('Outlet_name','jungle bhookh')->first();
        $OutletId1=$Outlet->id;
        //Get MenuTitle from title FOR jungle bhookh Outlet
        $menuTitle26 = MenuTitle::where('title',"Fuel up & Enter")->where('outlet_id',$OutletId1)->first();
        $menuTitle27 = MenuTitle::where('title',"Soup")->where('outlet_id',$OutletId1)->first();
        $menuTitle28 = MenuTitle::where('title','Starter')->where('outlet_id',$OutletId1)->first();
        $menuTitle29 = MenuTitle::where('title','Chinese Starter')->where('outlet_id',$OutletId1)->first();
        $menuTitle30 = MenuTitle::where('title','Green Gravy')->where('outlet_id',$OutletId1)->first();
        $menuTitle31 = MenuTitle::where('title','Red Gravy')->where('outlet_id',$OutletId1)->first();
        $menuTitle32 = MenuTitle::where('title','Cashewnut Gravyy')->where('outlet_id',$OutletId1)->first();
        $menuTitle33 = MenuTitle::where('title','Brown Gravy')->where('outlet_id',$OutletId1)->first();
        $menuTitle34 = MenuTitle::where('title','Green Gravyy')->where('outlet_id',$OutletId1)->first();
        $menuTitle35 = MenuTitle::where('title','Red Gravyy')->where('outlet_id',$OutletId1)->first();
        $menuTitle36 = MenuTitle::where('title','Cashewnut Gravy')->where('outlet_id',$OutletId1)->first();
        $menuTitle37 = MenuTitle::where('title','Brown Gravyy')->where('outlet_id',$OutletId1)->first();
        $menuTitle38 = MenuTitle::where('title','Dal')->where('outlet_id',$OutletId1)->first();
        $menuTitle39 = MenuTitle::where('title','Rice')->where('outlet_id',$OutletId1)->first();
        $menuTitle40 = MenuTitle::where('title','Salad & Raita')->where('outlet_id',$OutletId1)->first();
        $menuTitle41 = MenuTitle::where('title','Roti')->where('outlet_id',$OutletId1)->first();
        $menuTitle42 = MenuTitle::where('title','paratha')->where('outlet_id',$OutletId1)->first();
        $menuTitle43 = MenuTitle::where('title','Naan')->where('outlet_id',$OutletId1)->first();
        $menuTitle44 = MenuTitle::where('title','Kulcha')->where('outlet_id',$OutletId1)->first();
        $menuTitle45 = MenuTitle::where('title','Baked Dish')->where('outlet_id',$OutletId1)->first();
        $menuTitle46 = MenuTitle::where('title','Chinese Veg.')->where('outlet_id',$OutletId1)->first();
        $menuTitle47 = MenuTitle::where('title','Chinese Rice')->where('outlet_id',$OutletId1)->first();
        $menuTitle48 = MenuTitle::where('title','Noodles')->where('outlet_id',$OutletId1)->first();
        $menuTitle49 = MenuTitle::where('title','Welcome Drinks')->where('outlet_id',$OutletId1)->first();
        $menuTitle50 = MenuTitle::where('title','Sweet Regular')->where('outlet_id',$OutletId1)->first();
        $menuTitle51 = MenuTitle::where('title','Farshaan')->where('outlet_id',$OutletId1)->first();
        $menuTitle52 = MenuTitle::where('title','Sweet Premium')->where('outlet_id',$OutletId1)->first();
        //call MenuTitleId form menutitle(for jungle bhookh Outlet)
        $menuTitleId26=$menuTitle26->id;
        $menuTitleId27=$menuTitle27->id;
        $menuTitleId28=$menuTitle28->id;
        $menuTitleId29=$menuTitle29->id;
        $menuTitleId30=$menuTitle30->id;
        $menuTitleId31=$menuTitle31->id;
        $menuTitleId32=$menuTitle32->id;
        $menuTitleId33=$menuTitle33->id;
        $menuTitleId34=$menuTitle34->id;
        $menuTitleId35=$menuTitle35->id;
        $menuTitleId36=$menuTitle36->id;
        $menuTitleId37=$menuTitle37->id;
        $menuTitleId38=$menuTitle38->id;
        $menuTitleId39=$menuTitle39->id;
        $menuTitleId40=$menuTitle40->id;
        $menuTitleId41=$menuTitle41->id;
        $menuTitleId42=$menuTitle42->id;
        $menuTitleId43=$menuTitle43->id;
        $menuTitleId44=$menuTitle44->id;
        $menuTitleId45=$menuTitle45->id;
        $menuTitleId46=$menuTitle46->id;
        $menuTitleId47=$menuTitle47->id;
        $menuTitleId48=$menuTitle48->id;
        $menuTitleId49=$menuTitle49->id;
        $menuTitleId50=$menuTitle50->id;
        $menuTitleId51=$menuTitle51->id;
        $menuTitleId52=$menuTitle52->id;



        //Get OutletDb from Outlet_name ("Shree Mehfil")
        $Outlet=Outlet::where('Outlet_name','Shree Mehfil')->first();
        $OutletId2=$Outlet->id;
        //Get MenuTitle from title FOR Shree Mehfil Outlet
        $menuTitle53 = MenuTitle::where('title',"STARTER")->where('outlet_id',$OutletId2)->first();
        $menuTitle54 = MenuTitle::where('title',"SOUP")->where('outlet_id',$OutletId2)->first();
        $menuTitle55 = MenuTitle::where('title','SPRING ROLL')->where('outlet_id',$OutletId2)->first();
        $menuTitle56 = MenuTitle::where('title','HOCH-POCH')->where('outlet_id',$OutletId2)->first();
        $menuTitle57 = MenuTitle::where('title','OVEN BAKED')->where('outlet_id',$OutletId2)->first();
        $menuTitle58 = MenuTitle::where('title','SALAD')->where('outlet_id',$OutletId2)->first();
        $menuTitle59 = MenuTitle::where('title','PAPAD')->where('outlet_id',$OutletId2)->first();
        $menuTitle60 = MenuTitle::where('title','CURD/RAITA')->where('outlet_id',$OutletId2)->first();
        $menuTitle61 = MenuTitle::where('title','PUNJABI VEGETABLE')->where('outlet_id',$OutletId2)->first();
        $menuTitle62 = MenuTitle::where('title','PANEER')->where('outlet_id',$OutletId2)->first();
        $menuTitle63 = MenuTitle::where('title','KOFTA')->where('outlet_id',$OutletId2)->first();
        $menuTitle64 = MenuTitle::where('title','RICE/PULAO')->where('outlet_id',$OutletId2)->first();
        $menuTitle65 = MenuTitle::where('title','TANDOOR SE')->where('outlet_id',$OutletId2)->first();
        $menuTitle66 = MenuTitle::where('title','THANDA-THANDA WITH SERVICE')->where('outlet_id',$OutletId2)->first();
        $menuTitle67 = MenuTitle::where('title','ADD-ON')->where('outlet_id',$OutletId2)->first();
        $menuTitle68 = MenuTitle::where('title','PACKING FIX PUNJABI THALI')->where('outlet_id',$OutletId2)->first();
        $menuTitle69 = MenuTitle::where('title','SWEET DISH')->where('outlet_id',$OutletId2)->first();
       //call MenuTitleId form menutitle(for Shree Mehfil Outlet)
        $menuTitleId53=$menuTitle53->id;
        $menuTitleId54=$menuTitle54->id;
        $menuTitleId55=$menuTitle55->id;
        $menuTitleId56=$menuTitle56->id;
        $menuTitleId57=$menuTitle57->id;
        $menuTitleId58=$menuTitle58->id;
        $menuTitleId59=$menuTitle59->id;
        $menuTitleId60=$menuTitle60->id;
        $menuTitleId61=$menuTitle61->id;
        $menuTitleId62=$menuTitle62->id;
        $menuTitleId63=$menuTitle63->id;
        $menuTitleId64=$menuTitle64->id;
        $menuTitleId65=$menuTitle65->id;
        $menuTitleId66=$menuTitle66->id;
        $menuTitleId67=$menuTitle67->id;
        $menuTitleId68=$menuTitle68->id;
        $menuTitleId69=$menuTitle69->id;




        //Get OutletDb from Outlet_name ("Pleasure Trove")
        $Outlet=Outlet::where('Outlet_name','Pleasure Trove')->first();
        $OutletId3=$Outlet->id;
        //Get MenuTitle from title FOR Pleasure Trove Outlet
        $menuTitle70 =MenuTitle::where('title','APPETISERES')->where('outlet_id',$OutletId3)->first();
        $menuTitle71 =MenuTitle::where('title','THIRST TEMPTATION(MOCKTAILS)')->where('outlet_id',$OutletId3)->first();
        $menuTitle72 =MenuTitle::where('title','SHAKES N SHAKE')->where('outlet_id',$OutletId3)->first();
        $menuTitle73 =MenuTitle::where('title','ICE CREAM')->where('outlet_id',$OutletId3)->first();
        $menuTitle74 =MenuTitle::where('title','ETHICH DELIGHTS')->where('outlet_id',$OutletId3)->first();
        $menuTitle75 =MenuTitle::where('title','SOUPS')->where('outlet_id',$OutletId3)->first();
        $menuTitle76 =MenuTitle::where('title','AATISH-E-TANDOOR(NON-VEG)')->where('outlet_id',$OutletId3)->first();
        $menuTitle77 =MenuTitle::where('title','AATISH-E-TANDOOR(VEG)')->where('outlet_id',$OutletId3)->first();
        $menuTitle78 =MenuTitle::where('title','SHORT EATS(NON-VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle79 =MenuTitle::where('title','SHORT EATS(VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle80 =MenuTitle::where('title','SIZZLERS(NON-VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle81 =MenuTitle::where('title','SIZZLERS(VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle82 =MenuTitle::where('title','BAKED DISHES/PASTA(NON-VEG)')->where('outlet_id',$OutletId3)->first();
        $menuTitle83 =MenuTitle::where('title','BAKED DISHES/PASTA(VEG)')->where('outlet_id',$OutletId3)->first();
        $menuTitle84 =MenuTitle::where('title','CHICKEN(NON-VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle85 =MenuTitle::where('title','MUTTON(NON-VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle86 =MenuTitle::where('title','SEA FOOD(NON-VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle87 =MenuTitle::where('title','CHINESE/THAI MAIN COURSE(NON-VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle88 =MenuTitle::where('title','CHINESE/THAI MAIN COURSE(VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle89 =MenuTitle::where('title','SUBZI KI KHASHIYAT(MAIN COURSE-VEG')->where('outlet_id',$OutletId3)->first();
        $menuTitle90 =MenuTitle::where('title','NOODLES & RICE BOWL(NON-VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle91 =MenuTitle::where('title','NOODLES & RICE BOWL(VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle92 =MenuTitle::where('title','SALADS')->where('outlet_id',$OutletId3)->first();
        $menuTitle93 =MenuTitle::where('title','ADD ON')->where('outlet_id',$OutletId3)->first();
        $menuTitle94 =MenuTitle::where('title','TANDOORI BREADS')->where('outlet_id',$OutletId3)->first();
        $menuTitle95 =MenuTitle::where('title','RICE/PULAV (NON. VEG.)')->where('outlet_id',$OutletId3)->first();
        $menuTitle96 =MenuTitle::where('title','RICE/PULAV (VEG.)')->where('outlet_id',$OutletId3)->first();
        //call MenuTitleId form menutitle(for Pleasure Trove Outlet)
        $menuTitleId70=$menuTitle70->id;
        $menuTitleId71=$menuTitle71->id;
        $menuTitleId72=$menuTitle72->id;
        $menuTitleId73=$menuTitle73->id;
        $menuTitleId74=$menuTitle74->id;
        $menuTitleId75=$menuTitle75->id;
        $menuTitleId76=$menuTitle76->id;
        $menuTitleId77=$menuTitle77->id;
        $menuTitleId78=$menuTitle78->id;
        $menuTitleId79=$menuTitle79->id;
        $menuTitleId80=$menuTitle80->id;
        $menuTitleId81=$menuTitle81->id;
        $menuTitleId82=$menuTitle82->id;
        $menuTitleId83=$menuTitle83->id;
        $menuTitleId84=$menuTitle84->id;
        $menuTitleId85=$menuTitle85->id;
        $menuTitleId86=$menuTitle86->id;
        $menuTitleId87=$menuTitle87->id;
        $menuTitleId88=$menuTitle88->id;
        $menuTitleId89=$menuTitle89->id;
        $menuTitleId90=$menuTitle90->id;
        $menuTitleId91=$menuTitle91->id;
        $menuTitleId92=$menuTitle92->id;
        $menuTitleId93=$menuTitle93->id;
        $menuTitleId94=$menuTitle94->id;
        $menuTitleId95=$menuTitle95->id;
        $menuTitleId96=$menuTitle96->id;



        //Get OutletDb from Outlet_name ("Shambhus Coffee Bar")
        $Outlet=Outlet::where('Outlet_name','Shambhus Coffee Bar')->first();
        $OutletId4=$Outlet->id;
        //Get MenuTitle from title FOR  Shambhus Coffee Bar
        $menuTitle97 = MenuTitle::where('title','Hot Bread')->where('outlet_id',$OutletId4)->first();
        $menuTitle98 = MenuTitle::where('title','Hot Dog & Burger')->where('outlet_id',$OutletId4)->first();
        $menuTitle99 = MenuTitle::where('title','Bhel')->where('outlet_id',$OutletId4)->first();
        $menuTitle100 = MenuTitle::where('title','Sandwiches')->where('outlet_id',$OutletId4)->first();
        $menuTitle101 = MenuTitle::where('title','Sp. Grill Sandwiches')->where('outlet_id',$OutletId4)->first();
        $menuTitle102 = MenuTitle::where('title','Club Sandwiches')->where('outlet_id',$OutletId4)->first();
        $menuTitle103 = MenuTitle::where('title','Pizza(Soft Base)')->where('outlet_id',$OutletId4)->first();
        $menuTitle104 = MenuTitle::where('title','Pizza(Hard Base)')->where('outlet_id',$OutletId4)->first();
        $menuTitle105 = MenuTitle::where('title','Cold Coffee')->where('outlet_id',$OutletId4)->first();
        $menuTitle106 = MenuTitle::where('title','Frappe Cafe')->where('outlet_id',$OutletId4)->first();
        $menuTitle107 = MenuTitle::where('title','Chocolate')->where('outlet_id',$OutletId4)->first();
        $menuTitle108 = MenuTitle::where('title','Coco')->where('outlet_id',$OutletId4)->first();
        $menuTitle109 = MenuTitle::where('title','Hot Espresso')->where('outlet_id',$OutletId4)->first();
        $menuTitle110 = MenuTitle::where('title','Mocktails')->where('outlet_id',$OutletId4)->first();
        $menuTitle111 = MenuTitle::where('title','Slush')->where('outlet_id',$OutletId4)->first();
        $menuTitle112 = MenuTitle::where('title','Ice Tea')->where('outlet_id',$OutletId4)->first();
        $menuTitle113 = MenuTitle::where('title','Masala Tea')->where('outlet_id',$OutletId4)->first();
        $menuTitle114 = MenuTitle::where('title','Milk Shake')->where('outlet_id',$OutletId4)->first();
        $menuTitle115 = MenuTitle::where('title','Lassi')->where('outlet_id',$OutletId4)->first();
        $menuTitle116 = MenuTitle::where('title','Curacao')->where('outlet_id',$OutletId4)->first();
        $menuTitle117 = MenuTitle::where('title','Faluda')->where('outlet_id',$OutletId4)->first();
        $menuTitle118 = MenuTitle::where('title','Fruit Juice')->where('outlet_id',$OutletId4)->first();
        $menuTitle119 = MenuTitle::where('title','Toast')->where('outlet_id',$OutletId4)->first();
        $menuTitle120 = MenuTitle::where('title','French Fries')->where('outlet_id',$OutletId4)->first();
        $menuTitle121 = MenuTitle::where('title','Combo Offer')->where('outlet_id',$OutletId4)->first();

        //call MenuTitleId form menutitle(for Shambhus Coffee Bar)
        $menuTitleId97=$menuTitle97->id;
        $menuTitleId98=$menuTitle98->id;
        $menuTitleId99=$menuTitle99->id;
        $menuTitleId100=$menuTitle100->id;
        $menuTitleId101=$menuTitle101->id;
        $menuTitleId102=$menuTitle102->id;
        $menuTitleId103=$menuTitle103->id;
        $menuTitleId104=$menuTitle104->id;
        $menuTitleId105=$menuTitle105->id;
        $menuTitleId106=$menuTitle106->id;
        $menuTitleId107=$menuTitle107->id;
        $menuTitleId108=$menuTitle108->id;
        $menuTitleId109=$menuTitle109->id;
        $menuTitleId110=$menuTitle110->id;
        $menuTitleId111=$menuTitle111->id;
        $menuTitleId112=$menuTitle112->id;
        $menuTitleId113=$menuTitle113->id;
        $menuTitleId114=$menuTitle114->id;
        $menuTitleId115=$menuTitle115->id;
        $menuTitleId116=$menuTitle116->id;
        $menuTitleId117=$menuTitle117->id;
        $menuTitleId118=$menuTitle118->id;
        $menuTitleId119=$menuTitle119->id;
        $menuTitleId120=$menuTitle120->id;
        $menuTitleId121=$menuTitle121->id;


        //Get OutletDb from Outlet_name ("Cafe Coffee Bar")
        $Outlet=Outlet::where('Outlet_name','Cafe Coffee Day')->first();
        $OutletId5=$Outlet->id;
        //Get MenuTitle from title FOR  Cafe Coffee Day
        $menuTitle122 =MenuTitle::where('title','Hot Coffees')->where('outlet_id',$OutletId5)->first();
        $menuTitle123 =MenuTitle::where('title','Cold Coffees')->where('outlet_id',$OutletId5)->first();
        $menuTitle124 =MenuTitle::where('title','International Coffees')->where('outlet_id',$OutletId5)->first();
        $menuTitle125 =MenuTitle::where('title','Friends of Frappe')->where('outlet_id',$OutletId5)->first();
        $menuTitle126 =MenuTitle::where('title','Coffee Top-ups')->where('outlet_id',$OutletId5)->first();
        $menuTitle127 =MenuTitle::where('title','Chocoholicas')->where('outlet_id',$OutletId5)->first();
        $menuTitle128 =MenuTitle::where('title','Hot Teas')->where('outlet_id',$OutletId5)->first();
        $menuTitle129 =MenuTitle::where('title','Fruiteazers')->where('outlet_id',$OutletId5)->first();
        $menuTitle130 =MenuTitle::where('title','Lemonades')->where('outlet_id',$OutletId5)->first();
        $menuTitle131 =MenuTitle::where('title','Bites')->where('outlet_id',$OutletId5)->first();
        $menuTitle132 =MenuTitle::where('title','Frosteas')->where('outlet_id',$OutletId5)->first();
        $menuTitle133 =MenuTitle::where('title','Big Bites')->where('outlet_id',$OutletId5)->first();
        $menuTitle134 =MenuTitle::where('title','Combo Offers')->where('outlet_id',$OutletId5)->first();
        $menuTitle135 =MenuTitle::where('title','Sweet Treats')->where('outlet_id',$OutletId5)->first();
        $menuTitle136 =MenuTitle::where('title','Shorts')->where('outlet_id',$OutletId5)->first();
        $menuTitle137 =MenuTitle::where('title','Ice Cream Top-Ups')->where('outlet_id',$OutletId5)->first();
        $menuTitle138 =MenuTitle::where('title','Sundaes')->where('outlet_id',$OutletId5)->first();
        $menuTitle139 =MenuTitle::where('title','The Cake')->where('outlet_id',$OutletId5)->first();
        //call MenuTitleId form menutitle(for Cafe Coffee Day)
        $menuTitleId122=$menuTitle122->id;
        $menuTitleId123=$menuTitle123->id;
        $menuTitleId124=$menuTitle124->id;
        $menuTitleId125=$menuTitle125->id;
        $menuTitleId126=$menuTitle126->id;
        $menuTitleId127=$menuTitle127->id;
        $menuTitleId128=$menuTitle128->id;
        $menuTitleId129=$menuTitle129->id;
        $menuTitleId130=$menuTitle130->id;
        $menuTitleId131=$menuTitle131->id;
        $menuTitleId132=$menuTitle132->id;
        $menuTitleId133=$menuTitle133->id;
        $menuTitleId134=$menuTitle134->id;
        $menuTitleId135=$menuTitle135->id;
        $menuTitleId136=$menuTitle136->id;
        $menuTitleId137=$menuTitle137->id;
        $menuTitleId138=$menuTitle138->id;
        $menuTitleId139=$menuTitle139->id;



        //Get OutletDb from Outlet_name ("Saffron Outlet")
        $Outlet=Outlet::where('Outlet_name',"Saffron Outlet")->first();
        //call OutletId from Outlet
        $OutletId6=$Outlet->id;
        //Get MenuTitle from title FOR Saffron Outlet
        $menuTitle140 = MenuTitle::where('title',"Assorted Beverages")->where('outlet_id',$OutletId6)->first();
        $menuTitle141 = MenuTitle::where('title',"Khane Se Pehle")->where('outlet_id',$OutletId6)->first();
        $menuTitle142 = MenuTitle::where('title','Continental Masti')->where('outlet_id',$OutletId6)->first();
        $menuTitle143 = MenuTitle::where('title','Starter')->where('outlet_id',$OutletId6)->first();
        $menuTitle145 = MenuTitle::where('title','Main Course')->where('outlet_id',$OutletId6)->first();
        $menuTitle146 = MenuTitle::where('title','Taaza Tarkariyan')->where('outlet_id',$OutletId6)->first();
        $menuTitle147 = MenuTitle::where('title','Kofta Ka Khazana')->where('outlet_id',$OutletId6)->first();
        $menuTitle148 = MenuTitle::where('title','Tandoor Se')->where('outlet_id',$OutletId6)->first();
        $menuTitle149 = MenuTitle::where('title','Basmati Ka Jaadu')->where('outlet_id',$OutletId6)->first();
        $menuTitle150 = MenuTitle::where('title','Tadka Marke')->where('outlet_id',$OutletId6)->first();
        $menuTitle151 = MenuTitle::where('title','Saathmein')->where('outlet_id',$OutletId6)->first();
        $menuTitle152 = MenuTitle::where('title','Dahi ki Jugalbandhi')->where('outlet_id',$OutletId6)->first();
        $menuTitle153 = MenuTitle::where('title','IceCream and Desserts')->where('outlet_id',$OutletId6)->first();
        //call MenuTitleId form menutitle(for Saffron Outlet)
        $menuTitleId140=$menuTitle140->id;
        $menuTitleId141=$menuTitle141->id;
        $menuTitleId142=$menuTitle142->id;
        $menuTitleId143=$menuTitle143->id;
        $menuTitleId145=$menuTitle145->id;
        $menuTitleId146=$menuTitle146->id;
        $menuTitleId147=$menuTitle147->id;
        $menuTitleId148=$menuTitle148->id;
        $menuTitleId149=$menuTitle149->id;
        $menuTitleId150=$menuTitle150->id;
        $menuTitleId151=$menuTitle151->id;
        $menuTitleId152=$menuTitle152->id;
        $menuTitleId153=$menuTitle153->id;

        //Get OutletDb from Outlet_name ("Sankalp Outlet")
        $Outlet=Outlet::where('Outlet_name',"Sankalp Outlet")->first();
        //call OutletId from Outlet
        $OutletId7=$Outlet->id;
        //Get MenuTitle from title FOR Sankalp Outlet
        $menuTitle154 = MenuTitle::where('title','Assorted Beverages')->where('outlet_id',$OutletId7)->first();
        $menuTitle155 = MenuTitle::where('title','All Time Favorite')->where('outlet_id',$OutletId7)->first();
        $menuTitle156 = MenuTitle::where('title','Idli Stall')->where('outlet_id',$OutletId7)->first();
        $menuTitle157 = MenuTitle::where('title','Special Idli')->where('outlet_id',$OutletId7)->first();
        $menuTitle158 = MenuTitle::where('title','Vada')->where('outlet_id',$OutletId7)->first();
        $menuTitle159 = MenuTitle::where('title','Dashing Dosaz')->where('outlet_id',$OutletId7)->first();
        $menuTitle160 = MenuTitle::where('title','Speciality Dosaz')->where('outlet_id',$OutletId7)->first();
        $menuTitle161 = MenuTitle::where('title','Ravishing Rava')->where('outlet_id',$OutletId7)->first();
        $menuTitle162 = MenuTitle::where('title','Amezing Uthappa')->where('outlet_id',$OutletId7)->first();
        $menuTitle163 = MenuTitle::where('title','Curries')->where('outlet_id',$OutletId7)->first();
        $menuTitle164 = MenuTitle::where('title','Rice')->where('outlet_id',$OutletId7)->first();
        $menuTitle165 = MenuTitle::where('title','Sweet')->where('outlet_id',$OutletId7)->first();
        $menuTitle166 = MenuTitle::where('title','Extra Items')->where('outlet_id',$OutletId7)->first();
        $menuTitle167 = MenuTitle::where('title','Ice Creams & Desserts')->where('outlet_id',$OutletId7)->first();
        //call MenuTitleId form menutitle(for Sankalp Outlet)
        $menuTitleId154=$menuTitle154->id;
        $menuTitleId155=$menuTitle155->id;
        $menuTitleId156=$menuTitle156->id;
        $menuTitleId157=$menuTitle157->id;
        $menuTitleId158=$menuTitle158->id;
        $menuTitleId159=$menuTitle159->id;
        $menuTitleId160=$menuTitle160->id;
        $menuTitleId161=$menuTitle161->id;
        $menuTitleId162=$menuTitle162->id;
        $menuTitleId163=$menuTitle163->id;
        $menuTitleId164=$menuTitle164->id;
        $menuTitleId165=$menuTitle165->id;
        $menuTitleId166=$menuTitle166->id;
        $menuTitleId167=$menuTitle167->id;



        //Get OutletDb from Outlet_name ("Havmor Outlet")
        $Outlet=Outlet::where('Outlet_name',"Havmor Outlet")->first();
        //call OutletId from Outlet
        $OutletId8=$Outlet->id;
        //Get MenuTitle from title FOR Havmor Outlet
        $menuTitle168 = MenuTitle::where('title','Starters')->where('outlet_id',$OutletId8)->first();
        $menuTitle169 = MenuTitle::where('title','Beverages')->where('outlet_id',$OutletId8)->first();
        $menuTitle170 = MenuTitle::where('title','Milk Shakes')->where('outlet_id',$OutletId8)->first();
        $menuTitle171 = MenuTitle::where('title','Starters-I')->where('outlet_id',$OutletId8)->first();
        $menuTitle172 = MenuTitle::where('title','Starters-II')->where('outlet_id',$OutletId8)->first();
        $menuTitle173 = MenuTitle::where('title','Salads')->where('outlet_id',$OutletId8)->first();
        $menuTitle174 = MenuTitle::where('title','Accompaniments')->where('outlet_id',$OutletId8)->first();
        $menuTitle175 = MenuTitle::where('title','Soups')->where('outlet_id',$OutletId8)->first();
        $menuTitle176 = MenuTitle::where('title','Sizzlers')->where('outlet_id',$OutletId8)->first();
        $menuTitle177 = MenuTitle::where('title','Continental')->where('outlet_id',$OutletId8)->first();
        $menuTitle178 = MenuTitle::where('title','Indian')->where('outlet_id',$OutletId8)->first();
        $menuTitle179 = MenuTitle::where('title','Rice and Roti')->where('outlet_id',$OutletId8)->first();
        $menuTitle180 = MenuTitle::where('title','Oriental')->where('outlet_id',$OutletId8)->first();
        $menuTitle181 = MenuTitle::where('title','Rice and Noodles')->where('outlet_id',$OutletId8)->first();
        $menuTitle182 = MenuTitle::where('title','Exotic Sundaes')->where('outlet_id',$OutletId8)->first();
        $menuTitle183 = MenuTitle::where('title','Ice Cream Scoops')->where('outlet_id',$OutletId8)->first();
        //call MenuTitleId form menutitle(for Havmor Outlet)
        $menuTitleId168=$menuTitle168->id;
        $menuTitleId169=$menuTitle169->id;
        $menuTitleId170=$menuTitle170->id;
        $menuTitleId171=$menuTitle171->id;
        $menuTitleId172=$menuTitle172->id;
        $menuTitleId173=$menuTitle173->id;
        $menuTitleId174=$menuTitle174->id;
        $menuTitleId175=$menuTitle175->id;
        $menuTitleId176=$menuTitle176->id;
        $menuTitleId177=$menuTitle177->id;
        $menuTitleId178=$menuTitle178->id;
        $menuTitleId179=$menuTitle179->id;
        $menuTitleId180=$menuTitle180->id;
        $menuTitleId181=$menuTitle181->id;
        $menuTitleId182=$menuTitle182->id;
        $menuTitleId183=$menuTitle183->id;


        //Get OutletDb from Outlet_name ("Not Just Grill")
        $Outlet=Outlet::where('Outlet_name',"Not Just Grill")->first();
        //call OutletId from Outlet
        $OutletId9=$Outlet->id;
        //Get MenuTitle from title FOR Not Just Grill
        $menuTitle184 = MenuTitle::where('title','BBQ')->where('outlet_id',$OutletId9)->first();
        $menuTitle185 = MenuTitle::where('title','Clay Oven')->where('outlet_id',$OutletId9)->first();
        $menuTitle186 = MenuTitle::where('title','Sizzler')->where('outlet_id',$OutletId9)->first();
        $menuTitle187 = MenuTitle::where('title','Teppanyaki & Griddle')->where('outlet_id',$OutletId9)->first();
        $menuTitle188 = MenuTitle::where('title','Oriental')->where('outlet_id',$OutletId9)->first();
        $menuTitle189 = MenuTitle::where('title','Soups')->where('outlet_id',$OutletId9)->first();
        $menuTitle190 = MenuTitle::where('title','Sides')->where('outlet_id',$OutletId9)->first();
        $menuTitle191 = MenuTitle::where('title','Indian Mains')->where('outlet_id',$OutletId9)->first();
        $menuTitle192 = MenuTitle::where('title','Mediterranean Et Latine')->where('outlet_id',$OutletId9)->first();
        $menuTitle193 = MenuTitle::where('title','Desserts from Turquoise Villa')->where('outlet_id',$OutletId9)->first();
        $menuTitle194 = MenuTitle::where('title','Mocktails')->where('outlet_id',$OutletId9)->first();
        $menuTitle195 = MenuTitle::where('title','Coolers')->where('outlet_id',$OutletId9)->first();
        $menuTitle196 = MenuTitle::where('title','Shakes')->where('outlet_id',$OutletId9)->first();
        $menuTitle197 = MenuTitle::where('title','Juices')->where('outlet_id',$OutletId9)->first();
        $menuTitle198 = MenuTitle::where('title','Teas')->where('outlet_id',$OutletId9)->first();
        $menuTitle199 = MenuTitle::where('title','Coffees')->where('outlet_id',$OutletId9)->first();
        $menuTitle200 = MenuTitle::where('title','Chocolates')->where('outlet_id',$OutletId9)->first();
        //call MenuTitleId form menutitle(for Not Just Grill)
        $menuTitleId184=$menuTitle184->id;
        $menuTitleId185=$menuTitle185->id;
        $menuTitleId186=$menuTitle186->id;
        $menuTitleId187=$menuTitle187->id;
        $menuTitleId188=$menuTitle188->id;
        $menuTitleId189=$menuTitle189->id;
        $menuTitleId190=$menuTitle190->id;
        $menuTitleId191=$menuTitle191->id;
        $menuTitleId192=$menuTitle192->id;
        $menuTitleId193=$menuTitle193->id;
        $menuTitleId194=$menuTitle194->id;
        $menuTitleId195=$menuTitle195->id;
        $menuTitleId196=$menuTitle196->id;
        $menuTitleId197=$menuTitle197->id;
        $menuTitleId198=$menuTitle198->id;
        $menuTitleId199=$menuTitle199->id;
        $menuTitleId200=$menuTitle200->id;



        //Get OutletDb from Outlet_name ("Dinner Bell2")
        $Outlet=Outlet::where('Outlet_name',"Dinner Bell2")->first();
        //call OutletId from Outlet
        $OutletId10=$Outlet->id;
        //Get MenuTitle from title FOR Dinner Bell2
        $menuTitle201 = MenuTitle::where('title','Appetizers')->where('outlet_id',$OutletId10)->first();
        $menuTitle202 = MenuTitle::where('title','Mocktails')->where('outlet_id',$OutletId10)->first();
        $menuTitle203 = MenuTitle::where('title','Soups')->where('outlet_id',$OutletId10)->first();
        $menuTitle204 = MenuTitle::where('title','Starters')->where('outlet_id',$OutletId10)->first();
        $menuTitle205 = MenuTitle::where('title','Tandoor Express')->where('outlet_id',$OutletId10)->first();
        $menuTitle206 = MenuTitle::where('title','Salad')->where('outlet_id',$OutletId10)->first();
        $menuTitle207 = MenuTitle::where('title','Accompniments')->where('outlet_id',$OutletId10)->first();
        $menuTitle208 = MenuTitle::where('title','Continental')->where('outlet_id',$OutletId10)->first();
        $menuTitle209 = MenuTitle::where('title','Itallian')->where('outlet_id',$OutletId10)->first();
        $menuTitle210 = MenuTitle::where('title','Mexican')->where('outlet_id',$OutletId10)->first();
        $menuTitle211 = MenuTitle::where('title','Indian Vegetables')->where('outlet_id',$OutletId10)->first();
        $menuTitle212 = MenuTitle::where('title','Indian Paneer')->where('outlet_id',$OutletId10)->first();
        $menuTitle213 = MenuTitle::where('title','Kofta')->where('outlet_id',$OutletId10)->first();
        $menuTitle214 = MenuTitle::where('title','Dal')->where('outlet_id',$OutletId10)->first();
        $menuTitle215 = MenuTitle::where('title','Rice')->where('outlet_id',$OutletId10)->first();
        $menuTitle216 = MenuTitle::where('title','Roti Tawa')->where('outlet_id',$OutletId10)->first();
        $menuTitle217 = MenuTitle::where('title','Roti Tandoori')->where('outlet_id',$OutletId10)->first();
        $menuTitle218 = MenuTitle::where('title','Naan')->where('outlet_id',$OutletId10)->first();
        $menuTitle219 = MenuTitle::where('title','Kulcha')->where('outlet_id',$OutletId10)->first();
        $menuTitle220 = MenuTitle::where('title','Paratha')->where('outlet_id',$OutletId10)->first();
        $menuTitle221 = MenuTitle::where('title','Sandwich')->where('outlet_id',$OutletId10)->first();
        $menuTitle222 = MenuTitle::where('title','Pizza')->where('outlet_id',$OutletId10)->first();
        $menuTitle223 = MenuTitle::where('title','Dessert')->where('outlet_id',$OutletId10)->first();
        $menuTitle224 = MenuTitle::where('title','Milk Shake')->where('outlet_id',$OutletId10)->first();
        $menuTitle225 = MenuTitle::where('title','Executive Power Lunch')->where('outlet_id',$OutletId10)->first();
        $menuTitle226 = MenuTitle::where('title','With Baked Dish Power Lunch')->where('outlet_id',$OutletId10)->first();
        $menuTitle227 = MenuTitle::where('title','Pack Lunch')->where('outlet_id',$OutletId10)->first();
        $menuTitle228 = MenuTitle::where('title','Fix Lunch')->where('outlet_id',$OutletId10)->first();
        $menuTitle229 = MenuTitle::where('title','Deluxe Lunch')->where('outlet_id',$OutletId10)->first();
        //call MenuTitleId form menutitle(for Dinner Bell2)
        $menuTitleId201=$menuTitle201->id;
        $menuTitleId202=$menuTitle202->id;
        $menuTitleId203=$menuTitle203->id;
        $menuTitleId204=$menuTitle204->id;
        $menuTitleId205=$menuTitle205->id;
        $menuTitleId206=$menuTitle206->id;
        $menuTitleId207=$menuTitle207->id;
        $menuTitleId208=$menuTitle208->id;
        $menuTitleId209=$menuTitle209->id;
        $menuTitleId210=$menuTitle210->id;
        $menuTitleId211=$menuTitle211->id;
        $menuTitleId212=$menuTitle212->id;
        $menuTitleId213=$menuTitle213->id;
        $menuTitleId214=$menuTitle214->id;
        $menuTitleId215=$menuTitle215->id;
        $menuTitleId216=$menuTitle216->id;
        $menuTitleId217=$menuTitle217->id;
        $menuTitleId218=$menuTitle218->id;
        $menuTitleId219=$menuTitle219->id;
        $menuTitleId220=$menuTitle220->id;
        $menuTitleId221=$menuTitle221->id;
        $menuTitleId222=$menuTitle222->id;
        $menuTitleId223=$menuTitle223->id;
        $menuTitleId224=$menuTitle224->id;
        $menuTitleId225=$menuTitle225->id;
        $menuTitleId226=$menuTitle226->id;
        $menuTitleId227=$menuTitle227->id;
        $menuTitleId228=$menuTitle228->id;
        $menuTitleId229=$menuTitle229->id;



        //Get OutletDb from Outlet_name ("Cafe Upper Crust")
        $Outlet=Outlet::where('Outlet_name',"Cafe Upper Crust")->first();
        //call OutletId from Outlet
        $OutletId11=$Outlet->id;
        //Get MenuTitle from title FOR Cafe Upper Crust
        $menuTitle230 = MenuTitle::where('title','Veg Starter')->where('outlet_id',$OutletId11)->first();
        $menuTitle231 = MenuTitle::where('title','Non-Veg Starter')->where('outlet_id',$OutletId11)->first();
        $menuTitle232 = MenuTitle::where('title','Quenchers')->where('outlet_id',$OutletId11)->first();
        $menuTitle233 = MenuTitle::where('title','Veg. Soups')->where('outlet_id',$OutletId11)->first();
        $menuTitle234 = MenuTitle::where('title','Non Veg. Soups')->where('outlet_id',$OutletId11)->first();
        $menuTitle235 = MenuTitle::where('title','Veg. Frankies')->where('outlet_id',$OutletId11)->first();
        $menuTitle236 = MenuTitle::where('title','NonVeg. Frankies')->where('outlet_id',$OutletId11)->first();
        $menuTitle237 = MenuTitle::where('title','Veg. Open & Shut Cases')->where('outlet_id',$OutletId11)->first();
        $menuTitle238 = MenuTitle::where('title','NonVeg. Open & Shut Cases')->where('outlet_id',$OutletId11)->first();
        $menuTitle239 = MenuTitle::where('title','Veg. Ovenscapes')->where('outlet_id',$OutletId11)->first();
        $menuTitle240 = MenuTitle::where('title','Veg. Pizza')->where('outlet_id',$OutletId11)->first();
        $menuTitle241 = MenuTitle::where('title','Non Veg. Pizza')->where('outlet_id',$OutletId11)->first();
        $menuTitle242 = MenuTitle::where('title','French Fries')->where('outlet_id',$OutletId11)->first();
        $menuTitle243 = MenuTitle::where('title','Veg. BBQ')->where('outlet_id',$OutletId11)->first();
        $menuTitle244 = MenuTitle::where('title','Veg. Kebabs')->where('outlet_id',$OutletId11)->first();
        $menuTitle245 = MenuTitle::where('title','Non Veg. BBQ')->where('outlet_id',$OutletId11)->first();
        $menuTitle246 = MenuTitle::where('title','Non Veg. Kebabs')->where('outlet_id',$OutletId11)->first();
        $menuTitle247 = MenuTitle::where('title','Non Veg. Ovenscapes')->where('outlet_id',$OutletId11)->first();
        $menuTitle250 = MenuTitle::where('title','Veg. Pasta')->where('outlet_id',$OutletId11)->first();
        $menuTitle251 = MenuTitle::where('title','Non Veg. Pasta')->where('outlet_id',$OutletId11)->first();
        $menuTitle252 = MenuTitle::where('title','Veg. Chinese')->where('outlet_id',$OutletId11)->first();
        $menuTitle253 = MenuTitle::where('title','Non Veg. Chinese')->where('outlet_id',$OutletId11)->first();
        $menuTitle254 = MenuTitle::where('title','Veg. Thai')->where('outlet_id',$OutletId11)->first();
        $menuTitle255 = MenuTitle::where('title','Non Veg. Thai')->where('outlet_id',$OutletId11)->first();
        $menuTitle256 = MenuTitle::where('title','Veg. Sizzlers')->where('outlet_id',$OutletId11)->first();
        $menuTitle257 = MenuTitle::where('title','Non Veg. Sizzlers')->where('outlet_id',$OutletId11)->first();
        $menuTitle258 = MenuTitle::where('title','Veg. Steaks')->where('outlet_id',$OutletId11)->first();
        $menuTitle259 = MenuTitle::where('title','Non Veg. Steaks')->where('outlet_id',$OutletId11)->first();
        $menuTitle260 = MenuTitle::where('title','Fusion Food')->where('outlet_id',$OutletId11)->first();
        $menuTitle261 = MenuTitle::where('title','Veg. Indian Cuisine')->where('outlet_id',$OutletId11)->first();
        $menuTitle262 = MenuTitle::where('title','NonVeg. Indian Cuisine')->where('outlet_id',$OutletId11)->first();
        //call MenuTitleId form menutitle(for Cafe Upper Crust)
        $menuTitleId230=$menuTitle230->id;
        $menuTitleId231=$menuTitle231->id;
        $menuTitleId232=$menuTitle232->id;
        $menuTitleId233=$menuTitle233->id;
        $menuTitleId234=$menuTitle234->id;
        $menuTitleId235=$menuTitle235->id;
        $menuTitleId236=$menuTitle236->id;
        $menuTitleId237=$menuTitle237->id;
        $menuTitleId238=$menuTitle238->id;
        $menuTitleId239=$menuTitle239->id;
        $menuTitleId240=$menuTitle240->id;
        $menuTitleId241=$menuTitle241->id;
        $menuTitleId242=$menuTitle242->id;
        $menuTitleId243=$menuTitle243->id;
        $menuTitleId244=$menuTitle244->id;
        $menuTitleId245=$menuTitle245->id;
        $menuTitleId246=$menuTitle246->id;
        $menuTitleId247=$menuTitle247->id;
        $menuTitleId250=$menuTitle250->id;
        $menuTitleId251=$menuTitle251->id;
        $menuTitleId252=$menuTitle252->id;
        $menuTitleId253=$menuTitle253->id;
        $menuTitleId254=$menuTitle254->id;
        $menuTitleId255=$menuTitle255->id;
        $menuTitleId256=$menuTitle256->id;
        $menuTitleId257=$menuTitle257->id;
        $menuTitleId258=$menuTitle258->id;
        $menuTitleId259=$menuTitle259->id;
        $menuTitleId260=$menuTitle260->id;
        $menuTitleId261=$menuTitle261->id;
        $menuTitleId262=$menuTitle262->id;



        //Get OutletDb from Outlet_name ("Cellad Eatery")
        $Outlet=Outlet::where('Outlet_name',"Cellad Eatery")->first();
        //call OutletId from Outlet
        $OutletId12=$Outlet->id;
        //Get MenuTitle from title FOR Cellad Eatery
        $menuTitle263 = MenuTitle::where('title','Buffet Station')->where('outlet_id',$OutletId12)->first();
        //call MenuTitleId form menutitle(for Cellad Eatery)
        $menuTitleId263=$menuTitle263->id;


        //Get OutletDb from Outlet_name ("Toritos")
        $Outlet=Outlet::where('Outlet_name',"Toritos")->first();
        //call OutletId from Outlet
        $OutletId13=$Outlet->id;
        //Get MenuTitle from title FOR Toritos
        $menuTitle264 = MenuTitle::where('title','Starters')->where('outlet_id',$OutletId13)->first();
        $menuTitle265 = MenuTitle::where('title','Mocktails')->where('outlet_id',$OutletId13)->first();
        $menuTitle266 = MenuTitle::where('title','Soups')->where('outlet_id',$OutletId13)->first();
        $menuTitle267 = MenuTitle::where('title','Italian')->where('outlet_id',$OutletId13)->first();
        $menuTitle268 = MenuTitle::where('title','Tapas')->where('outlet_id',$OutletId13)->first();
        $menuTitle269 = MenuTitle::where('title','Salads')->where('outlet_id',$OutletId13)->first();
        $menuTitle270 = MenuTitle::where('title','World Cuisines')->where('outlet_id',$OutletId13)->first();
        $menuTitle271 = MenuTitle::where('title','Mexican')->where('outlet_id',$OutletId13)->first();
        $menuTitle272 = MenuTitle::where('title','Stone Baked Thin Crust Pizza')->where('outlet_id',$OutletId13)->first();
        $menuTitle273 = MenuTitle::where('title','Swiss')->where('outlet_id',$OutletId13)->first();
        $menuTitle274 = MenuTitle::where('title','Oven Baked')->where('outlet_id',$OutletId13)->first();
        $menuTitle275 = MenuTitle::where('title','Mediterranean')->where('outlet_id',$OutletId13)->first();
        $menuTitle276 = MenuTitle::where('title','Accompaniments')->where('outlet_id',$OutletId13)->first();
        $menuTitle277 = MenuTitle::where('title','Desserts')->where('outlet_id',$OutletId13)->first();
        $menuTitle278 = MenuTitle::where('title','Cold Beverages')->where('outlet_id',$OutletId13)->first();
        $menuTitle279 = MenuTitle::where('title','Pasta Station')->where('outlet_id',$OutletId13)->first();
        $menuTitle280 = MenuTitle::where('title','Hot Beverages')->where('outlet_id',$OutletId13)->first();
        $menuTitle281 = MenuTitle::where('title','Coffee')->where('outlet_id',$OutletId13)->first();
        $menuTitle282 = MenuTitle::where('title','Tea')->where('outlet_id',$OutletId13)->first();
        $menuTitle283 = MenuTitle::where('title','High Tea')->where('outlet_id',$OutletId13)->first();
        //call MenuTitleId form menutitle(for Toritos)
        $menuTitleId264=$menuTitle264->id;
        $menuTitleId265=$menuTitle265->id;
        $menuTitleId266=$menuTitle266->id;
        $menuTitleId267=$menuTitle267->id;
        $menuTitleId268=$menuTitle268->id;
        $menuTitleId269=$menuTitle269->id;
        $menuTitleId270=$menuTitle270->id;
        $menuTitleId271=$menuTitle271->id;
        $menuTitleId272=$menuTitle272->id;
        $menuTitleId273=$menuTitle273->id;
        $menuTitleId274=$menuTitle274->id;
        $menuTitleId275=$menuTitle275->id;
        $menuTitleId276=$menuTitle276->id;
        $menuTitleId277=$menuTitle277->id;
        $menuTitleId278=$menuTitle278->id;
        $menuTitleId279=$menuTitle279->id;
        $menuTitleId280=$menuTitle280->id;
        $menuTitleId281=$menuTitle281->id;
        $menuTitleId282=$menuTitle282->id;
        $menuTitleId283=$menuTitle283->id;

        //Get OutletDb from Outlet_name ("Toritos")
        $Outlet=Outlet::where('Outlet_name',"Tomatos")->first();
        //call OutletId from Outlet
        $OutletId14=$Outlet->id;
        //Get MenuTitle from title FOR Tomatos
        $menuTitle284 = MenuTitle::where('title','Global')->where('outlet_id',$OutletId14)->first();
        $menuTitle285 = MenuTitle::where('title','Pan Asian')->where('outlet_id',$OutletId14)->first();
        $menuTitle286 = MenuTitle::where('title','Indian')->where('outlet_id',$OutletId14)->first();
        $menuTitle287 = MenuTitle::where('title','Veg. Soups')->where('outlet_id',$OutletId14)->first();
        $menuTitle288 = MenuTitle::where('title','NonVeg. Soups')->where('outlet_id',$OutletId14)->first();
        $menuTitle289 = MenuTitle::where('title','Veg. Salads')->where('outlet_id',$OutletId14)->first();
        $menuTitle290 = MenuTitle::where('title','NonVeg. Salads')->where('outlet_id',$OutletId14)->first();
        $menuTitle291 = MenuTitle::where('title','Global Star Attractions')->where('outlet_id',$OutletId14)->first();
        $menuTitle292 = MenuTitle::where('title','Pan Asian Star Attractions')->where('outlet_id',$OutletId14)->first();
        $menuTitle293 = MenuTitle::where('title','All Stir Fry Combos')->where('outlet_id',$OutletId14)->first();
        $menuTitle294 = MenuTitle::where('title','Popular Chinese')->where('outlet_id',$OutletId14)->first();
        $menuTitle296 = MenuTitle::where('title','Attraction From The Indian Kitchen')->where('outlet_id',$OutletId14)->first();
        $menuTitle297 = MenuTitle::where('title','Indian Brads')->where('outlet_id',$OutletId14)->first();
        $menuTitle298 = MenuTitle::where('title','Accompaniments')->where('outlet_id',$OutletId14)->first();
        $menuTitle299 = MenuTitle::where('title','Pizzas (Veg.)')->where('outlet_id',$OutletId14)->first();
        $menuTitle300 = MenuTitle::where('title','Pizzas (Non Veg.)')->where('outlet_id',$OutletId14)->first();
        //call MenuTitleId form menutitle(for Tomatos)
        $menuTitleId284=$menuTitle284->id;
        $menuTitleId285=$menuTitle285->id;
        $menuTitleId286=$menuTitle286->id;
        $menuTitleId287=$menuTitle287->id;
        $menuTitleId288=$menuTitle288->id;
        $menuTitleId289=$menuTitle289->id;
        $menuTitleId290=$menuTitle290->id;
        $menuTitleId291=$menuTitle291->id;
        $menuTitleId292=$menuTitle292->id;
        $menuTitleId293=$menuTitle293->id;
        $menuTitleId294=$menuTitle294->id;
        $menuTitleId296=$menuTitle296->id;
        $menuTitleId297=$menuTitle297->id;
        $menuTitleId298=$menuTitle298->id;
        $menuTitleId299=$menuTitle299->id;
        $menuTitleId300=$menuTitle300->id;



        //Get OutletDb from Outlet_name ("Page One")
        $Outlet=Outlet::where('Outlet_name',"Page One")->first();
        //call OutletId from Outlet
        $OutletId15=$Outlet->id;
        //Get MenuTitle from title FOR Page One
        $menuTitle301 = MenuTitle::where('title','From The Soup Kettle')->where('outlet_id',$OutletId15)->first();
        $menuTitle302 = MenuTitle::where('title','Salads')->where('outlet_id',$OutletId15)->first();
        $menuTitle303 = MenuTitle::where('title','Starters/Continental')->where('outlet_id',$OutletId15)->first();
        $menuTitle304 = MenuTitle::where('title','Starters/Oriental Experience')->where('outlet_id',$OutletId15)->first();
        $menuTitle305 = MenuTitle::where('title','Starters/Tandoor Ke Salakhon  Se')->where('outlet_id',$OutletId15)->first();
        $menuTitle306 = MenuTitle::where('title','Sizzlers')->where('outlet_id',$OutletId15)->first();
        $menuTitle307 = MenuTitle::where('title','Pizza House')->where('outlet_id',$OutletId15)->first();
        $menuTitle308 = MenuTitle::where('title','Oven Baked')->where('outlet_id',$OutletId15)->first();
        $menuTitle309 = MenuTitle::where('title','Italian Pasta')->where('outlet_id',$OutletId15)->first();
        $menuTitle310 = MenuTitle::where('title','Main Course/Continental')->where('outlet_id',$OutletId15)->first();
        $menuTitle311 = MenuTitle::where('title','Main Course/Oriental Experience')->where('outlet_id',$OutletId15)->first();
        $menuTitle312 = MenuTitle::where('title','Main Course/Paneer Aap Ki Pasand')->where('outlet_id',$OutletId15)->first();
        $menuTitle313 = MenuTitle::where('title','Main Course/Sabjiyo Ka Mela')->where('outlet_id',$OutletId15)->first();
        $menuTitle314 = MenuTitle::where('title','Basmati Ke Noor')->where('outlet_id',$OutletId15)->first();
        $menuTitle315 = MenuTitle::where('title','Dal ki Peshkash')->where('outlet_id',$OutletId15)->first();
        $menuTitle316 = MenuTitle::where('title','Roti and Parathavali Gali')->where('outlet_id',$OutletId15)->first();
        $menuTitle317 = MenuTitle::where('title','Packed Lunch')->where('outlet_id',$OutletId15)->first();
        $menuTitle318 = MenuTitle::where('title','Executive Shahi Lunch')->where('outlet_id',$OutletId15)->first();
        $menuTitle319 = MenuTitle::where('title','Page One Lunch')->where('outlet_id',$OutletId15)->first();
       //call MenuTitleId form menutitle(for Page One)
        $menuTitleId301=$menuTitle301->id;
        $menuTitleId302=$menuTitle302->id;
        $menuTitleId303=$menuTitle303->id;
        $menuTitleId304=$menuTitle304->id;
        $menuTitleId305=$menuTitle305->id;
        $menuTitleId306=$menuTitle306->id;
        $menuTitleId307=$menuTitle307->id;
        $menuTitleId308=$menuTitle308->id;
        $menuTitleId309=$menuTitle309->id;
        $menuTitleId310=$menuTitle310->id;
        $menuTitleId311=$menuTitle311->id;
        $menuTitleId312=$menuTitle312->id;
        $menuTitleId313=$menuTitle313->id;
        $menuTitleId314=$menuTitle314->id;
        $menuTitleId315=$menuTitle315->id;
        $menuTitleId316=$menuTitle316->id;
        $menuTitleId317=$menuTitle317->id;
        $menuTitleId318=$menuTitle318->id;
        $menuTitleId319=$menuTitle319->id;


        //store Menus into Menustableseeder
        $Menus = array
        (
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Fresh Juice(Orange,Pineapple,Sweet lime)','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Jal Jeera Soda','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Fresh Lime Soda','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Mineral Water','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Aerated Water','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Butter Milk','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Sweet Lassi','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId,'item'=>'Dry fruit lassi','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'April Lady','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Aqua Marina','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Canberry lemonade','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Fruit Dizzy Blonde','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Fruit Punch','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Green Apple Mojito','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Jungle Masti','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Kiwi Margarita','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Orange Mogito','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId2,'item'=>'Vergin Mojito','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId3,'item'=>'Boondi Raita','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId3,'item'=>'Mix Raita','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId3,'item'=>'Pine Apple Raita','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId3,'item'=>'Plain Curd','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId3,'item'=>'Papad Roasted','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId3,'item'=>'Papad Fried','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId3,'item'=>'Masala Papad','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Chef Special Salad','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Cold Pasta Salad(Tomato basil Sauce)','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Fresh Cut Fruit Salad','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Green Salad','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Mexican Salad','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Pesto Paneer Caesar Salad','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Russian Salad','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Smoky Potato Salad','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Toasted Salad','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId4,'item'=>'Walldroff Salad','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId5,'item'=>'Chicken Cols-low salad','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId5,'item'=>'Chicken Salad','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId5,'item'=>'Mexican Chicken Salad','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId5,'item'=>'Spicy Chicken Salad','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Chicken Asian Green Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Chicken Fasitas Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Chicken Lemon Crispy ginger Rice Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Chicken Manchow Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Chicken Shangai Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Chicken Sweer Corn Corriender Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Peppery lemon Chicken Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Chicken Tortilla Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Hot & Sour Chicken Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId6,'item'=>'Thai Chicken Tom Yam Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Tomato Cinnamon Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Tomato Basil Parmesan Cheese Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Shangai Veg Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Peppery lemon Veg Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Mexican Tex Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Crispy Ginger Rice Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Brocolli Almond Feta cheese Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'Tomka Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId7,'item'=>'All Time Favourite Soup (Tomato,Minestrone,Manchow,Hot&Sour Soup,Sweet Corn Soup)','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Crispy Chicken/Korien Chicken','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Singapuri Chicken','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Crispy Chicken Salt "N" Pepper','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Japanese Smoke Chicken','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Zed Chicken','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Chicken Chilly/Chicken Lollypop','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Chicken Fusion Crispy Roll','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Schezwan Fish','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Chilly Fish(Dry)','price'=>'305','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Prawns Chilly(Dry)','price'=>'335','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Golden Fried Prawns','price'=>'335','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>'Lamb Schezwan Dry','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId8,'item'=>' Lamb Roast Chilly Dry','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId9,'item'=>'Chicken Chinese Sizzler','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId9,'item'=>'Dragon Chicken Sizzler','price'=>'410','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId9,'item'=>'Fish,Prawns Sizzler','price'=>'475','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId9,'item'=>'WOW Special','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Crispy Corn Salt & Pepper','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Paneer Chilli(Dry)','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Fujain Crispy Roll With Basil','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Crispy Chilli Babycorn','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Chrunchy Crispy Veg','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Veg Manchurian','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Sweet Chilli Poteto','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Koren Paneer','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId10,'item'=>'Chinese Bhell','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId11,'item'=>'Chinese Sizzler','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId11,'item'=>'Mexican Sizzler','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId11,'item'=>'Paneer Steak & Pine-Apple Sizzler','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId11,'item'=>'Vegetable Grilled Sizzler','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Wow Veg Platter','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Paneer Tikka','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Paneer Malai Tikka','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Paneer Reshmi','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Tandoori Soyabin','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Tandoori Mashroom','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Veg Seekh Kebab','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Hara Bhara Kebab','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Baby Corn Tandoori','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Baked Beans Tikki','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId12,'item'=>'Veg.Mansoori Kebab','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Wow Shahi Platter','price'=>'510','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Banzara Kebab','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Pahadi Kebab','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Sholey Kebab','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Methi Kebab','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Kasturi Kebab','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Lasuni Kebab','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Tikka','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Malai Tika','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Resmi Kebab','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Chsmol','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Kabuli Kebab','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Tangdi Mumtaz','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Tandoori Murgh Pahadi','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Tandoori Murgh','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Seekh Kebab','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Mutton Seekh Kebab','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Murgh Kolongi Kebab','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Tandoori Pompfret','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Fish Tikka','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Fish Malai Kebab','price'=>'355','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Fish Resmi Kebab','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Fish Pudina Kebab','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Parawns Tandoori','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Malaysian King Prawns','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Tandoori Khazana','price'=>'775','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId13,'item'=>'Nawabi Platter','price'=>'925','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Chicken Santomein Noodles','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Chicken Basil Gartic Noodles','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Wok Chicken Hakka Noodles','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Fish Pepper Noddles','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Prawns Chilly Noodles','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Prawns Oven Fried Rice','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Fish Lami Fried Rice','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Wok Chicken Fried Rice','price'=>'215','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Wok Chicken Tripple schezwan Fried Rice','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Chicken Nan-King Fried Rice','price'=>'235','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Wok Chicken Hunan Fried Rice','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Wok Chicken Tropical Fried Rice','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Egg Fried Rice','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId14,'item'=>'Chicken schezwan Fried Rice','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Santomein Noodles','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Wok Hakka Noodles','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Paad Thai Noodles','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Schezwan Fried Rice','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Hunan Fried Rice','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Veg Fried Rice','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Veg American Chopsy','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId15,'item'=>'Veg Manchurian Fried Rice','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Lababdar','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Tufani','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Khurchan','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Laziz','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Chingari','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Palak Paneer','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Tawa Masala','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Kadai','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Paneer Tikka Masala','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'WOW Sp. Veg.','price'=>'215','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Veg Sahi Korma','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Veg Jalfarezi','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Veg Handi/Veg Tufani','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Veg Kolhapuri','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Baby Com Mushroom Masala','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Cheese Begam Bahar','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Cheese Butter Masala','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Khoya Kaju/Kaju Kadai','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Dal Tadka','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId16,'item'=>'Dal Makhani / Dal Panchratni','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId17,'item'=>'Baked Maccaroni with P/A','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId17,'item'=>'Seasonal Vegetable in Sauce of your choice','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId17,'item'=>'Gobi Manchurian /Veg Manchurian','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId17,'item'=>'Exotic Veg Hot Garlic Sauce','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId17,'item'=>'Swwet “N” Sour Veg','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId17,'item'=>'Paneer Manchurian','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId17,'item'=>'Cottage Cheese in(Thai Green Curry&Red Curry)','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Butter Chicken','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Hazare','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Muglai','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Laziz','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Kadai','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Bhuna','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Murgh Mussalam','price'=>'390','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Kabuli','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Nazakat','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Sufiyani Chicken','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Tikka Masala','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Methi Garlic','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Lababdar / Chicken Bharta','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Tawa Chicken','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Murg Trikoti','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId18,'item'=>'Chicken Angara','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Bhuna / Mutton Korna','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Do Pyaza','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Charbag','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutto Muglai/Mutton Peshwari','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Rarra Mutton','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Rogan Josh','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Dal Ghost','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Saagwala','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Kasmiri Chaap','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Kheena','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Nihari / Mutton Afgani','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId19,'item'=>'Mutton Masala','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Fish Bhuna Shahi','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Fish Zaykedar','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Fish Hariyali','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Fish Garlic','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Fish Muglai','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Fish Curry','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Fish Masala','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Prawns Masala/Prawns lazziz','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Jhinga Gaon Curry','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId20,'item'=>'Egg Curry','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Devils Chicken','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Chicken Chilli','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Chicken Hongkong','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Chicken Manchurian','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Stir Fried Chicken in Chilli Parslaey Sauce','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Dice Chciken With Chinese Green','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Chicken in Chilli Soya Sauce','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Chicken in Garlic Sauce','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Papper Chicken','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Prawns Green Curry/Prawns Manchurian','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId21,'item'=>'Shrimp Red Curry','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId22,'item'=>'Banana Strawberry Shake','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId22,'item'=>'Chocolate Milk Shake','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId22,'item'=>'Strawberry Milk Shake','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId22,'item'=>'Vanilla Milk Shake','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId22,'item'=>'Cold Coffee','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId22,'item'=>'Cold Coffee with Ice-Cream','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'Chicken Dum Biryani','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'Mutton Chiman Biryani/Mutton Dum Biryani','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'Fish Biryani','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'Prawns Biryani','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'Chicken Pulao','price'=>'215','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'MuttonPulao/Chicken Hydrabadi Biryani','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'Chicken Special Biryani','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId23,'item'=>'Egg Biryani','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId2),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId24,'item'=>'Veg Dum Biryani/Veg Hyderabadi Biryani','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId24,'item'=>'Veg Chilman Biryani','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId24,'item'=>'Veg Pulao','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId24,'item'=>'Jeera Rice','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId24,'item'=>'Peas Pulao','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId24,'item'=>'Plain Rice','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId24,'item'=>'Navratna Pulao','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Roti (Plain)','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Roti (Butter)','price'=>'24','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Cheese Naan(Butter)','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Garlic Naan(Butter)','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Stuffed Naan(Butter)','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Naan (Plain)','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Naan (Butter)','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Paratha (Plain)','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Paratha (Butter)','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Stuffed Paratha(Butter)','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Khulcha (Plain)','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Khulcha (Butter)','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Hariyali Naan(Butter)','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Romali Roti (Plain)','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId,'menu_title_id'=>$menuTitleId25,'item'=>'Romali Roti (Butter)','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),


            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Orange Cooler ','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Fruit Punch/Virgin Colada','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Fresh Fruit Juice','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Fresh Lime Soda','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Fresh Lime Water','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Aerated Water','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Mineral Water','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Jal-Jeera Water','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Jal-Jeera Soda','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Chass(Masala or Plain)','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId26,'item'=>'Lassi(Sweet or Salted)','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Cream of Tomato Soup','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Cream of Veg./Mushroom Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Cheese Corn Tomato Soup','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Minestrone Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Sweet Corn Veg Soup','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Hot & Sour Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Veg. Wonton/Noodle Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Veg. Manchow Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Veg. Clear Soup','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId27,'item'=>'Jade Soup','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Tandoori Khazana','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Harabhara Kabab','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Raja Kabab','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Tandoori Aloo aur Gobi','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Kathi Kabab','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Paneer Tikka Dry','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Lasuni Paneer Tikka','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Ajwain Paneer Tikka','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Stuffed Paneer Tikka','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId28,'item'=>'Hariyali Pudina Tikka','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Veg. Manchurian Dry','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Veg. Spring Roll','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Veg. Fried Wontons','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Hunan Potato','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Veg. Mushroom Pepper Salt','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Crispy Veg. Szchewan Style','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Paneer Manchurian/Chilly Dry','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Baby Corn Chilly Dry','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Szchewan Paneer','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId29,'item'=>'Chinese Bhel Paneer','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId30,'item'=>'Paneer Hara Masala','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId30,'item'=>'Hariyani Lasuni Paneer','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId30,'item'=>'Paneer Mutter Haryali','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId30,'item'=>'Paneer Kofta Haryali','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId31,'item'=>'Paneer Tikka Lababdar','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId31,'item'=>'Paneer Amritsari','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId31,'item'=>'Paneer Butter Masala','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId31,'item'=>'Paneer Mushroom Makhani','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId31,'item'=>'Paneer Kalmi Masala','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId32,'item'=>'Paneer Pasanda','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId32,'item'=>'Paneer Kofta','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId32,'item'=>'Paneer Shahi Nawabi','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId32,'item'=>'Paneer Jungle Bhookh Spe.','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId33,'item'=>'Mutter Paneer','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId33,'item'=>'Belaspur Ka Dum Paneer','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId33,'item'=>'Paneer Handi/Paneer Kadai','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId33,'item'=>'Paneer Bhurji/Paneer Balti','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId33,'item'=>'Kalonji Paneer','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId34,'item'=>'Subz Hara Masala','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId34,'item'=>'Corn Palak','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId34,'item'=>'SUbz Pudina Bahar','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId34,'item'=>'Baby Corn Hara Masala','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId34,'item'=>'Hariyali Kofta','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId35,'item'=>'Kashmiri Dum Aloo','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId35,'item'=>'Subz Makhanwala/Kolhapuri','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId35,'item'=>'Subz Jaipuri/Subz Jalfrezi','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId35,'item'=>'Tomato Corn Bharta','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId35,'item'=>'Baby Corn Makhani','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId35,'item'=>'Veg. Toofani','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId35,'item'=>'Jungle Bhookh ki aag','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId1),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId36,'item'=>'Subz Kheema Hyderabadi','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId36,'item'=>'Methi Mutter Malai','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId36,'item'=>'Malai Kofta/Navratan Korma','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId36,'item'=>'Khoya Kaju','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId37,'item'=>'Aloo Mutter/Gobi','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId37,'item'=>'Chana Masala','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId37,'item'=>'Dum Aloo','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId37,'item'=>'Subz Kadai/Subz Handi','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId37,'item'=>'Subz balti/Subz Lasuni','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId37,'item'=>'Veg. Kofta','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId37,'item'=>'Subz Bawli','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId38,'item'=>'Dal Tadkawali','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId38,'item'=>'Dal Balti','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId38,'item'=>'Dal Lasuni','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId38,'item'=>'Dal Makhani','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId39,'item'=>'Safed Chawal','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId39,'item'=>'Jeera Rice','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId39,'item'=>'Pulao(Peas/Veg.)','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId39,'item'=>'Kashmiri Pulao','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId39,'item'=>'Veg. Biryani','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId39,'item'=>'Hyderabadi Biryani','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Fried Papad/Rosted Papad','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Masala Papad','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Plain Curd   ','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Green Salad','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Raita(Boondi/Mix Veg.)','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Raita(Pineapple)','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Cheese Cherry P/A Sticks','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId40,'item'=>'Russian Salad','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId41,'item'=>'Plain Roti','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId41,'item'=>'Butter Roti','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId41,'item'=>'Missi Roti','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId41,'item'=>'Roomali Roti','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId42,'item'=>'Plain Paratha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId42,'item'=>'Butter Paratha','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId42,'item'=>'Lachha Paratha','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId42,'item'=>'Pudina Paratha','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId42,'item'=>'Methi Paratha','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId42,'item'=>'Stuffed Paratha','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Plain Naan','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Butter Naan','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Stuffed Naan','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Garlic Naan','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Kasmiri Naan','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Cheese Naan','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Cheese Chilli Naan','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId43,'item'=>'Basket Naan','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId44,'item'=>'Plain Kulcha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId44,'item'=>'Butter Kulcha','price'=>'50','details'=>'175','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId44,'item'=>'Stuffed Kulcha','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Golden Baked Veg.','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Baked Spaghetti with P/A','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Baked Makroni with P/A','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Veg. Augratine','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Baked Chilly Corn','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Veg. Florentine','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Burmese Spaghetti','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId45,'item'=>'Cannelloni Florentine','price'=>'170  ','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId46,'item'=>'Schzewan Veg.','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId46,'item'=>'Sweet and Sour veg.','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId46,'item'=>'Mancurian(Cauliflower/Veg.)','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId46,'item'=>'Paneer Manchurian','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId46,'item'=>'Veg. Hongkong','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId46,'item'=>'Mix Veg. with Hot Garlic Sauce','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId47,'item'=>'Veg. Fried Rice','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId47,'item'=>'Fried Rice With Noodles','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId47,'item'=>'Schzewan Fried Rice','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId47,'item'=>'Manchurian with Fried Rice','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId48,'item'=>'Hakka Noodles','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId48,'item'=>'American Chopsuey','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId48,'item'=>'Chinese Chopsuey','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId48,'item'=>'Veg. Chowmein','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId48,'item'=>'Chilly Garlic Noodles','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Fruit Punch','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Pina-Coloda','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Blue Lagoon','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Litchi Limca','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Assorted Drinks','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Juice','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Strawberry Punch','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Pina-Coloda','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Blue Lagoon','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Litchi Limca','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Assorted Drinks','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Juice','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId49,'item'=>'Strawberry Punch','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId50,'item'=>'Gulab-Jamun','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId50,'item'=>'Kala Jamun','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId50,'item'=>'Mung DAl Halwa','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId50,'item'=>'Gajar Ka Halwa','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Sandwich Dhokla','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Paatra','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Lilva Ni Kachori','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Khandvi','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Khaman','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Mix Bhajiya','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Veg. Cutlets','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Banana Cutlets','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId51,'item'=>'Basket Chaat','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId52,'item'=>'Ras Malai','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId52,'item'=>'Angoori Basudi','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId52,'item'=>'Kesar Pista Basudi','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId52,'item'=>'Sitafal Basudi','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId52,'item'=>'Mango Pleasure','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId52,'item'=>'Mango Ras','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId1,'menu_title_id'=>$menuTitleId52,'item'=>'Laccha Rabdi','price'=>'','details'=>'','cuisine_type_id'=>$CuisinetypeId),

            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Hara Bhara Kabab','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Karara Kabab','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Panner Tikka Dry','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Aloo Chilly','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Tapri Paneer Chilly','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Veg. Lolli Pop','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Crispy Baby Corn','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Garlic Balls','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId53,'item'=>'Veg. Bread Stick','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Cream Of Tomato Soup','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Corn Tomato Cheese Soup','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Minestrone Soup','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Tomato Corn Veg. Soup','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Sweet Corn Veg. Soup','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Sweet Corn Cheese Soup','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Sweet Corn Plain Soup','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Veg. Noodle Soup','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Three Trager Soup','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Cream Of Veg. Soup','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Cream of Mushroom Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Veg. Clear Soup','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Hot & Sour Veg. Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Manchow Veg. Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'French Onion Veg. Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Lemon Coriander Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId54,'item'=>'Veg. Mushroom Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId55,'item'=>'Veg. Spring Roll','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId55,'item'=>'Cheese Veg. Spring Roll','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId55,'item'=>'Veg. Corn Spring ROll','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId55,'item'=>'Mushroom Spring Roll','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Garlic Noodles','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Hakka Noodles','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Manchurian Noodles','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Chilly Balls','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Cheese Balls','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Chow-Mein','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Chinese Bhel','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Fried Rice','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Mixed Fried Rice','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Szechwan  Fried Rice','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Mushroom Fried Rice  ','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Baby Corn Chilly(Dry & Gravy)','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Hot Garlic Sauce','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Mushroom Chilly(Dry & Gravy)','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Veg. Munchurian(Dry & Gravy)','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Paneer Munchurian','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId56,'item'=>'Paneer Chilly Dry','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId57,'item'=>'Macroni With Cheese','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId57,'item'=>'Spaghetti With Cheese','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId57,'item'=>'Vegetable With Cheese','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId57,'item'=>'Macroni With Pineapple','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId57,'item'=>'Spaghetti With Pineapple','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId57,'item'=>'Vegetable With Pineapple','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId58,'item'=>'Green Salad','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId58,'item'=>'Kachumber Salad','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId58,'item'=>'Tomato Salad','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId59,'item'=>'Roasted Papad','price'=>'18','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId59,'item'=>'Masala Papad','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId59,'item'=>'Fried Papad','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId60,'item'=>'Jeera Curd','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId60,'item'=>'Boondi Raita','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId60,'item'=>'Aloo Raita','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId60,'item'=>'Veg. Raita','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId60,'item'=>'Fruit Raita','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId60,'item'=>'Pineapple Raita','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Dal Fried(Yellow)','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Dal Tadka(Yellow)','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'DAl Makhani(Mix With Rajma)','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Aloo(Gobi/Mutter/Palak)','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Jeera Aloo','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Chana Masala','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Mix Vegetable','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Dum Aloo','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Bengan Bhartha','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Green Peas Curry','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Gobi Masala','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Bhindi Masala','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Shahi Korma Paneer','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Navratan Korma(Sweet)','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Mutter Tomato Cream','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Vegetable Makhanwala','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Vegetable JalFrezi','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Veg. Handi','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Veg. Hyderabadi','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Veg. Kolhapuri(with Paneer)','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Veg. Toofani','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Veg. Jaipuri','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Veg. Kadai','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Mutter Methi Malai','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Mehfil Special Veg.','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Khoya Kaju(Sweet)','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Kaju Curry','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Baby Corn Masala','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Mushroom Mutter Curry','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Mushroom Hara Masla','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Makai Shimla Palak','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Tomato Corn Bhartha','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Cheese Butter Masala','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Tawa Veg. Masala','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId61,'item'=>'Veg. Amritsari','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Butter Masal','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Tikka Masala','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Chips Masala','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Shahi Panner','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Kadai','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Chatpata','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Amritsari','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Pasanda','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Jwalamukhi','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Bhurji','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Kima Masala','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Kaju Masala','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Kaju Cury','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Tandoori Masala','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Mirch Masala','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Patiyala','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Spicy','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Handi','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Jodhpuri','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Tawa Paneer','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Mutter Paneer','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Palak Paneer','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Toofani','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId62,'item'=>'Paneer Lawabdar','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId63,'item'=>'Malai Kofta','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId63,'item'=>'Veg. Kofta','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId63,'item'=>'Kashmiri Kofta(Sweet)','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId63,'item'=>'Palak Kofta(Spicy)','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId63,'item'=>'Cheese Kofta','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId63,'item'=>'Jain Kofta','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Plain Rice','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Fried Rice','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Jeera Butter Rice','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Peas Pulao','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Veg. Pulao','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Kashmiri Pulao(Sweet)','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Cheese Pulao','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Mehfil Special Pulao','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Jain Kaju Pulao','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Veg. Biryani','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId64,'item'=>'Hyderabadi Biryani','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Plain Roti','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Butter Roti','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Misii Roti','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Makke Di Roti','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Plain Parotha','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Butter Parotha','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Soft Butter Parotha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Lachha Parotha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Stuff Parotha(Aloo/Gobi/Methi)','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Plain Kulcha','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Butter Kulcha','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Stuff Kulcha(Paneer/Garlic/Onion)','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Plain Naan','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Butter Naan','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Garlic/Stuff Naan','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Hariyali Naan','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Cheese Naan','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Cheese Masala Naan','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId65,'item'=>'Tandoori Basket','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Butter Milk','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Masala Butter Milk','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Punjabi Lassi Swwet/Salty','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Cold Drink','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Mineral Water','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Jal-Jeera Water','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Jal-Jeera Soda','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Fresh Lemon Water','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Fresh Lemon Soda','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId4),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId66,'item'=>'Cold Coffee','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Bread Butter','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Veg. Sandwich','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'French Fries','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Grill Veg. Sandwich','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Cheese Sandwich','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Grill Cheese Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Cheese Jam Sandwich','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Grill Cheese Jam Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId67,'item'=>'Club Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId68,'item'=>'Veg. Sabji,Paneer Sabji,Dal Fried/Jeera Butter Rice,Salad,Papad,Gulab Jamun(2pcs),3BUtter Roti(Thali)','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId69,'item'=>'Gulab Jamun(2pcs)','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId2,'menu_title_id'=>$menuTitleId69,'item'=>'RAsmalai(2pcs)','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),

            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId70,'item'=>'Fresh Juice(Orange,Pineapple,Sweet_Lime)','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId70,'item'=>'Jal Jeera Soda/Fresh Lime Soda','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId70,'item'=>'Jal Jeera Water/Fresh Lime Water','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId70,'item'=>'Mineral Water','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId70,'item'=>'Aerated Water','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId70,'item'=>'Butter Milk','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId70,'item'=>'Sweet Lassi','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Love Bite','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Italian Queen','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Virgin Pin a Colada','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Blue Lagoon','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Pack A Punch','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Conti Lemonade','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Virgin Marry','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Green Moito','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId71,'item'=>'Hich On The Peach','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId72,'item'=>'Cold Coffee','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId72,'item'=>'Cold Coffee with Ice Cream','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId72,'item'=>'Chocolate/Pineapple/Strawberry Surprise/Purple Sunbird','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId72,'item'=>'Ferroro Rocher Fantasy','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId73,'item'=>'Premium Ice Cream','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId73,'item'=>'Regular Ice Cream(Venila/Strawbery)','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId73,'item'=>'Vanila With Hot Chocolate Sauce','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId73,'item'=>'Double Sunday','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId73,'item'=>'Tripple Sunday','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId74,'item'=>'Bird On The Nest','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId74,'item'=>'Fresh Fruit Salad','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId74,'item'=>'Fruit Salad With Ice Cream','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId74,'item'=>'GulabJamun With Ice Cream','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId74,'item'=>'Choco-Rush','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId3),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Sweet Corn Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Hot & Sour Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Manchow Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Clear Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Tomka Soup','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Chicken Tortilla/Chicken Fajitas(non veg.)','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Pasta Fagoli Soup(Non Veg.)','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Shrimp Tomka(Non Veg.)','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Brocolu Cheese Soup','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Mexican Tortilla','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId75,'item'=>'Tomato','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Kebab Plater','price'=>'575','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Murgh Achari Tandoor','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Afgani Tandoor','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Tandoori Murgh','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Tangdi Chatpati','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Achari Tangdi','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Arabian Tandgi','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Murgh Nizami','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Murgh Tikka','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Lahori Tikkka','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Murgh Malai Tikka','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Murgh Seekh Kebab','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Lucknom Seekh Kebab','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Surkh Boti Kebab','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Fish Tikka','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Pompret Fish','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Panchratna Tikka','price'=>'620','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId76,'item'=>'Golden Platter','price'=>'725','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId77,'item'=>'Mile Jule Kebab(Platter)','price'=>'360','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId77,'item'=>'Paneer Pudina Tikka','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId77,'item'=>'Paneer Makmali Tikka','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId77,'item'=>'Achari Paneer Tikka','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId77,'item'=>'Hara Kebab','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId77,'item'=>'Raonaq-E-Sek','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId77,'item'=>'Tandoor Mushroom','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Chicken LollyPop','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Chicken Chilly','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Chicken Manchurian','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Chicken Wontons','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Crispy Chicken Oriental Sauce','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Crispy Chicken Chilly','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Chicken Spring Roll','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Chicken Golden Finger','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Spicy Chicken Arabita','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Grill Barbeque Chicken','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Lamb Schezwan Dry','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Lamb Roast Chilly Dry','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Pepper Fish/Fish Chilly','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Dragon Fish','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Prawns Chilyy/Golden Fried Prawns','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId78,'item'=>'Prawns Takoz','price'=>'330','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId79,'item'=>'Paneer Chilly Dry/Paneer','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId79,'item'=>'Babycorn Chilly Dry','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId79,'item'=>'Veg Fried Wonton','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId79,'item'=>'Veg Manchurian','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId79,'item'=>'Kumthod Vrispy Veg','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId79,'item'=>'Grill Cottage Cheese/Cottage Cheese Steak','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId79,'item'=>'Chrumbs Fried Cheese Finger','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId80,'item'=>'Chicken Grilled Sizzler','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId80,'item'=>'Chicken Sashlik Sizzler','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId80,'item'=>'Pleasure Special Sizzlers','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId80,'item'=>'Chicken Steak Cheese Spaghetti Sizzler','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId80,'item'=>'Bela Pasta Chicken Sizzler','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId80,'item'=>'Mexican Chicken Humpum','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId80,'item'=>'Chicken Bullet','price'=>'460','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId81,'item'=>'Mexican Sizzlers','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId81,'item'=>'Vegetable Grilled Sizzler','price'=>'360','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId81,'item'=>'Paneer Stick $ Pinnaple Sizzler','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId81,'item'=>'Chinese Sizzler','price'=>'360','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId82,'item'=>'Chicken Lasanga','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId82,'item'=>'Chicken Alfungi','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId82,'item'=>'Grilled Chicken Pasta','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId82,'item'=>'Cajun Pasta','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId82,'item'=>'Penne Sardi Pasta(Red/White Sauce)','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId83,'item'=>'Burmese Spageti','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId83,'item'=>'Baked Macroni With Pineapple','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Murg Mussalam','price'=>'390','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Murg Makhani','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Murgh Lazzat Avadhi','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Murgh Tikka Masala','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Murgh Handi Laziz','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Maysore Pepper Chicken','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Chicken Masala','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Tandoori Masala','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Chicken Tawa Masaledar','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Chicken Reshmi Masaledar','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Chicken Banjaran Masaledar','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Chicken Khyber','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Juliana Chicken','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Chicken Takatak','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Chicken Mumtaz','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Murgh Hari Mirch Ka Salan','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Murgh Hyderabadi','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId84,'item'=>'Pleasure Trove Special','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Nihari','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'BHuna Ghost','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Rogan Josh','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Lavani Kashmiri Chap','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Barah Boti Masala','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Kheema GHost','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Mutton Masala','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Dal Gosht','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Gosht Singapoori','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId85,'item'=>'Mutter Kheema','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId86,'item'=>'Machi Zaykedar','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId86,'item'=>'Machi Amritsari','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId86,'item'=>'Zinga Pardanasheen','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId86,'item'=>'Zinga Gaon Curry','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Chicken Garlic/Chicken Chilly','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Chicken Manchurian','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Pepper Chicken','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Chicken Hongkong','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Chicken Pataya Red Curry/Chicken Yellow Curry','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Schezwan Lamb','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Prawns Manchurian','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId87,'item'=>'Prawns Green Curry','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId88,'item'=>'Gobi Manchurian/Veg Manchurian','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId88,'item'=>'Veg Hot Garlic Sauce','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId88,'item'=>'Sweet and Sour Veg','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId88,'item'=>'Pataya Red Curry','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId88,'item'=>'Thai Cottage Cheese Green Curry','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Paneer Vajid Ali Shah','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Paneer Tikka Masala/Paneer BUtter Masala','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Lucknowi Paneer Pasanda','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Paneer Mirch Mahel','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Paneer Laziz','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Veg Tawa Masala','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Veg Jaipuri','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Khoya Kaju','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'Mix Veg','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId89,'item'=>'DAl Tadka/Dal Makhani','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Amercian Chopsuey','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Chicken Hakka Noodles','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Chciken Sautey Noodle','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Chicken Thai Noodles','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Prawns Thai Noodles','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Chicken Fried Rice','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Chicken Tripple Schezwan Fried Rice','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Chicken Babycorn Strongoff','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Salendo Chciken Fried Rice','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Mexican Chciekn Fried Rice','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Prawns Fried rice','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Fish Fried Rice','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Thai Prawns Fried Rice','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId90,'item'=>'Mixed Fried Rice','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId91,'item'=>'American Chopsueyy','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId91,'item'=>'Veg Hakka Noddles','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId91,'item'=>'Schezwan Noddle','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId91,'item'=>'Singapore Fried Rice','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId91,'item'=>'Veg Fried Rice','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId91,'item'=>'Veg Strong Off','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId92,'item'=>'Waldroff Salad','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId92,'item'=>'French Taco Salad','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId92,'item'=>'Olive Salad','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId92,'item'=>'Russain Salad','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId92,'item'=>'Green Salad','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId92,'item'=>'Chicken Grilled Salad','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId92,'item'=>'Fajita Salad','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId93,'item'=>'Mixveg/Boodi/Cucumber/Cool Raita','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId93,'item'=>'Pineapple/Fruit Kajoori Raita','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId93,'item'=>'PLain Curd','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId93,'item'=>'Papad Roasted','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId93,'item'=>'Papad Fried','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId93,'item'=>'Masala Papad','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Plain Roti','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Butter Roti','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Naan Roti','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Butter Roti','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Plain Paratha','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Butter Paratha','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Plain Kulcha','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Butter Kulcha','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Stuffed Paratha','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Plain Cheese Naan','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Butter Cheese Naan','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Plain Roomali Roti','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Butter Roomali Roti','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId94,'item'=>'Butter Roti Basket','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId95,'item'=>'Gosht Hyderabadi Biryani','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId95,'item'=>'Murg Hyderabadi Biryani','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId95,'item'=>'Lucknowi Gosht Parada Biryani','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId95,'item'=>'Lucknowi Chicken Parda Biryani','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId95,'item'=>'PT Special Dum Biryani','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId95,'item'=>'Chicken Lavan Pulav','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId96,'item'=>'Dum Biryani','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId96,'item'=>'Veg Biryani','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId96,'item'=>'Jeera Rice','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId3,'menu_title_id'=>$menuTitleId96,'item'=>'Steam Rice','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),


            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId97,'item'=>'Garlic Bread','price'=>'69','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId97,'item'=>'Cheese Garlic','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId97,'item'=>'Premium Bread','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId98,'item'=>'Veg. Hot Dog','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId98,'item'=>'Veg. Cheese Hot Dog','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId98,'item'=>'Veg. Burger','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId98,'item'=>'Veg. Cheese Burger','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId99,'item'=>'Sp.Bhel','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId99,'item'=>'Cheese Bhel','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId99,'item'=>'Sev Puri','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId99,'item'=>'Cheese Sev Puri','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId99,'item'=>'Monaco Topping','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Veg. Sandwich','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Veg. Sandwich','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Veg. Cheese Sandwich','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Veg. Cheese Sandwich','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Alu Mutter Sandwich','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Alu Mutter Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Alu+Veg. Mix Sandwich','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Alu+Veg. Mix Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Alu Mutter Cheese Sandwich','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Alu Mutter Cheese Sandwich','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Cheese Sandwich','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Cheese Sandwich','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Cheese Chatni Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Cheese Chatni Sandwich','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Cheese Jam Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Cheese Jam Sandwich','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Plain Alu+Veg. Mix Cheese Sandwich','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Grill Alu+Veg. Mix Cheese Sandwich','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId100,'item'=>'Sp. Chocolate Sandwich','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId101,'item'=>'Mexican Grill Sandwich','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId101,'item'=>'Sp. Tandoori Paneer Grill Sandwich','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId101,'item'=>'Garlic Bonanza Sandwich','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId102,'item'=>'Sp. Pineapple Sandwich','price'=>'109','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId102,'item'=>'Three in One Sandwich','price'=>'119','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId102,'item'=>'American Club Sandwich','price'=>'119','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId102,'item'=>'Chocolate Club Sandwich','price'=>'119','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId102,'item'=>'Shambhus sp. club sandwich','price'=>'119','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Margherita(reg.)','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Margherita(Med.)','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Masala Pizza(Reg.)','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Masala Pizza(Med.)','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Fresh Veggie(Reg.)','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Fresh Veggie(Med.)','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Mexican(Reg.)','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Mexican(Med.)','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Tandoori Paneer(Med.)','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Tandoori Paneer(Reg.)','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Shambhus Sp. Pizza(Reg.)','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId103,'item'=>'Shambhus Sp. Pizza(Med.)','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId104,'item'=>'Italian/Jain Pizza(Reg)','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId104,'item'=>'Italian/Jain Pizza(Med)','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId104,'item'=>'Cheese Bakes Pizza(Reg.)','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId104,'item'=>'Cheese Bakes Pizza(Med.)','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId104,'item'=>'Mexican Bakes Pizza(Reg.)','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId104,'item'=>'Mexican Bakes Pizza(Med.)','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'Sp. Cold Coffee(Shake)','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'Sp. Cold Coffee(Thick)','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'Coffee Choco Mix(Shake)','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'Coffee Choco Mix(Thick)','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'3 in 1 Coffee(Shake)','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'3 in 1 Coffee(Thick)','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'Coffee With Brownie(Thick)','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId105,'item'=>'Oreo Coffee','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId106,'item'=>'Frappe Coffee','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId106,'item'=>'Chocolate Frappe Coffee','price'=>'109','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId106,'item'=>'Deville Coffee','price'=>'119','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId107,'item'=>'Chocolate Shake','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId107,'item'=>'Chocolate Thick','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId107,'item'=>'Choco Brownie','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId107,'item'=>'Choco Chips','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId108,'item'=>'Coco','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId108,'item'=>'3 in 1 Coco','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId5),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Espresso Coffee','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Hot Chocolate','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Cafe Cappuccino','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Cafe Mocha','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Cafe Latte','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Irish Coffee','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Orange Coffee','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId109,'item'=>'Black Coffee','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId110,'item'=>'Fruit Punch','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId110,'item'=>'Pinacolada','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId111,'item'=>'Black Current','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId111,'item'=>'Orange','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId112,'item'=>'Lemon','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId112,'item'=>'Peach','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId113,'item'=>'Masala Tea','price'=>'39','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Butter Scotch Shake','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Butter Scotch Thick','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Strawberry Shake','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Strawberry Thick','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Rose Shake','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Rose Thick','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Thandai Shake','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Thandai Thick','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Litchi Shake','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Litchi Thick','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Black Current Shake','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId114,'item'=>'Black Current Thick','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId115,'item'=>'Sp.Sweet Lassi','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId115,'item'=>'Rose Lassi','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId115,'item'=>'Kesar Lassi','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId115,'item'=>'Chocolate Lassi','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId116,'item'=>'Blue Curacao','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId116,'item'=>'Green Curacao','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId116,'item'=>'Red Curacao','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId116,'item'=>'Black Curacao','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId117,'item'=>'Rose','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId117,'item'=>'Chocolate','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId117,'item'=>'Thandai','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId117,'item'=>'Strawberry','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId118,'item'=>'Mausambi','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId118,'item'=>'Pineapple','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId118,'item'=>'Orange','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId118,'item'=>'Water Melon','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId119,'item'=>'Cheese Toast','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId119,'item'=>'Cheese Chilli Toast','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId119,'item'=>'Cheese Garlic Toast','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId120,'item'=>'French Fries','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId120,'item'=>'Cheese French Fries','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId121,'item'=>'1sp. Cold Coffee & 1Veg. Sandwich','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId121,'item'=>'2Cappuccino Coffee & 1Cheese Chilly Toast','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId121,'item'=>'2Sp. Cold Coffee,1Mexican Grill Sandwich,1Italian Piza','price'=>'249','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId4,'menu_title_id'=>$menuTitleId121,'item'=>'3Sp.Cold Coffee,1Shambhus club Sandwich,1Italian Pizza,1French Fries,1Brownie With Ice Cream','price'=>'499','details'=>'','cuisine_type_id'=>$CuisinetypeId),


            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Espresso','price'=>'64','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Cappuccino','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Hazelnut Cappucciono','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Lrish Cappucciono','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Vanilla Cappucciono','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Cafe Latte','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Hazelnut Latte','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Lrish Latte','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Vanilla Latte','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Macchiato','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Lrish Coffee','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Cafe Americano','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId122,'item'=>'Cafe Mocha','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId123,'item'=>'Devils Own','price'=>'139','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId123,'item'=>'Iced Eskimo','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId123,'item'=>'Kaapi Nirvana','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId123,'item'=>'Tropical Lceberg','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId123,'item'=>'Vegan Shake','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId123,'item'=>'Cold Sparkle','price'=>'92','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId124,'item'=>'Aztec','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId124,'item'=>'Ethiopian','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId125,'item'=>'Cafe Frappe','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId125,'item'=>'Choco Frappe','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId125,'item'=>'Blushberry Frappe','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId125,'item'=>'Crunchy Frappe','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId125,'item'=>'Crunchy Vanilla Frappe','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId126,'item'=>'Chocolate Sauce','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId126,'item'=>'Ice Cream Scoop','price'=>'37','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId126,'item'=>'Whipped Cream','price'=>'37','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId127,'item'=>'Gourmet Hot Chocolate','price'=>'96','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId127,'item'=>'Cold Choco Latte','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId128,'item'=>'Green Tea','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId128,'item'=>'Darjeeling Tea','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId128,'item'=>'Assam Tea','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId128,'item'=>'Masala Chai','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId129,'item'=>'Mango Shake','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId129,'item'=>'Black Current Blast','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId129,'item'=>'Green Apple Soda','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId129,'item'=>'Cool Blue','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId130,'item'=>'Classic Lemonade','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId130,'item'=>'Kiwi Lemonade','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId130,'item'=>'Strawbery Lemonade','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId131,'item'=>'French Croissant','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId131,'item'=>'Creamy Choco Donut','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId131,'item'=>'Hot N Spicy Veg Puff','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId131,'item'=>'Chilli Cheese Toastizza','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId131,'item'=>'Veg Samosa','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId131,'item'=>'Cookies','price'=>'15','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId132,'item'=>'Cucumber Lemon','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId132,'item'=>'Pamegranate Lemon','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId133,'item'=>'Tandoori Paneer','price'=>'109','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId133,'item'=>'Spinach N Corn Cheese','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId133,'item'=>'Tex-Mex-Veg Cheese','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId133,'item'=>'Spicy Cheese','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId133,'item'=>' Hot Beverage and Veg Samosa','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId134,'item'=>'Cappuccino/Iced Coffee/Hot Tea + Chilli Cheese Toastizza/Creamy Choco Donut','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId134,'item'=>'Flavoures Cappuccino_Cafe Coffee Day Indulgence','price'=>'129','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId134,'item'=>' Cappuccino/Hot Tea+Veg Sandwich','price'=>'129','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId134,'item'=>'Cold Beverage+Veg Sandwich','price'=>'139','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId135,'item'=>'Choco Black Forest','price'=>'83','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId135,'item'=>'Nutty Fudge Brownie','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId135,'item'=>'Chocolate Fantasy','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId135,'item'=>'Cafe Coffee Day','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId136,'item'=>'Mango Shot','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId136,'item'=>'Belgian Choco Shot','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId137,'item'=>'Chocolate Sauce','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId138,'item'=>'Sizzle Dazzle Brownie','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId138,'item'=>'Fruity Blizz','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId138,'item'=>'Choc- Hola','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId138,'item'=>'Dark Passion','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId138,'item'=>'Vanilla Ice Cream','price'=>'67','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId138,'item'=>'Chocolate Ice Cream','price'=>'67','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId139,'item'=>'Choco Black Forest Cake','price'=>'614','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId139,'item'=>'Chocolate Fantasy Cake','price'=>'970','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId5,'menu_title_id'=>$menuTitleId139,'item'=>'Choco Indulgence Celebration Cake','price'=>'449','details'=>'','cuisine_type_id'=>$CuisinetypeId),


            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Aerated Drinks','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Fresh Lime Soda','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Fresh Fruit Juice','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Cold Coffee','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Cold Coffee With I/C','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Assorted Milk Shake','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Blue Lagoon','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Fruit Punch','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Pina Colada','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Fresh Lime Water','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId140,'item'=>'Bottled Water','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId141,'item'=>'Tomato Soup','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId141,'item'=>'Corn N Tomato Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId141,'item'=>'Sweet Corn Veg. Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId141,'item'=>'Hot N Sour Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId141,'item'=>'Manchow Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId141,'item'=>'Makai Ka Shorba','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId141,'item'=>'Tamatar Dhaniya Shorba','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId142,'item'=>'Baked Corn Chilly','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId142,'item'=>'Baked Macaroni','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId142,'item'=>'Veg. Augratin','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Paneer Tiranga Dry','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Paneer Tikka Dry','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Malai Paneer Tikka','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Lehshuni Paneer Tikka','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Hariyali Paneer Tikka','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Achari Paneer Tikka','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Gobhi Lehsuni','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Gobhi Tikka','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Aloo','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Kumbh Kabab','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Veg. Seekh Kabab','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Veg. Cheese Roll','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Cheese Paneer Kabab','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Harabhara Kabab','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId143,'item'=>'Tandoori Special Sizzler','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Afghani Bhurji','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Balti','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Tawa','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Kolhapuri','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Musallum','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Kadai','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Handi','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Chatpata','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Tikka Masala','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId145,'item'=>'Paneer Palak','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Saffron Special Vegetable','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Khoya Kaju','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Kaju Makhani','price'=>'189','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Navratan Korma','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Veg. Toofani','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Veg. Makhanwala','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Veg. Kadai','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Veg. Jaipuri','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId146,'item'=>'Veg Diwani Handi','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId147,'item'=>'Dum Aloo','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId147,'item'=>'Malai Kofta','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId147,'item'=>'Palak Kofta','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId147,'item'=>'Shahi Kofta','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Roti','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Butter Roti','price'=>'23','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Rumali Roti','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Paratha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Methi Paratha','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Kulcha','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Kulcha Onion','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Kulcha Cheese Chilly Garlic','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Naan','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Naan Garlic','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Naan Cheese','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Naan','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId148,'item'=>'Naan Cheese Chilly Garlic','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Steam Rice','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Jeera Rice','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Peas Pulao','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Banarasi Pulao','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Avadhi Dum Biryani','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Hyderabadi Biryani','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Nargisi Pulao','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId149,'item'=>'Saffron Special Pulao','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId150,'item'=>'Dal Fry','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId150,'item'=>'Dal Bukhara','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId150,'item'=>'Dal Amritsari','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId150,'item'=>'Dal Hariyali','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId150,'item'=>'Dal Tadka','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId151,'item'=>'Roasted Papad','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId151,'item'=>'Fried Papad','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId151,'item'=>'Masal Papad','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId151,'item'=>'Green Salad','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId151,'item'=>'French Fries','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId152,'item'=>'Butter Milk','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId152,'item'=>'Lassi','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId152,'item'=>'Veg. Raita','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId152,'item'=>'Boondi Raita','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId152,'item'=>'Pineapple Raita','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId153,'item'=>'Strawberry','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId153,'item'=>'Vanilla','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId153,'item'=>'Kesar Pista','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId153,'item'=>'Chocolate','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId153,'item'=>'Butter Scotch','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId6,'menu_title_id'=>$menuTitleId153,'item'=>'Sizzling Brownie with I/c','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),

            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Fresh Lime Soda','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Fresh Fruit Juice','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Rasam','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Tomato Soup','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Neermor/Chhass','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Cold Coffee','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Cold Coffee With IceCream','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Pina Colada','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Carribean Fruit Punch','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Blue Sea Surfer','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Lemon Lychee Fizz','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Icy Shakes','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Lassi','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Ginger Tea','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Mysore Filter Coffee','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Aerated Soft Drinks','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId154,'item'=>'Bottled Water','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Crispy Cheese Idli','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Chips N Chips','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Sevai Upma','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Telangana Aloo','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Masala Boondi','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Thayir Boondi','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Vegetable Upma','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Onion Thool Pakoras','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId155,'item'=>'Fried Idli','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId156,'item'=>'Steamed Idli','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId156,'item'=>'Idli lITTLES','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId156,'item'=>'Butter Idli','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId156,'item'=>'Thayir Idli','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId156,'item'=>'Idli Vada','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId156,'item'=>'Rasam Idli','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId157,'item'=>'Kanchipuram Idli','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId157,'item'=>'Masala Vegetable Idli','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId157,'item'=>'Cocktail Rice Cakes','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId157,'item'=>'Achari Rice Cakes','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId157,'item'=>'Chettinad Rice Cakes','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId157,'item'=>'Nilgiri Rice Cakes','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId157,'item'=>'Vagahr Idli','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId158,'item'=>'Medu Vada','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId158,'item'=>'Rasam Vada','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId158,'item'=>'Thayir Vada','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId158,'item'=>'Onion Vada','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId159,'item'=>'Golden Crisp','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId159,'item'=>'Onion Dosa','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId159,'item'=>'Super Paper Dosa','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId159,'item'=>'Mysore Chatpata Dosa','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId159,'item'=>'Nilgiri Special Dosa','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId159,'item'=>'Add Potato Bhaji In Dosa','price'=>'15','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Sankalp Special 4 Ft. Long Dosa','price'=>'800','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Cheese Dosa','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Spring Dosa','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Cheesey Spring Dosa','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Special Indian Bhaji Dosa','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Paneer Dosa','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Cheese Corn Dosa','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Military Ghee Roast Dosa','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Chettinad Spicy Dosa','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Keerai Cheese Garlic Dosa','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Capsicum Chilly Garlic Dosa','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Achari Onion Dosa','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Kara Mura Dosa','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Three Barrel Dosa','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId160,'item'=>'Dosa Platter','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId161,'item'=>'Kanchipuram Achari Rava','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId161,'item'=>'Onion Rava','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId161,'item'=>'Udipi Rava','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId161,'item'=>'Coconut Rava','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId161,'item'=>'Crisp N Crunchy Rava','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId161,'item'=>'Capsicum Rava','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId161,'item'=>'Add Potato BHaji in Dosa','price'=>'15','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Double Roast-Plain','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Double Roast-Topping','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Cheese Chilli Uthappa','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Cheese Madurai Sandwich Uthappa','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Chennai Pizza Uthappa','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Keerai Uthappa','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Madurai Sandwich Uthappa','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Panchavarana Uthappa','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Special Tomato Masala Uthappa','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Special Tomato Uthappa','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId162,'item'=>'Tomato Corn Uthappa','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Malabari Paneer','price'=>'189','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Nilgiri Kurma','price'=>'189','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Kadalai Kurma','price'=>'189','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Karai Paneer','price'=>'189','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Chetting Kurma','price'=>'189','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Vegetable Kurma','price'=>'189','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Aloo Mutter','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId163,'item'=>'Jeera Aloo','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId164,'item'=>'Cheese Pulao','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId164,'item'=>'Bisi Bela Huli Anna','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId164,'item'=>'Kaikari Biryani','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId164,'item'=>'Vegetable Pulao','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId164,'item'=>'Bagala Bhath','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId164,'item'=>'Steamed Rice','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId165,'item'=>'Kesary','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Poori','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Tawa Chapati','price'=>'15','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Malabari Porotta','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Set Dosa','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Appalam','price'=>'25','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Poppodum','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Masala Papad','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Butter','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Cheese','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Pachadi','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId166,'item'=>'Salad','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId167,'item'=>'Sizzling Brownie','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId167,'item'=>'Nutty Hot Fudge Sundae','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId167,'item'=>'Tri Color Jamaican Sundae','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId167,'item'=>'Swiss Peak Tall Sundae','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId167,'item'=>'Cassata','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId7,'menu_title_id'=>$menuTitleId167,'item'=>'Assorted Ice-Cream','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),

            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Karari roomali','price'=>'149','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Paneer Chilli bean','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Paneer Pudina Tikka','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Paneer banjara','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Assorted havmor platter','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Crispy Cottage Cheese','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Crispy Chilli Waterchestnuts','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Nachos With Cheese Sauce','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId168,'item'=>'Crispy Vegetable Pepper Salt','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Mojito','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Tang Sling','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Cranberry Cooler','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Diet Mojito','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Coke Float','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Aerated Water','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Mineral Water','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Fresh Lemon Water','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Fresh Lemon Soda','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Masala Chaas','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Mawa Lassi','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Lassi','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId169,'item'=>'Coffee','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId170,'item'=>'Regular(Vanila/chocolate)','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId170,'item'=>'Fresh Fruit(strawberry/pineapple/seasonal)','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId170,'item'=>'Exotic(Kesar Pista/Kaju Anjir)','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId170,'item'=>'Sugar Free(Chocolate/Strawberry)','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId170,'item'=>'Cold Coffee','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Cheesy Cigar rolla','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Tapri Chilli Paneer','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Cheese Jalapeno Poppers','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Crispy Pesto Paneer','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Mushroom Aranchini','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Bruschetta with Mozzarella','price'=>'215','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Havmor thin crust pizza','price'=>'249','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId171,'item'=>'Hara Kabab','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId172,'item'=>'Chaats(samosa/papdi/sev-puri)','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId172,'item'=>'Spinach and Mushroom Wrap','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId172,'item'=>'Corn and Mushroom Wrap','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId173,'item'=>'Moroccan salad','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId173,'item'=>'Waldorf Salad','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId173,'item'=>'Green Salad','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId174,'item'=>'Garlic Toast','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId174,'item'=>'Plain Raita','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId174,'item'=>'Raita','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId174,'item'=>'Papad','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId174,'item'=>'Masala Papad','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Khowsuey','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Ginger Lentil Broth','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Broccoli garlic Clear Soup','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Pesto Spiked Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Corn Tomato Cheese Soup','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Sweet Corn Veg. Soup','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Hot & Sour/Manchow Soup','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Mix Veg. Chimney Soup','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Minestrone Soup','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId175,'item'=>'Cream of Tomato Soup','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId176,'item'=>'Vegetable Kiev Sizzler','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId176,'item'=>'Oriental Sizzler','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId176,'item'=>'Avadhi Sizzler','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId176,'item'=>'Chinese Blast','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId176,'item'=>'Sizzling Italiano','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId176,'item'=>'Grilled Cottage Cheese Steak','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId177,'item'=>'Stuffed Ravioli In Neapolitan Sauce','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId177,'item'=>'Stuffed Ravioli with bell pepper and broccoli in pesto cream cheese sauce','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId177,'item'=>'Spaghetti','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId177,'item'=>'Baked Macaroni/Vgetable','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId177,'item'=>'Spaghetti','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId177,'item'=>'Penne with Vegetables','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId177,'item'=>'Baked Lasagna','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Chana Masala','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Lasooni Palak Makai aur Khumb','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Subz tawa masal','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Khoya kaju/kaju curry','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Subz bahar','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Aloo aap ki Pasand','price'=>'179','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Aloo Chutneywale','price'=>'179','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Havemor taka tak','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Navratna Korma','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Mushroom Mutter Masala','price'=>'235','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Subz Dahiwala','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Ralli Malli Subzi','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Dal Tadkewali','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId178,'item'=>'Dal Makhani','price'=>'235','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Hyderabadi parda biryani','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Avadhi Dum Biryani','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Subzi Biryani','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Pulao','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Sada Chawal','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Puri','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Tandoori tokri','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Naan','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Masala Cheese nann','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Garlic Naan/Hariyali Naan','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Paratha-Lachhedar/Pudina Paratha','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Butter Naan/Butter Paratha/BUtter Kulcha','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Roomali Roti','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Missi Roti','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Stuffed Kulcha','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Butter Roti','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Roti','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId179,'item'=>'Multigrain Paratha','price'=>'79','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId180,'item'=>'Cantonese spring rolls','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId180,'item'=>'Sichuan Chilli Paneer','price'=>'265','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId180,'item'=>'Veg Manchurian-dry/gravy','price'=>'235','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId180,'item'=>'Exotic Vegetable thai red curry/green curry','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId180,'item'=>'Stir Fried Vegetables','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId180,'item'=>'Vegetable Stew Thai Style','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId181,'item'=>'Szechwan noodles','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId181,'item'=>'Hakka noodles','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId181,'item'=>'Szechwan Rice','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId181,'item'=>'Vegetable fried rice','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId181,'item'=>'Jeera Fried Rice','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId181,'item'=>'Burnt GInger Fried Rice','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId181,'item'=>'Ginger Steamed Rice','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Chocolava','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Tutti Frutti','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Sizzling Brownie Volcano','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Hot Fudge Nut Sundae','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Cookie Crunch','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Triple Sundae','price'=>'215','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Tall Beauty','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Crunchy Munchy Sundae','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Royal Crunch','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Fruit Fusion','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Dry Fruit Khazana','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Coffee Toffee Sundae with Brownie','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Choco Dip','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Vanilla with hot chocolate sauce','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId182,'item'=>'Gulab Jamun','price'=>'89','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId183,'item'=>'Regular','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId183,'item'=>'Fresh Fruit Flavours','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId183,'item'=>'Deluxe','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId183,'item'=>'Exotica','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId183,'item'=>'Supreme','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId8,'menu_title_id'=>$menuTitleId183,'item'=>'Suger Free','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),


            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId184,'item'=>'BBQ Cottage Cheese','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId184,'item'=>'Jamaican Jerk Baby Veggies','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId184,'item'=>'Assortment in Tikka Marinade','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId184,'item'=>'Satay With Peanut Sauce','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId184,'item'=>'Patato Yakitori','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId185,'item'=>'Grilled Summer Vegetables With Harissa Marinade','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId185,'item'=>'Harabara Kebab','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId185,'item'=>'Haryali Paneer Ka Tikka','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId185,'item'=>'Paneer Ka Tikka','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId185,'item'=>'Kasuri Aloo','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId185,'item'=>'Achari Gobi','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId185,'item'=>'Kebab Platter','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId186,'item'=>'Sizzling Chilli Paneer','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId186,'item'=>'Grilled Veg Sizzler','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId186,'item'=>'Stuffed Cottage Cheese','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Corn Kebabs with Wasabi Butter','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Grilled Mushrooms with Herb Cheese','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Mixed Veggie Grill Chaat','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Teriyaki Tofu Triangles','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Panner Pesto','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Buddhas Delight','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Paneer Tawa Masala','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Pickled Cottage Cheese','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Veggie Mongolian Style','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'House Special','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId187,'item'=>'Root Vegetable Ras Al Hanout','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Hot N Sour Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Sweet Corn Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Khun Tom Pumpkin Soup','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Glass Noodle Salad','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Cucumber Salad With Roasted Cashews','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Curried Salad With Roasted Cashews','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Sweet $ Sour Salad','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Fritters','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Stir Fired Vegetables with Cashew','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Vegetable with Sweet & Sour Sauce','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Forest Curry','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Yellow Curry With Broccoli Carrots','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Carrots','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Khao Suey','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Crispy Vegetables','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Vegetable Stir Fried Noodles','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Spinach Noodles','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Cantonese Noodles','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Phad Thai','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Chow Mein','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Chop Suey','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'House Vegetable Fried Rice','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Ginger Fried Rice With Cilantro','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Curry Pineapple Fried Rice','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId188,'item'=>'Lemon Fried Rice','price'=>'255','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId189,'item'=>'Tamatar Ka Shorba','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId189,'item'=>'Dal Ka Soup','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId189,'item'=>'Palak Ka Soup','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Roasted Papad','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Fried Papad','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Masala Papad','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Salad','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Curd','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Sev Ka Raita','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Aloo Ka Raita','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Cucumber Ka Raita','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Tandoori Roti','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Butter Roti','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Plain Naan','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Butter Naan','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Cheese Naan','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Lachha Paratha','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Plain Roomali','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Butter Roomali','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Kulcha','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Butter Kulcha','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Steamed Rice','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Jeera Rice','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Bhopali Matar Gajar Pulao','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Hyderabadi Kabuli Biryani','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId190,'item'=>'Veg. Pulao','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Paneer Makhani Dilliwala','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Paneer Tikka Masala','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Paneer Akbari','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Kadai Paneer','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Khurmani ka Bukhara Kofta','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Mirchi Ka Salan','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Mughlai Saag','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Aloo Matar Ki Curry','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Veg. Jalfreezi','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Subz Miloni','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Masaledar Baingan','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Shalgam Ka Bharta','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Khotti Dal','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Dal Maharani','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId191,'item'=>'Dal Tadka','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Minestrone Milanese Soup','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Cream of Tomato Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Cream of Broccoli Soup','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Mexican Soup','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Potato Salad','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Caesar Salad','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Falafel With Tzatziki','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Eggplant Hummus & Sour Cream Tahini With Pita','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Enchiladas','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Nachos With Salsa Cheese','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Spaghetti Arrablata','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Pasta Pesto','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Pasta With Tomato & Basil','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Pasta Creole','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Cheesy Spaghetti Supreme Pasta','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Stuffed Cottage Cheese','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Cauliflower and Potato au Gratin','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Vegetable Pot Pie,Grape Sauce With Couscous Crust','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Cabbage Dolma','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Grilled Eggplant in Tomato','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Beans Charmoula','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId192,'item'=>'Papa Chorreadas','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Tiramisu','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Panna Cotta With Orange Choco Cream','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Chocolate Mousse','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Dessert Crepe With Berry Compote,Vanilla Ice Cream','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Belgian Waffle With Almond Cream and Coffee','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Sizzling Chocolate Brownie with Vanilla','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'American Banana Split','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Vanilla Hot Chocolate Fudge','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId193,'item'=>'Selections From Baskin & Robins','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Virgin Mojito','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Electric Lemonade','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Black Currant','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Kiwi Jumbo','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Lemonade on the rocks','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Cranberry Pucker','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Prohibition Punch','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Honeymoon','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Fire and Ice','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Singapore Sling','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Thai Collins','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Cosmopolitan','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Mock Champagne','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'Transfusion','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId194,'item'=>'River Cruise','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId195,'item'=>'Fresh Lime Soda','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId195,'item'=>'Butter Milk','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId195,'item'=>'Lassi','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId195,'item'=>'Aerated Water','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId195,'item'=>'Energy Drink','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId195,'item'=>'Mineral Water','price'=>'47','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId196,'item'=>'Banana/Strawberry/Vanilla/Chikoo/Mango','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId197,'item'=>'Only Fresh Juices are Served Subject to availability','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId198,'item'=>'Black Tea','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId198,'item'=>'Garden Organic Teas','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId198,'item'=>'Green Flavoured Tea','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId198,'item'=>'Oriental','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId198,'item'=>'Cutting Chai','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId198,'item'=>'Suleiman','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId198,'item'=>'Iced Tea','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'Espresso','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'Cappuccino','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'Mocha','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'Latte','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'American','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'Madras Filter','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'Cold Coffee','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId199,'item'=>'Ice Espresso','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId200,'item'=>'Hot Chocolate','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId200,'item'=>'Coco-Shake','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId9,'menu_title_id'=>$menuTitleId200,'item'=>'Mocha Shake','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),


            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Jaljeera','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Jaljeera with Soda','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Fresh Lime Water','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Fresh LIme Soda','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Choice of Fresh Juice','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Cold Coffee','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Cold Coffee with Ice Cream','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Butter Milk','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Masala Butter Milk','price'=>'38','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Lassi','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Aerated Drinks','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId201,'item'=>'Mineral Water','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Fruit Punch','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Pinacolada','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Pink Lady','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Strawberry Punch','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Blue Lagoon','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Orange Blossom','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Green Godess','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Caribbean Fantasy','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId202,'item'=>'Eve Teaser','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Cream of Tomato','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Corn Cheese Tomato','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Tomato Basil','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Minestrone','price'=>'92','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Chilly Beans','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Broccoli & Almond','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Broccoli & Bell Pepper','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Malaysian Green','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'French Onion','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Lemon Coriander','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Veg. Clear','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Veg. Wonton','price'=>'99','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Veg. Sweet Corn','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Veg. Hot N Sour','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Veg. Manchow','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId203,'item'=>'Choice of Cream Soup','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Hara Bhara Kebab','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Raja Kebab','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Corn Tikki','price'=>'148','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Veg. Lollipop','price'=>'138','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Veg. Sesame Finger','price'=>'138','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Veg. Wonton Dry','price'=>'152','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Veg. Manchurian Dry','price'=>'152','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Chinese Cigar','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Veg. Spring Roll','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Chinese Bhel','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Crispy Veg.','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Potato Chilly Dry','price'=>'138','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Paneer Chilly Dry','price'=>'168','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Gobi/Baby Corn Chilli Fry','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId204,'item'=>'Szechwan Paneer Chilli Dry','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId205,'item'=>'Paneer Tikka Dry','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId205,'item'=>'Paneer Hariyali Tikka','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId205,'item'=>'Paneer Adraki Tikka','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId205,'item'=>'Paneer Lasaniya Tikka','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId205,'item'=>'Mix Tandoori Platter','price'=>'220','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Green Salad','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Juliene Salad','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Kachumber Salad','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Coleslaw Salad','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Russian Salad','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Corn Potato Salad','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Woldroff Salad','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Paneer Pineapple Salad','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId206,'item'=>'Mix Fruit Salad','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId207,'item'=>'Raita','price'=>'72','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId207,'item'=>'Curd','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId207,'item'=>'Roasted Papad','price'=>'20','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId207,'item'=>'Fried Papad','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId207,'item'=>'Masala Papad','price'=>'34','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId207,'item'=>'Cheese Masal Papad','price'=>'48','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId208,'item'=>'Baked Cheese Macaroni Pineapple','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId208,'item'=>'Veg. Au-gratine','price'=>'168','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId208,'item'=>'Baked Spaghetti','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId208,'item'=>'Baked Chilly Corn','price'=>'168','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId208,'item'=>'Veg. Lasagnia','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId208,'item'=>'Veg. Cannelloni','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId208,'item'=>'Veg. Florentine','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId209,'item'=>'Fussilli Alfredo','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId209,'item'=>'Penne Pasta','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId209,'item'=>'Pasta Mista','price'=>'199','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId210,'item'=>'Mexican Trio','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId210,'item'=>'Enchillidas with Corn','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId210,'item'=>'Enchillidas with Cheese','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId210,'item'=>'Nachos','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId210,'item'=>'Taccos','price'=>'162','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Dinner Bell Special Veg.','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Kolhapuri','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Makhanwala','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Jalfrezie','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Kadai','price'=>'158','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Jaipuri','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Khada Masala','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Garlic Masala','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Kheema Masala','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Nawabi','price'=>'158','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Baby Corn Butter Masala','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Baby Corn Mushroom Masala','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Baby Corn Khada Masala','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Shahi Korma','price'=>'158','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Palak Corn Masala','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Diwani Handi','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Cheese Hariyali Masala','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Dum Aloo Kashmiri','price'=>'153','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Dum Aloo Punjabi','price'=>'153','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Jeera Aloo','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Aloo Mutter','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Aloo Palak','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Aloo Gobi','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Mix Veg. Curry','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Chana Masala','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Tomato Corn Bharta','price'=>'158','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Amritsari Chhole','price'=>'149','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Veg. Tufani','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Cheese Butter Masala','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Cheese Angoori','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Khoya Kalu','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Mushroom Methi Malai','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Navratan Korma','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Methi Mutter Malai','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Methi Mutter Masala','price'=>'158','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Kaju Butter Masala','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId211,'item'=>'Kaju Kadai Masala','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Sp. Dinner Bell Paneer','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Tikka Masala','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Tikka Lababdar','price'=>'177','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Butter Masala','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Amrutsari','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Akbari','price'=>'174','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Balti Paneer','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Pasanda','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Kadai/Handi','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Bhurjee','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Khada Masala','price'=>'174','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Garlic Masala','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Mutter Paneer','price'=>'158','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Shahi Korma','price'=>'172','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId212,'item'=>'Paneer Palak','price'=>'162','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId213,'item'=>'Malai Kofta','price'=>'152','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId213,'item'=>'Cheese Kofta','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId213,'item'=>'Veg. Kofta Curry','price'=>'152','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId213,'item'=>'Nargisi Kofta','price'=>'152','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId214,'item'=>'Yellow Dal Fry','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId214,'item'=>'Yellow Dal Tadka','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId214,'item'=>'Dal Lasooni','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId214,'item'=>'Dal Makhani','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId214,'item'=>'Dal Bukhara','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId214,'item'=>'Dal Palak','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId215,'item'=>'Steam Rice','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId215,'item'=>'Jeera Rice','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId215,'item'=>'Peas Pulao','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId215,'item'=>'Veg. Pulao','price'=>'121','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId215,'item'=>'Kashmiri Pulao','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId215,'item'=>'Veg. Biryani','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId215,'item'=>'Veg. Hyderabadi Biriyani','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId216,'item'=>'Plain Tawa Roti','price'=>'15','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId216,'item'=>'Butter Tawa Roti','price'=>'18','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId217,'item'=>'Plain Roti','price'=>'22','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId217,'item'=>'Butter Roti','price'=>'24','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId217,'item'=>'Missi Roti','price'=>'30','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId217,'item'=>'Roomali Roti','price'=>'44','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId218,'item'=>'Plain Naan','price'=>'38','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId218,'item'=>'Butter Naan','price'=>'42','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId218,'item'=>'Garlic Naan','price'=>'57','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId218,'item'=>'Cheese Naan','price'=>'68','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId218,'item'=>'Stuffed Naan','price'=>'56','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId218,'item'=>'Masala Cheese Naan','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId219,'item'=>'Plain Kulcha','price'=>'38','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId219,'item'=>'Butter Kulcha','price'=>'42','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId219,'item'=>'Stuffed Kulcha','price'=>'56','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId219,'item'=>'Onion Kulcha','price'=>'52','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId220,'item'=>'Plain Paratha','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId220,'item'=>'Butter Paratha','price'=>'37','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId220,'item'=>'Lachhedar Paratha','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId220,'item'=>'Methi Paratha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId220,'item'=>'Stuffed Paratha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId220,'item'=>'Assorted Roti Basket','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId221,'item'=>'Butter Jam','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId221,'item'=>'Bread Butter Toast','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId221,'item'=>'Bread Butter','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId221,'item'=>'Veg. Sandwich','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId221,'item'=>'Veg. Club','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId221,'item'=>'Cheese Sandwich','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId221,'item'=>'Veg. Cheese','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId222,'item'=>'Itallian Pizza','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId222,'item'=>'Choice of Your Pizza(Mexican,Margeritta,Sp. Dinner Bell Pizza)','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId223,'item'=>'Gulab Jamun','price'=>'48','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId223,'item'=>'Choice of Flavoured Ice Cream','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId223,'item'=>'Kaju Draksh Ice Cream','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId223,'item'=>'Kesar Pista Ice Cream','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId223,'item'=>'Cassata Cut','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId223,'item'=>'Fruit Salad With Ice Cream','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId223,'item'=>'Vanilla with Hot Chocolate Sauce','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId224,'item'=>'Kaju Anjeer','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId224,'item'=>'Butter Scotch','price'=>'105','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId224,'item'=>'Flavoured Milk Shake','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId225,'item'=>'Executive Power Lunch(unlimited)[Soup,Starter,Salad,Veg.Subji,Paneer Subji,Dal,Rice,Roti-Naan,Papad,Butter Milk,Ice Cream] NOTE:BUTTER MILK,ICE CREAM & STARTER LIMITED','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId226,'item'=>'With Baked Dish Power Lunch','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId227,'item'=>'Pack Lunch','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId228,'item'=>'Fix Lunch','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId10,'menu_title_id'=>$menuTitleId229,'item'=>'Deluxe Lunch','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),

            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Garlic Toast','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'3 Pepper Potatoes','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Tomato Bruschetta','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Herb Potato Wedges served with Dipping Sauce','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Crispy Fried Vegetables','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Cheesy Jalapeno Poppers','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Nachos With Cheese Sauce and Salsa','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Tangy Paneer Bites','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Paneer Chilly Dry','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Masala Paneer Bites','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Cheesy/Herbed Garlic Crostini','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Jalapeno Olive Garlic Crostini','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId230,'item'=>'Herbed Mushrooms','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Chicken Cheesy Jalapeno','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Chicken Kheema Garlic Crostini','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Chilly Chicken Garlic Crostini','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Chilly Garlic Chicken Fingers','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'3 Pepper Chicken','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Chicken Chilly Dry','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Crispy Fried Chicken','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Chicken Lollipops','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Herbed Chicken','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Tangy Chicken Bited','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Masala Chicken Bites','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId231,'item'=>'Tomato Chicken Bruschetta','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId232,'item'=>'Aerated Water','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId232,'item'=>'Mineral Water','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId232,'item'=>'Fresh Lime Soda','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId232,'item'=>'Cold Coffee','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId232,'item'=>'Cold Coffee with Ice Cream','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId232,'item'=>'Iced Tea','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId232,'item'=>'Coolers','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Cream of Tomato','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Cream of Veg','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Minestrone','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Sweet Corn Veg./Mushroom','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Hot & Sour','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Veg. Manchow','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Roasted Garlic Veg','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Leamon Coriander','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Veg. Clear Soup','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId233,'item'=>'Cheddar Cheese Soup','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId234,'item'=>'Tomato Egg Drop','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId234,'item'=>'Cream of Chicken','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId234,'item'=>'Chicken Manchow','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId234,'item'=>'Chicken Clear','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId234,'item'=>'Hot & Sour','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId234,'item'=>'Roasted Garlic Chicken','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId235,'item'=>'Vegetable Frankie','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId235,'item'=>'Bombay Frankie','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId235,'item'=>'Paneer Frankie','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId236,'item'=>'Egg.& Veg. Frankie','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId236,'item'=>'Chicken Frankie','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId236,'item'=>'Paneer Kathi Kebab','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId236,'item'=>'Chicken Kathi Kebab','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId236,'item'=>'Mutton Kheema Frankie','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId236,'item'=>'Mutton Kheema Kathi Kebab','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId237,'item'=>'Vegetable and Cheese Grilled','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId237,'item'=>'3Tier Veg. Club','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId237,'item'=>'3Tier Cheese Jungli Club','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId237,'item'=>'3Tier BBQ Paneer Club','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId238,'item'=>'Chicken & Tomato Grilled','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId238,'item'=>'Cicken Jungli','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId238,'item'=>'Fiery Chicken Grilled','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId238,'item'=>'3Tier Non-Veg.Club','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId238,'item'=>'3Tier BBQ Chicken Club','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Baked Macaroni','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Veg. Au-Gratin','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Veg. Florentine','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Burmese Spaghetti','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Indian Shadow','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Cheesy Mashed Poattoes','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Pot Purani','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Veg Moussaka','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Baked Pasta','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId239,'item'=>'Upper Crust Special','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId240,'item'=>'Margherita','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId240,'item'=>'Neapolitan','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId240,'item'=>'No Mercy','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId240,'item'=>'All-4-One','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId240,'item'=>'Stone Heart','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId240,'item'=>'Peppy Paneer','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId241,'item'=>'Chicken & Cheese','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId241,'item'=>'The Devils Den','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId241,'item'=>'Chicken No Mercy','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId241,'item'=>'Brave Heart','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId241,'item'=>'Noahs Ark','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId242,'item'=>'French Fries','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId242,'item'=>'Masala French Fries','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId242,'item'=>'Dynamite Fresh Fries','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId242,'item'=>'Cheesy French Fries','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId242,'item'=>'Schezwan French Fries','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId243,'item'=>'Mustad Pepper Potatoes','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId243,'item'=>'Veggies in Teriyaki Sauce','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId243,'item'=>'Cottage-Cheese Peri Peri','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId243,'item'=>'Chilli Garlic Cottage Cheese','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId243,'item'=>'Smoked Cottage Cheese','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId244,'item'=>'Veg. Tandoori Masala','price'=>'230','details'=>'230','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId244,'item'=>'Veg. Pahadi','price'=>'230','details'=>'230','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId244,'item'=>'Veg. Sheekh Kebab','price'=>'230','details'=>'230','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId244,'item'=>'Tandoori Aloo','price'=>'230','details'=>'230','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId244,'item'=>'Paneer Tikka','price'=>'280','details'=>'280','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId244,'item'=>'Tandoori Mushroom','price'=>'300','details'=>'300','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId244,'item'=>'Cheesy Paneer Tikka','price'=>'330','details'=>'330','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId245,'item'=>'Mustard Pepper Chicken','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId245,'item'=>'Garlic Pepper Chicken','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId245,'item'=>'Chilli Garlic Chiken','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId245,'item'=>'Smoked Chiken','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId245,'item'=>'Teriyaki Chicken','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId245,'item'=>'Peri Peri Chicken','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId246,'item'=>'Tandoori Chicken/Murg Pahadi(Half/Full)','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId246,'item'=>'Chicken Tikka(Hara/Aachari/Lasuni)','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId246,'item'=>'Chicken Malai Tikka','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId246,'item'=>'Chicken Reshmi Kebab','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId246,'item'=>'Chicekn Tangdi Kebab','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId246,'item'=>'Chicken Shikari Tangdi Kebab','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId246,'item'=>'Mutton Sheekh Kebab','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Baked Chicken','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Chicken Burmese Spaghetti','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Cheesy Chicken Mashed Potatoes','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Chicken Pot Pourri','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Simply Indian','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Spaghetti Bolognese','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Tangy Chicken','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Moussaka','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Baked Pasta Chicken','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId247,'item'=>'Uppercrust Special','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId250,'item'=>'Pasta in Arrabiata Sauce','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId250,'item'=>'Pasta in Cheese Sauce','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId250,'item'=>'Pasta in Basil Pesto Sauce','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId250,'item'=>'Pasta in Alfredo Sauce','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId250,'item'=>'Pasta in Creamy Spinach Sauce','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId250,'item'=>'Past in 2 Sauces','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId250,'item'=>'Aglio e Olio','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId251,'item'=>'Add Chicken to Your Favorite Veg. Pasta Extra Chicken','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId252,'item'=>'Veg. Fried Rice','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId252,'item'=>'Veg. Hakka Noodles','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId252,'item'=>'Veg. Manchurian','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId252,'item'=>'Schezwan Style Veg.(Spicy)','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId252,'item'=>'Mixed Veg in hot garlic','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId252,'item'=>'Paneer Chilly With Gravy','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId252,'item'=>'Schezwan Paneer Chilly','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Egg. Fried Rice','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Chicken Fried Rice','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Chicken Hakka Noodles','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Chicken Manchurian','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Schezwan Style Chicken(Spicy)','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Chicken in hot Garlic','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Chicken Chilly with Gravy','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId253,'item'=>'Schezwan Chicekn Chilly(Spicy)','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId254,'item'=>'Thai Red Curry','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId254,'item'=>'Thai Green Curry','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId255,'item'=>'Add Chicekn to Your Veg Cury','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Mix Veg','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Mamma Mia','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Cottage Steak','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Cottage Steak-II','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Paneer Shashlik','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Jane Fonda Special','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Chak De India','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Code Red','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Mexican Roots','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Jewel of China','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Dragon On Fire','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Blade Runner','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Cheesy Italian Sizzler','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId256,'item'=>'Burn It Down','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Big moose','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Chef Recommends','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Brown Sahib','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Played on Pepper','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Chicken Shashlik','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Code Red Chicken Sizzler','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Mexican Spice Sizzler','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Raja Hindustani','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Creamy Garlic Chicken Steak','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Jackie Chan Favorite','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Bruce Lee Special','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Double Cross','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId257,'item'=>'Cheesy Chicken Italian Sizzler','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId258,'item'=>'Olive Veg. Steak','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId258,'item'=>'BBQ Paneer Steak','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId258,'item'=>'Fire House','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId258,'item'=>'Cheesy Oregano Veg. Steak','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId259,'item'=>'Olive Chicken Steak','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId259,'item'=>'BBQ chicken Steak','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId259,'item'=>'Blue Smoke','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId259,'item'=>'Cheesy Oregano Chicken Steak','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'The Avengers','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'Inception','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'Street Warrior','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'Burmese Khowsuey','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'Revolution','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'Ravioli','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'Anti Gravity','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId260,'item'=>'Fast and Furious','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Malai Kofta','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Dum Aloo','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Veg. Hyderbadi','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Veg. Tak a Tak','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Subzi Handi','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Aloo Mutter','price'=>'230','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Mushroom Masala','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Paneer Butter Masala','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Paneer Butter Masala','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Palak Paneer','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Paneer Tikka Masala','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Paneer Handi','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Paneer Tak a Tak','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId261,'item'=>'Paneer Hayderbadi','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Egg Curry','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Masala Chicken Curry','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Chicken Kadhai','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Chicken Tak a Tak','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Chicken Hyderbadi','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Murgh Handi','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Chicken Tikka Masala','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Butter Chicken','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Mutton Rara','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Mutton Khada Masala','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Mutton Handi','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'Mutton Hara Masala','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId11,'menu_title_id'=>$menuTitleId262,'item'=>'CUC Special Mutton Kheema','price'=>'400','details'=>'','cuisine_type_id'=>$CuisinetypeId),

            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Salads and Continental Buffet(monday to saturday)','price'=>'380','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Salads and Continental Buffet(Sundays and Public Holidays)','price'=>'430','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Salads and Continental Buffet(Childran Upto 5years)','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Choice of Soup','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Choice of Salad','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Garlic Tost','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Baked or Continental From','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Dessert of the day','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Coconut Water','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Masala Buttermilk','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Fresh LimeSoda','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Mineral Water','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Diet Coke','price'=>'60','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Hot Vegetable Sandwich','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'French Fries With Zippy Cheese Sauce','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Nachos with Zippy Cheese Sauce','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Pizza Margharita','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Pizza Farmhouse','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Pasta in Creamy Cheese Sauce','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId12,'menu_title_id'=>$menuTitleId263,'item'=>'Pasta arabiata','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),



            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Cheese Pull up Bread','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Grilled Stuffed Mushroom with Parsley Crust','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Arabian Dip Sampler','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Grande Anti Pasti Platter','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Traditional Bruschetta','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Fusion Bruschetta','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Simply Nachos','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Grande Nachos','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Stuffed Crostini','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId264,'item'=>'Signature Tacos','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Boca Mocha','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Blueberry Lemonade','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'New Cocoon','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Litchi Lake','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Awesome Twosome','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Chocolate Shakers','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Purple Sling','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Spicy Guava','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Mango Julius','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Traditional Mojito','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Apple Fever','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId265,'item'=>'Cranberry Cooler','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Mexican Toritos','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Tomato Basil','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Minestrone','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Greek Lentil','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Italian Clear','price'=>'115','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Frensh Onion','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Creamy Cilantro','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId266,'item'=>'Creamy Tomato Spinach','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Ravioli','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Authentic Cannelloni','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Chestnut & Celery Risotto','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Red Pepper & Sundried Tomato Risotto','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Fusion Spaghetti','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Spaghetti Bolognese','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Pasata Creation','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Thin Potato Skins','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Cheese Garlic Bread','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Garlic Bread','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Chilli Pepper and Cheese Bites','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Spicy French Fries','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Salsa French Fries','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'French Fries','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Press Potatoes','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Empanadas','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Mexican Crostini','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId267,'item'=>'Fusion Nachos','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId268,'item'=>'Potatoes Bravas','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId268,'item'=>'Plaited Bread','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId268,'item'=>'Italian Mushrooms','price'=>'240','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId268,'item'=>'Crispy Ravioli','price'=>'180','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId269,'item'=>'Cool and Crisp Salad','price'=>'165','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId269,'item'=>'California Salad','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId269,'item'=>'Taco Salad','price'=>'175','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId269,'item'=>'Tropical Cream Salad','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId269,'item'=>'Exotic Green Salad','price'=>'155','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Egyptian Cottage Cheese Steak','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Chimichurri Cottage Cheese Steak','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Manicotti Rounds','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Maifatti','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Spring Vegetable Tagine','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Paneer Arabiatta','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Thai Green Curry','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Moroccan Kofta','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId270,'item'=>'Traditional Legume Pesto','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Cottage Cheese Tchoupatoulis','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Ranchero Veg Enchiladas','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Quesadillas','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Vegetable Stroganoff','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Texas Casserole','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Fajitas','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Authentic Enchiladas','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Mexican Chimichanga','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId271,'item'=>'Mexican Poblano','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Margherita','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Crisp and Lite Pizza','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Veggie Attack','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Athena','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Toritos Special','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Farm and Forest Pizza','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Mexican Pizza','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Sicilian','price'=>'290','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId272,'item'=>'Fungi Fromage','price'=>'295','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId273,'item'=>'French Style Fondue','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId273,'item'=>'Innovative Fondue','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId273,'item'=>'Potato Cheese Rosti','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId273,'item'=>'Traditional Potato Rosti','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId274,'item'=>'Baked Lasagne','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId274,'item'=>'Vegetable Gratin','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId274,'item'=>'Mac and Cheese','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId274,'item'=>'Baked Potato Pesto','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId275,'item'=>'Humus Beiruty','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId275,'item'=>'Humus Turki','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId275,'item'=>'Turkish Patican','price'=>'195','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId275,'item'=>'Falafel Wrap','price'=>'215','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId276,'item'=>'Mineral Water','price'=>'40','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId276,'item'=>'Aerated Water','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId276,'item'=>'Fresh Lime Soda','price'=>'80','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId276,'item'=>'Ice Tea','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId276,'item'=>'Red Bull','price'=>'145','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Baklava','price'=>'190','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Chocolate Lava','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Chocolate Fondue','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Chocolate Walnut Brownie','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Chocolate Pie','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Apple Pie','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Ice Cream Sundae','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId277,'item'=>'Exotic Seasonal Ice Cream','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId278,'item'=>'Coffee Frost','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId278,'item'=>'Cold Coffee','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId278,'item'=>'Oreo Shake','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId278,'item'=>'Brownie Shake','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId278,'item'=>'Orange Cold Coffee','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId278,'item'=>'Hazelnut Alomond Coffee','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId278,'item'=>'Iced Bournvita','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId279,'item'=>'Pasta Creation','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId280,'item'=>'Cappuccino','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId280,'item'=>'Cafe Americano','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId280,'item'=>'Espresso Shot','price'=>'70','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId280,'item'=>'Cafe Latte','price'=>'95','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId281,'item'=>'Hazelnut Hot Chocolate','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId281,'item'=>'Caramel Macchiato','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId281,'item'=>'Cafe Mocha','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId281,'item'=>'Cina Coffee','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId281,'item'=>'Bornvita','price'=>'75','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId282,'item'=>'Masala Chai','price'=>'50','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId282,'item'=>'Green Tea','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Grilled Focaccia','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Portobello Pesto','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'3Layered Club Sandwich','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Pita Pizza','price'=>'140','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Loaded Vegetarian Burger','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Paneer Arabiatta','price'=>'160','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Cheese Chlly Toast','price'=>'90','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'French Style Fondue','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Exotic Maggie','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId13,'menu_title_id'=>$menuTitleId283,'item'=>'Cheese Vegetable Maggie','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),


            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Cocktail Tacos','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Nachos With Salsa And Cheese Herb Sauce','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Nachos Supreme','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Pizza Strips Supreme','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Zucchini Wrapped Grilled Paneer','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Mexican Tortas','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Ultimate Potato Skins','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Cheese Herb Jalapeno Dumplings','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Ultimate Grilled Cottage Cheese Olivia','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId284,'item'=>'Chilli Con Queso','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId285,'item'=>'Cocktail Corn Cheese Spring Rolls','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId285,'item'=>'Crunchy Schezwan Potato Fingers','price'=>'245','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId285,'item'=>'Chilli Paneer','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId285,'item'=>'Crispy Vegetables in Plum Sauce','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId286,'item'=>'Mulayam Paneer Tikka','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId286,'item'=>'Assorted Paneer Tikka','price'=>'395','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId286,'item'=>'Corn Cheese Banjara Kerab','price'=>'315','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId286,'item'=>'Hara Cheese Kebab','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId286,'item'=>'Bharwan Tandoori Aloo','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId286,'item'=>'Barbeque Khazana','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId287,'item'=>'Classic Tomato Soup','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId287,'item'=>'House Special Tangy Sour and Pepper Soup','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId287,'item'=>'Tom Yum Veg Soup','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId287,'item'=>'Mexican Lime and Corn Soup','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId287,'item'=>'Manchow Soup','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId287,'item'=>'Hot n Sour Soup','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId287,'item'=>'Crispy GArlic Spinach Clear Soup','price'=>'185','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId288,'item'=>'House Special Tangy Sour and Pepper Chicken Soup','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId288,'item'=>'Tom Yum Chicken Soup','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId288,'item'=>'Chicken Manchow Soup','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId288,'item'=>'Hot N Sour Chicken Soup','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId288,'item'=>'Crispy Garlic Spinach AND Chicken Clear Soup','price'=>'205','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId289,'item'=>'Garden Salad','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId289,'item'=>'Mexicano Salad','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId289,'item'=>'Caesar Salad','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId289,'item'=>'Waldorf Salad','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId290,'item'=>'Chicken and Pasta Salad','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId290,'item'=>'Chicken Caesar Salad','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Veg Chilaquiles ','price'=>'410','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'The Ever Popular Baby Corn Stroganoff','price'=>'410','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Grilled Exotic Veggies and Cottage Cheese in Bhell Pepper Sauce','price'=>'410','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Cheese Souffle','price'=>'425','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'The Ultimate Garden Skillet','price'=>'410','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Combination Cannelloni','price'=>'410','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Mexican Hot Pot','price'=>'460','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Choice Of Pasta in Choice of Your Sauce','price'=>'435','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Cheese Herb Garlic Bread','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Herb Garlic Bread','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Chicken Parmigianna','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Mexican Style Orange Chicken','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Chicken Enchiladas Classico','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'The Ever Populer Chicken Stroganoff','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Ultimate Mon-Veg. Skillet','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Chicken San Diego','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Mexican Non Veg Pot Rice','price'=>'495','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Chicken Garlic Pepper Steak','price'=>'490','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Good Old Fish N Chips','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Grilled Lemon Pepper Chicken Breast','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Choice of Pasta in Choice of your Sauce','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId291,'item'=>'Pan Grilled Fish in Your Choice of Sauces','price'=>'530','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId292,'item'=>'Exotic Veggies in Thai Sweet Chilli Sauce','price'=>'415','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId292,'item'=>'Khow Suey Veg','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId292,'item'=>'Veg Thai Green or Red Curry With Rice','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId294,'item'=>'Fried Rice','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId294,'item'=>'Noodles','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId294,'item'=>'Veg Manchurian','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId294,'item'=>'American Chopsuey','price'=>'345','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId294,'item'=>'Stir Fried Vegetables and Cottage Cheese with Roast Bell Pepper Noodles','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId293,'item'=>'Rice/Noodles in Sauces of Your Choice','price'=>'415','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Murgh Makhani','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Murgh Mirch Masala','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Chicken Tikka Methi Garlic Masala','price'=>'440','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Murgh Kalmi Curry','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Gosht Bemisaal','price'=>'480','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Fish/Prawns Curry','price'=>'470','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Murgh Biryani','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Gosht Biryani','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId296,'item'=>'Egg Lababdar','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Roti','price'=>'36','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Butter Roti','price'=>'44','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Pudhina Roti','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Missi Roti','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Paratha','price'=>'72','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Naan','price'=>'72','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Kulcha','price'=>'72','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Garlic Naan','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Hara Masala Naan','price'=>'86','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Onion Kulcha','price'=>'92','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Cheese Naan','price'=>'158','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Cheese Masala Naan','price'=>'170','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId297,'item'=>'Assorted Roti Basket','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId298,'item'=>'Veg Raita','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId298,'item'=>'Roasted Papad','price'=>'32','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId298,'item'=>'Fried Papad','price'=>'38','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId298,'item'=>'Masala Papad','price'=>'49','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId299,'item'=>'Pizza Supreme','price'=>'380','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId299,'item'=>'Pizza Neopolitan','price'=>'270','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId299,'item'=>'Pizza Margarita','price'=>'260','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId299,'item'=>'Taco Piza','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId299,'item'=>'BBQ Paneer Pizza','price'=>'380','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId299,'item'=>'Chargrilled Vegetable Pizza','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId299,'item'=>'Roasted Garlic And Bell Pepper Pizza','price'=>'370','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId300,'item'=>'Grilled Chicken Pizza','price'=>'420','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId14,'menu_title_id'=>$menuTitleId300,'item'=>'Barbeque Chicken Pizza','price'=>'420','details'=>'','cuisine_type_id'=>$CuisinetypeId),

            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'The Page One Special Soup','price'=>'130','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Cream Soup Of Your Choice','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Mexican Chilli Bean Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Minestrone Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Broccoli and Almond Soup','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Steamed Wonton Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Oriental Tomato Soup','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Tomato Soup of your Choice','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Velvet Corn Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Lemon Corriander Soup','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Subz Yakhani Shorba','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Szechwan Hot N Sour Soup/Manchow/Talumien','price'=>'125','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId301,'item'=>'Thai Coconut Soup','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId302,'item'=>'Garden Green Salad','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId302,'item'=>'Sprouted Tex Mex Garbanzo Bean Salad','price'=>'150','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId302,'item'=>'Insalata Caprese','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId302,'item'=>'Greek Salad','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId302,'item'=>'Mesclun Salad','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId302,'item'=>'Ceaser Salad','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Swiss Cheesy Fondue Station','price'=>'365','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Spinach Cheese Ball','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Lebanese Platter','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Quesedillas','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Tex Mex Tacos','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Bruschetta','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Jalapeno Mozzarella Crispies','price'=>'280','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId303,'item'=>'Baked Nachos','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Exotic Veg and Cream Cheese Sushi','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Stir Fried Szechwan Paneer','price'=>'340','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Exotic Vegetables','price'=>'340','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Mongolian Stir Fry','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Chilli Cheese Garlic Balls','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Potato Cheese Nest','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Jalapeno Cheese Pouches','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Crackling Spinach And Crunchy Potatoes Szechwan','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Chilly Wanton','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Vietnamese Spring Rolls','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Salt N Pepper Chilly Corn Karne','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Steamed Dimsums','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Ginger Blasted Porcini Mashrooms','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId304,'item'=>'Mashroom Fusion Delight','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Rajputana Paneer Ke Sooley','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Paneer Tikka Patiala Shahi','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Paneer Tikka','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Erani Gullar Kebab','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Bread Roll Kebabi Crisp','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Shammi Kebab','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Page One Kebabs','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Aloo Dalnaz','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Subzahary Hari Bhari Gilouti','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Bikanry Papad Potly','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Bharwan Tandoori Khumb','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId305,'item'=>'Subzahary Kebabi Khazana','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Sizzler Vegiterranean','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Brochettes Pizzaiola','price'=>'330','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Flaming Vegetable Cordon Bleu','price'=>'330','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Sizzling Pasta','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Indian Gate To China','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Hot Plate From China Town','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Sizzler Thai Chi','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId306,'item'=>'Sizzler Page One Indiana','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId307,'item'=>'Siciliana(Italian Thin Crust Pizza Topped With Your Choice)','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId307,'item'=>'Mediterranea(Italian Thin Crust Pizza Topped With Your Choice)','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId307,'item'=>'Ortolana(Italian Thin Crust Pizza Topped With Your Choice)','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId307,'item'=>'Flama(Italian Thin Crust Pizza Topped With Your Choice)','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId307,'item'=>'Paneer Tikka(Italian Thin Crust Pizza Topped With Your Choice)','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId307,'item'=>'Hawarian(Italian Thin Crust Pizza Topped With Your Choice)','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId307,'item'=>'Margheritta(Italian Thin Crust Pizza Topped With Your Choice)','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId308,'item'=>'Lasagne','price'=>'320','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId308,'item'=>'Mexican Trio','price'=>'310','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId308,'item'=>'Vegetable Augratine','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId308,'item'=>'Baked Duo Cheeselings','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId308,'item'=>'Baked Creole Vegetable','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId308,'item'=>'Baked Macroni With Pineapple','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId309,'item'=>'Pasta Cooked to Perfection','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId309,'item'=>'Ravioli','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Crepe Continental','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Cheese Aubergine Timble','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Stuffed Courgettes Served with Saffron Sauce','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Saffron Sauce','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Enchilada','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Fajitas','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Queso De Vera Cruse','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Mexican Rice','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Chimi Changa','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId310,'item'=>'Foil Baked Rice','price'=>'375','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Burmese Khausuay With Jasmine Bamboo Rice','price'=>'450','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Beling Style Tofu','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Stir Fried Vegetables Served With Steamed Rice','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Thai Curries','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Jewel Of The East','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Tofu/Mixed Vegy Fried Rice','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Veg Nasi Goreng','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Pan Seared Noodles','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Burnt Chilli Garlic Noodles','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Vegetable Hakka Noodles','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId311,'item'=>'Pad Thai Je','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId312,'item'=>'Afghani Paneer Masala','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId312,'item'=>'Paneer Begum Bahar','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId312,'item'=>'Paneer Dum Pukht','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId312,'item'=>'Paneer Bhurji','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId312,'item'=>'Tawa Paneer','price'=>'335','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId312,'item'=>'Khurchan Panner Tawa Masala','price'=>'335','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId312,'item'=>'Kachchi Mirch Aur Paneer Ka Salan','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Subz Vilayati','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Subz Tawa Takatak','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Khoya Kaju','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Subz Shahi Korma','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Subz Hyderabadi','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Subz Khada Masala','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Subz Makhanwala','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Khumb Makai Hara Pyaz','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Tandoori Malai Gobi Peshawari','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Bharwan Dum Kay Aloo','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Motila Saag','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Bharwan Bhindi Dum Masala','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId313,'item'=>'Amritsari Choley','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId314,'item'=>'Nawabi Biryani','price'=>'325','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId314,'item'=>'Subj Dum Biryani','price'=>'300','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId314,'item'=>'Masala Pulao','price'=>'275','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId314,'item'=>'Kashimiri Pulao','price'=>'285','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId314,'item'=>'Pulao','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId314,'item'=>'Khuska','price'=>'225','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId314,'item'=>'Steamed Rice','price'=>'200','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId315,'item'=>'Dal Pageone','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId315,'item'=>'Dal Mast Zayka','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId315,'item'=>'Sanganery Panchmel Dal','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId315,'item'=>'Dal Zeerali','price'=>'210','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Masala Cheese Naan','price'=>'135','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Bharwan Masala Kulcha','price'=>'100','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Paneer Stuffed Paratha','price'=>'110','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Kerala Paratha','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Stuffed Paratha','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Ceylon Paratha','price'=>'65','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Roomali Roti','price'=>'85','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Naan/Kulcha','price'=>'55','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Lachha Paratha','price'=>'45','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId316,'item'=>'Tandoori Roti','price'=>'35','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId317,'item'=>'Packed Lunch','price'=>'120','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId318,'item'=>'Executive Shahi Lunch','price'=>'250','details'=>'','cuisine_type_id'=>$CuisinetypeId),
            array('outlet_id'=>$OutletId15,'menu_title_id'=>$menuTitleId319,'item'=>'Page One Lunch','price'=>'350','details'=>'','cuisine_type_id'=>$CuisinetypeId),


        );



        DB::table('menus')->insert($Menus);
    }

}







