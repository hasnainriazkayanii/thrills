<?php
include('../Config/Connection.php');

$orderno=$_GET['id'];
$orderId=$_GET['orderId'];

  $sql="SELECT * FROM guest where id='$orderno'";
    $result=mysqli_query($db,$sql);
    $Orders = mysqli_fetch_assoc($result);
    $check=$Orders['inactive'];
    
    if($check==0)
    {

$order_update = "UPDATE `guest` SET inactive=1 WHERE id='$orderno'";
         mysqli_query($db,$order_update);
         
    }
    else
    {
   $order_update = "UPDATE `guest` SET inactive=0 WHERE id='$orderno'";
         mysqli_query($db,$order_update);     
    }
       
         header( "Location: ../Assign/Addassign.php?id=".$orderId."" );


?>