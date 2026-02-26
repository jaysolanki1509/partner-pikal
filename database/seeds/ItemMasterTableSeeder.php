<?php

use Illuminate\Database\Seeder;


class ItemMasterTableSeeder extends Seeder {

    public function run()
    {
        DB::table('item_master')->delete();

        $catagories = array(

            // Grocery


            array('id' => '1','catagory_id' => '1','item_name'=>'Idli Rice','display_order'=> '1','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '2','catagory_id' => '1','item_name'=>'Udad','display_order'=> '2','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '3','catagory_id' => '1','item_name'=>'Raw Rice','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '4','catagory_id' => '1','item_name'=>'Boiled Rice','display_order'=> '4','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '5','catagory_id' => '1','item_name'=>'Peanut','display_order'=> '5','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '6','catagory_id' => '1','item_name'=>'Tuver Dal','display_order'=> '6','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '7','catagory_id' => '1','item_name'=>'Red Big Mirch','display_order'=> '7','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '8','catagory_id' => '1','item_name'=>'Red Regular Mirch','display_order'=> '8','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '9','catagory_id' => '1','item_name'=>'Coriander','display_order'=> '9','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '10','catagory_id' => '1','item_name'=>'Rai','display_order'=> '10','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '11','catagory_id' => '1','item_name'=>'Jeera','display_order'=> '11','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '12','catagory_id' => '1','item_name'=>'Kali Miri','display_order'=> '12','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '13','catagory_id' => '1','item_name'=>'Methi Dana','display_order'=> '13','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '14','catagory_id' => '1','item_name'=>'Hing','display_order'=> '14','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '15','catagory_id' => '1','item_name'=>'Chana Dal','display_order'=> '15','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '16','catagory_id' => '1','item_name'=>'Haldi','display_order'=> '16','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '17','catagory_id' => '1','item_name'=>'Salt','display_order'=> '17','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '18','catagory_id' => '1','item_name'=>'Red Mirchi Powder','display_order'=> '18','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '19','catagory_id' => '1','item_name'=>'Garam Masala','display_order'=> '19','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '20','catagory_id' => '1','item_name'=>'Coriander Powder','display_order'=> '20','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '21','catagory_id' => '1','item_name'=>'Garlic','display_order'=> '21','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '22','catagory_id' => '1','item_name'=>'Coconut','display_order'=> '22','current_stock'=> '5','unit'=> 'Pc'),
            array('id' => '23','catagory_id' => '1','item_name'=>'Daliya','display_order'=> '23','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '24','catagory_id' => '1','item_name'=>'Variyali','display_order'=> '24','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '25','catagory_id' => '1','item_name'=>'Suji','display_order'=> '25','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '26','catagory_id' => '1','item_name'=>'Chaval Atta','display_order'=> '26','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '27','catagory_id' => '1','item_name'=>'Besan','display_order'=> '27','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '28','catagory_id' => '1','item_name'=>'Peanut Oil','display_order'=> '28','current_stock'=> '5','unit'=> 'Tin'),
            array('id' => '29','catagory_id' => '1','item_name'=>'Tea','display_order'=> '29','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '30','catagory_id' => '1','item_name'=>'Sugar','display_order'=> '30','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '31','catagory_id' => '1','item_name'=>'Coffee','display_order'=> '31','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '32','catagory_id' => '1','item_name'=>'Appalam','display_order'=> '32','current_stock'=> '5','unit'=> 'Pkt'),
            array('id' => '33','catagory_id' => '1','item_name'=>'Pickle','display_order'=> '33','current_stock'=> '5','unit'=> 'Kg'),


//            Vegetables


            array('id' => '101','catagory_id' => '2','item_name'=>'Tomato','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '102','catagory_id' => '2','item_name'=>'Onion','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '103','catagory_id' => '2','item_name'=>'Ginger','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '104','catagory_id' => '2','item_name'=>'Kadi Patta','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '105','catagory_id' => '2','item_name'=>'Mirchi','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '106','catagory_id' => '2','item_name'=>'Masala','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '107','catagory_id' => '2','item_name'=>'Carrot','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '108','catagory_id' => '2','item_name'=>'Peas','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '109','catagory_id' => '2','item_name'=>'Potato','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '110','catagory_id' => '2','item_name'=>'Drum Stick','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '111','catagory_id' => '2','item_name'=>'Pimpkin','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '112','catagory_id' => '2','item_name'=>'Raw Banana','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '113','catagory_id' => '2','item_name'=>'Raw Mango','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '114','catagory_id' => '2','item_name'=>'Brinjal','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),
            array('id' => '115','catagory_id' => '2','item_name'=>'Banana Leaves','display_order'=> '3','current_stock'=> '5','unit'=> 'Pc'),
            array('id' => '116','catagory_id' => '2','item_name'=>'Gobi','display_order'=> '3','current_stock'=> '5','unit'=> 'Kg'),


//            Dairy


            array('id' => '201','catagory_id' => '3','item_name'=>'Butter','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '202','catagory_id' => '3','item_name'=>'Ghee','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '203','catagory_id' => '3','item_name'=>'Cheese','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '204','catagory_id' => '3','item_name'=>'Curd','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '205','catagory_id' => '3','item_name'=>'Milk','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '206','catagory_id' => '3','item_name'=>'Butter Milk','display_order'=> '3','current_stock'=> '','unit'=> ''),


//            Disposables


            array('id' => '301','catagory_id' => '4','item_name'=>'Paper Napkin','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '302','catagory_id' => '4','item_name'=>'Tea Cups','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '303','catagory_id' => '4','item_name'=>'Coffee Cups','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '304','catagory_id' => '4','item_name'=>'Paper Foil','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '305','catagory_id' => '4','item_name'=>'600 ml Container','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '306','catagory_id' => '4','item_name'=>'50 ml Pouch','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '307','catagory_id' => '4','item_name'=>'500 ml Pouch','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '308','catagory_id' => '4','item_name'=>'Dora','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '309','catagory_id' => '4','item_name'=>'Plastic Spoon','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '310','catagory_id' => '4','item_name'=>'Shambhar Bag','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '311','catagory_id' => '4','item_name'=>'Chattni Bag','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '312','catagory_id' => '4','item_name'=>'Spoon','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '313','catagory_id' => '4','item_name'=>'Waste Bag (Black)','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '314','catagory_id' => '4','item_name'=>'Carry Bag','display_order'=> '3','current_stock'=> '','unit'=> ''),


//            Touletries


            array('id' => '401','catagory_id' => '5','item_name'=>'Floor Cleaner','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '402','catagory_id' => '5','item_name'=>'HandWash','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '403','catagory_id' => '5','item_name'=>'Towels','display_order'=> '3','current_stock'=> '','unit'=> ''),
            array('id' => '404','catagory_id' => '5','item_name'=>'Utensil Cleaner','display_order'=> '3','current_stock'=> '','unit'=> ''),





        );

        DB::table('item_master')->insert($catagories);


    }

}