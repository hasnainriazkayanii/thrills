<?php

include('../Config/Connection.php');

if(isset($_REQUEST['QrCode']))
{
    $QrCode=$_REQUEST['QrCode'];
    
    $QrArray=explode("/",$QrCode);
    
   
    $date_of_visit=date("Y-m-d");
   // echo date("Y-m-d");exit;
    
    $sql="SELECT * FROM `order` where order_id='$QrCode' and date_of_visit='$date_of_visit'";
    $result=mysqli_query($db,$sql);
    //$user=mysqli_fetch_assoc($result);
     
    //print_r($user);exit;
   // $userId=$user['id'];
    
/*    $sqlOrder = "SELECT * FROM `order` where customer_id='$userId'";
$resultOrder = mysqli_query($db, $sqlOrder);*/



if (mysqli_num_rows($result) > 0) {

while($Orders = mysqli_fetch_assoc($result)) {
    
$totalDetails=$Orders["order_id"];
$ticketId=(explode("/",$totalDetails));
$objet['ticketId']=$ticketId[3];
$objet['customer_id']=$Orders["customer_id"];    
$objet['orderId']=$Orders["id"];
$objet['customer']=$Orders["customer"];
$objet['dateOfVisit']=$Orders["date_of_visit"];
$objet['price']=$Orders["price"];
$objet['adults']=$Orders["adults"];
$objet['kids']=$Orders["kids"];
$objet['total']=$Orders["total"];
$objet['is_expire']=$Orders["is_expire"];
$objet['ticket_order']=$Orders["ticket_order"];

$objet['order_id']=$Orders["order_id"];
  $AllTickets[]=$objet;  
}
//print_r($AllTickets);exit;

 echo json_encode(array('status'=>200,'orderDetails'=>$AllTickets),JSON_PRETTY_PRINT);


}
else
{
  echo json_encode(array('status'=>401,'message'=>"Ticket is not valid"),JSON_PRETTY_PRINT);   
}

}
else
{
   echo json_encode(array('status'=>422,'message'=>"parameter missing"),JSON_PRETTY_PRINT); 
}





?>