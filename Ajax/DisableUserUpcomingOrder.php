<?php 


include('../Config/Connection.php');


$orderno = $_POST['guest_id'];

$orderId = $_POST['order_id'];

$mobile = $_POST['phone_number'];

$sql = "SELECT * FROM guest where order_id='$orderId'";
     
     $result = mysqli_query($db, $sql);
     
     $Orders = mysqli_fetch_assoc($result);
     $check = $Orders['isdisabled'];
     
     
     
     if ($check == 0) {
     
     
          $order_update = "UPDATE `guest` SET isdisabled=1, islogedin=0 WHERE order_id='$orderId' ";
          mysqli_query($db, $order_update);
     } else {
     
          $order_update = "UPDATE `guest` SET isdisabled=0 WHERE order_id='$orderId' ";
          mysqli_query($db, $order_update);
     }
     
     
     if(mysqli_query($db, $order_update)){
          echo "done";
     }
     else{
          echo "not";
     }


?>