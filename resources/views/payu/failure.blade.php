<?php
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];

$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$salt="AijPdMwY";
$message="Invalid Transaction. Please try again";
If (isset($_POST["additionalCharges"])) {
    $additionalCharges=$_POST["additionalCharges"];
    $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

}
else {

    $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

}
$hash = hash("sha512", $retHashSeq);

if ($hash != $posted_hash) {

    echo "Invalid Transaction. Please try again";
}
else {

    echo "<h3>Your order status is ". $status .".</h3>";
    echo "<h4>Your transaction id for this transaction is ".$txnid.".</h4>";

}
?>
<!--Please enter your website homepagge URL -->
<script>
    function failure() {
        window.PayUMoney.failure('<?php echo $status; ?>','<?php echo $txnid; ?>','<?php echo $message;?>');
    }
    failure();
</script>

