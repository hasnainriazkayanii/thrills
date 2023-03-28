<?php

include('../Config/Connection.php');

$id=$_GET['id'];

$customer_delete = "DELETE FROM `order` WHERE id='$id'";

$query_order = "select order_id from `order` where id = $id";
$res = mysqli_query($db,$query_order);
$order_id = mysqli_fetch_assoc($res)['order_id'];
if (mysqli_query($db, $customer_delete)) {


  $delete_commission = "DELETE from commPayouts where order_id  = $id";
  mysqli_query($db,$delete_commission);

  $delete_accounting = "DELETE from accounting where orderID  = '$order_id'";
  mysqli_query($db,$delete_accounting);

  $delete_guest = "DELETE from guest where order_id  = $id";
  mysqli_query($db,$delete_guest);

  $delete_history = "DELETE from history where order_id  = $id";
  mysqli_query($db,$delete_history);


    //echo "Record deleted successfully";

  header( "Location: Orderdetails.php?active=0" );

}



?> 