<?php
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\v1\Apicontroller;
//print_r($orderdetails);exit;
$orderid = $orderdetails['orderdetails']['orderdetails']['suborder_id'];
        if(isset($orderdetails['orderdetails']['orderdetails']['combine_address']) && $orderdetails['orderdetails']['orderdetails']['combine_address']!=''){
            $address = $orderdetails['orderdetails']['orderdetails']['combine_address'];
        }else{
            $address='';
        }
$mobilenumber = $orderdetails['orderdetails']['orderdetails']['user_mobile_number'];
$name =$orderdetails['orderdetails']['orderdetails']['name'];
$ordertype = $orderdetails['orderdetails']['orderdetails']['order_type'];

        $getoutletname=\App\Outlet::findOutlet($orderdetails['orderdetails']['orderdetails']['outlet_id']);

        $outletname=$getoutletname->name;
        $outletnumber=$getoutletname->contact_no;

        $orderitemdetails=\App\OrderItem::where('order_id', $orderdetails['orderdetails']['orderdetails']['order_id'])->get();
//print_r($orderitemdetails);exit;
$i=0;
date_default_timezone_set('Asia/Kolkata');
$date_time = date("F j, Y, g:i a");

$userIpAddress = Request::getClientIp();
        $totalprice=$orderdetails['orderdetails']['orderdetails']['totalprice'];
        $discount=$orderdetails['orderdetails']['orderdetails']['discount_value'];
        $priceafterdiscount=$orderdetails['orderdetails']['orderdetails']['totalcost_afterdiscount'];
        $paidtype=$orderdetails['orderdetails']['orderdetails']['paid_type'];
?>

<h1>Order Details</h1>

<p>
    Order Id: <?php echo ($orderid); ?> <br>
    Address: <?php echo ($address);?> <br>
    Phone number:<b> <?php echo ($mobilenumber);?></b><br>

    Name: <?php echo ucfirst($name); ?><br>
    Order Type: <?php echo App\Http\Controllers\Api\v1\Apicontroller::get_order_type($ordertype);?><br>
    Date: <?php echo($date_time);?><br>
    Outlet Name:<?php echo ucfirst($outletname);?><br>
    Outlet Phone Number:<b><?php echo ($outletnumber);?></b>
    <h2>Order Details</h2>
<?php foreach($orderitemdetails as $itemdetails){
//	print_r($itemdetails);
        $itemname=\App\Menu::where('id',$itemdetails->item_id)->get();//print_r($itemname);exit; ?>
        Item Name: <?php echo ucfirst($itemname[0]->item); ?> <br>
        Item Quantity: <?php echo ($itemdetails->item_quantity); ?> <br>
        Item Price: <?php echo ($itemdetails->item_price); ?> <br>

<?php $i++;}?>

<h2>Price Details</h2>
    Total Price:<?php echo ($totalprice);?><br>
    Discount:<?php echo ($discount);?><br>
    Price After Discount:<?php echo ($priceafterdiscount); ?><br>
    Payment Type:<?php echo ($paidtype); ?><br>
    User IP address: <?php echo ($userIpAddress);?><br>
</p>



