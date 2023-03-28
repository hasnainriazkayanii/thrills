<?php



include('../Config/Connection.php');



if(isset($_REQUEST['mobile']) && isset($_REQUEST['orderID']))

{

$mobile=$_REQUEST['mobile'];

//var_dump($_REQUEST['mobile']);

$orderID=$_REQUEST['orderID'];

  $sql44="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID'";

 

    $result44=mysqli_query($db,$sql44);

    

    //print_r($sql);exit;

    $user_cc=mysqli_fetch_assoc($result44);

    //var_dump($user_cc);

    //print_r($user_cc);exit;

    $isdisabled=$user_cc['isdisabled'];

//print_r($isdisabled);exit;


     echo json_encode(array('status'=>399,'isdisabled'=>$isdisabled,'message'=>"You Have Been Signed Out"),JSON_PRETTY_PRINT); 

         exit;

    

}



else

{

    echo json_encode(array('status'=>422,'orderDetails'=>"parameter missing"),JSON_PRETTY_PRINT); 

}





?>