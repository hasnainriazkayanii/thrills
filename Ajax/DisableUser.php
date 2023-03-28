<?php

include('../Config/Connection.php');


$orderno = $_GET['id'];

$orderId = $_GET['orderId'];

$mobile = $_GET['mobile'];



$sql = "SELECT * FROM guest where order_id='$orderId' AND guest_mobile = $mobile ";

$result = mysqli_query($db, $sql);

$Orders = mysqli_fetch_assoc($result);
$check = $Orders['isdisabled'];



if ($check == 0) {


     $order_update = "UPDATE `guest` SET isdisabled=1, islogedin=0 WHERE order_id='$orderId' AND guest_mobile = $mobile  ";
     mysqli_query($db, $order_update);
} else {

     $order_update = "UPDATE `guest` SET isdisabled=0 WHERE order_id='$orderId' AND guest_mobile = $mobile";
     mysqli_query($db, $order_update);
}



header("Location: ../Assign/Addassign.php?id=" . $orderId . "");

