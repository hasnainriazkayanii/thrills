<?php
include 'custom_message_tag.php';

$order_sql = "SELECT o.*,tt.ticket_name,tt.ticket_type as main_ticket_type,t.name as theme_park_name,c.first_name,c.last_name FROM `order` o LEFT JOIN customer c on c.id = o.customer_id LEFT JOIN theme_parks t ON t.id = o.theme_park_id LEFT JOIN tickettypes tt ON tt.id = o.ticket_type_id WHERE o.order_id='$order_id'";
$order = mysqli_fetch_assoc(mysqli_query($db,$order_sql));
$theme_park_id = $order['theme_park_id'];
$customer = $order['customer'];
$first_name = $order['first_name'];
$last_name = $order['last_name'];
$theme_park_name = $order['theme_park_name'];
$adults = $order['adults'];
$kids = $order['kids'];
$total = $order['total'];
$ticket_type = $order['ticket_type'];
$order_id = $order['order_id'];
$is_hide = $order['is_hide'];
$date_of_visit = $order['date_of_visit'];
$main_ticket_type = $order['ticket_name'];


$sql = "SELECT * FROM `theme_parks` WHERE `id`={$order['theme_park_id']}";
$result = mysqli_query($db, $sql);
$theme_park = mysqli_fetch_assoc($result);

$message = "Orlando Ticket World Here! \n";
$message .= "ORDER DETAILS: \n";

$message .= "$first_name $last_name \n";


if($order['adults']>0 && $order['kids']==0 ){
    if($order['adults']==1){
        $message .= $order['adults']."Adult \n";

    }
    else{
        $message .= $order['adults']." Adults \n";
    }
}
else if($order['adults']==0 && $order['kids']>0 ){
    if($order['kids']==1){
        $message .= $order['kids']." Child \n";

    }
    else{
        $message .= $order['kids']." Child \n";
    }

}
else{
    if($order['kids']==1 && $order['adults']==1){
        $message .= $order['adults']." Adult/".$order['kids']." Child \n";

    }
    else if($order['kids']==1 ){
        $message .= $order['adults']." Adult/".$order['kids']." Child \n";

    }
    else if($order['adults']==1 ){
        $message .= $order['adults']." Adult/".$order['kids']." Child \n";

    }
    else{
        $message .= $order['adults']." Adult/".$order['kids']." Child \n";
    }

}

$var=$order['ticket_type'];
if (strpos($var, 'Universal $100') !== false) {
    $var2=$order['ticket_type'];
    $result = substr($var2, 0, 10);

  //  $message .= $theme_park['name']." (".$main_ticket_type."), \n";
    $message .= "$main_ticket_type \n";
    $message .= date("D M d",strtotime($order['date_of_visit'])).", ".date("g:i A",strtotime($order['time']))." \n";

    $message .= "Your Total is $".$order['total']." CASH ONLY \n";
    $message .= "Exact Amount Please. We Do Not Have Change. \n";

    $park_code = preg_replace('/[^a-zA-Z]/', '', $order_id);

    $get_msg="SELECT message from text_messages where theme_park_id='{$order['theme_park_id']}' and status = 0";
    $res_msg = mysqli_query($db, $get_msg);
    $final_message = mysqli_fetch_assoc($res_msg);
    $message .=$final_message['message'];


}

else{
    $var2=$order['ticket_type'];
    $result = substr($var2, 0, 10);
   // $message .= $theme_park['name']." (".$main_ticket_type."),\n";
   $message .= "$main_ticket_type \n";
    $message .= date("D M d",strtotime($order['date_of_visit'])).", ".date("g:i A",strtotime($order['time']))." \n";

    $message .= "Your Total  is $".$order['total']." CASH ONLY\n";
    $message .= "Exact Amount Please. We Do Not Have Change. \n";

    $park_code = preg_replace('/[^a-zA-Z]/', '', $order_id);

    $get_msg="SELECT message from text_messages where theme_park_id='{$order['theme_park_id']}' and status = 0";
    $res_msg = mysqli_query($db, $get_msg);
    $final_message = mysqli_fetch_assoc($res_msg);
    $message .=$final_message['message'];


}

$message = custom_taga($message,$first_name,$last_name,$customer,$theme_park_name,$adults,$kids,$total,$ticket_type,$order_id,$date_of_visit,$order_time);

?>