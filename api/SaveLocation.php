<?php
echo "ADMK";
exit;



include('../Config/Connection.php');



if(!isset($_REQUEST['lat']) || !isset($_REQUEST['user_long']) || !isset($_REQUEST['Gid']))

{

    

      echo json_encode(array('status'=>422,'message'=>"parameter missing"),JSON_PRETTY_PRINT); 

    

}

else

{

    

$id=$_REQUEST['Gid'];    

//$customer_id=$_REQUEST['customer_id'];

$lat=$_REQUEST['lat'];

$long=$_REQUEST['user_long'];

//$orderId=$_REQUEST['orderId'];



$customer_update = "UPDATE `guest` SET user_lat='$lat',user_long='$long' WHERE id=$id";



//$customer_update = "UPDATE `order` SET user_lat='$lat',user_long='$long' WHERE id=$orderId";

    $result = mysqli_query($db,$customer_update);

    

    if($result)

    {

        

        echo json_encode(array('status'=>200,'message'=>"Data saved successfully"),JSON_PRETTY_PRINT); 

    }

    else

    {

     echo json_encode(array('status'=>403,'message'=>"Data not saved successfully"),JSON_PRETTY_PRINT);    

    }

}



?>