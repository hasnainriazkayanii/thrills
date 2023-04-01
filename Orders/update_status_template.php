<?php
require_once "../libraries/vendor/autoload.php";
include "../Config/twilio.php";
use Twilio\Rest\Client;

$client = new Client($account_sid, $auth_token);

if (@$order_confirmed == "1"){
    $update_status = "8";
}
else{
    $update_status = $status;
}


// update Order status
$sql = "UPDATE `order` SET `status`='$update_status' WHERE id='$order_id'";
mysqli_query($db,$sql);
$action_by = $_SESSION['user_id'];
$timestamp_insert = "INSERT INTO timestamps (type,object_id,order_id,action,action_by)
VALUES ('Order Status','$update_status',$order_id,'Updated','$action_by')";
$result = mysqli_query($db,$timestamp_insert);


// loging out user if status is route to park

if($status == 4 || $status == 1 || $status == 2 || $status == 6 ){
    if ($status == 4 || $status == 2){
        $disabled = 1;
    }
    else{
            $disabled = 0;
    }
    $update_guest = "update guest set isdisabled = $disabled where order_id = $order_id";
    mysqli_query($db,$update_guest);
    
}


//get guests
if ($update_status == 8) {
    $guests_sql = "SELECT c.country_code, c.Phone_number as guest_mobile FROM `customer` c, `order` o WHERE o.customer_id = c.id and o.id='$order_id'";
}
else{
    $guests_sql = "SELECT * FROM `guest` WHERE order_id='$order_id'";
}
$guests = mysqli_query($db,$guests_sql);
//var_dump($guests_sql);
//get order
$order_sql = "SELECT o.*,t.name as theme_park_name,c.first_name,c.last_name FROM `order` o LEFT JOIN customer c on c.id = o.customer_id LEFT JOIN theme_parks t ON t.id = o.theme_park_id LEFT JOIN tickettypes tt ON tt.id = o.ticket_type_id WHERE o.id='$order_id'";
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

$time = strtotime($date_of_visit);
$formatDate = date('m/d/y',$time);
$today=date("m/d/y");

if($formatDate==date("m/d/y")){
    $visit_date = "Today";
}
else if ($formatDate==date('m/d/y', strtotime($today. ' +1 days'))){
    $visit_date = 'TOMORROW';
}
else{
    $visit_date = date('M d', strtotime($date_of_visit));
}


$order_time = $order['time'];

$park_code = preg_replace('/[^a-zA-Z]/', '', $order['order_id']);
//First message At the Park
$text_msg1 = "SELECT * FROM `text_messages` WHERE theme_park_id='$theme_park_id' and status = $status limit 1"; // previously it was 2
//        var_dump($text_msg1);
$msg_row = mysqli_fetch_assoc(mysqli_query($db,$text_msg1));
$msg_1 =  $msg_row['message'];

$msg_1 = str_replace('{%fname%}',$first_name,$msg_1);
$msg_1 = str_replace('{%lname%}',$last_name,$msg_1);
$msg_1 = str_replace('{%fullname%}',$customer,$msg_1);
$msg_1 = str_replace('{%themepname%}',$theme_park_name,$msg_1);
$msg_1 = str_replace('{%adults%}',$adults,$msg_1);
$msg_1 = str_replace('{%kids%}',$kids,$msg_1);
$msg_1 = str_replace('{%ototal%}',$total,$msg_1);
$msg_1 = str_replace('{%ttype%}',$ticket_type,$msg_1);
$msg_1 = str_replace('{%onumber%}',$order_id,$msg_1);
$msg_1 = str_replace('{%datevisit%}',$date_of_visit,$msg_1);
$msg_1 = str_replace('{%otime%}',$order_time,$msg_1);

//echo $msg_1 . " <--> ";

$guests_query = "SELECT * FROM `guest` WHERE order_id='$order_id'";
$guest_message = mysqli_query($db,$guests_query);
$guest_count = 1;
while($guest_row = mysqli_fetch_assoc($guest_message)){
    $guest_name = $guest_row['guest_name'];
    $msg_1 = str_replace("{%guest".$guest_count."%}",$guest_name,$msg_1);
    $guest_count = $guest_count + 1;

//            $msg_1 .= $msg_1 . " " . $guest_name;
}



$message_attachment = $msg_row['message_attachment'];

if ($message_attachment != "" && str_replace("images/message_attachments/","",$message_attachment) != ""){
    $msg_1 = $msg_1 . "

https://dbaseconnect.com/cheapthrills/$message_attachment";
}


// sending message to admin based on the status
$admin_id = "";
$admin_message = "";
if ($status == 4){
    $admin_id = "2";
    $admin_message = "$customer is on the way to the park";
}
if ($status == 2){
    $admin_id = "1";
    $admin_message = "$customer has entered the park";
}


if ($status == 11){
    $admin_id = "1,2";
    $admin_message = "$customer order for $theme_park_name $visit_date has been cancelled";
}


if ($is_hide != "1"){
    if ($admin_id != "") {
        $query_admin_number = "SELECT * from settings where id in ($admin_id)";
        $admin_number = mysqli_fetch_assoc(mysqli_query($db, $query_admin_number))['value'];


        try {
            $resp_1 = $client->messages->create(
                $admin_number,
                array(
                    'from' => $twilio_number,
                    'body' => $admin_message
                )
            );


        } catch (Exception $e) { // var_dump($e);
        }
    }


}


//        var_dump($guests_query);
while($guest = mysqli_fetch_assoc($guests)){

//echo $guest['country_code']. $guest['guest_mobile'] . " <--> ";

    // if($status == 1){

    //   //  $ticket_id = $guest['ticket_id'];
    //     $sql1 = "SELECT * FROM history WHERE barcode='$ticket_id' group by history_date";
    //     $result1 = mysqli_query($db, $sql1);
    //     $count = mysqli_num_rows($result1);
    //     if ($count >= 1) {
    //         $history = mysqli_fetch_all($result1, MYSQLI_ASSOC);
    //         $history = $history[count($history) - 1];
    //         $guest_name_title = $guest['guest_name'];
    //         $str = $guest['ticket_id'];
    //       // $ticket_last_digits = substr($str, count(array($str)) - 7);
    //       // $msg_1 .= $guest_name_title . '(' . $ticket_last_digits . ')' . " \n";
    //       $msg_1 .= $guest_name_title . " \n";

    //         $msg_1 .= date("l F d", strtotime($history['history_date'])) . " \n";

    //         $sql = "SELECT * FROM history WHERE barcode='$ticket_id' and history_date='" . $history['history_date'] . "' ORDER BY history_time ASC";
    //         $result = mysqli_query($db, $sql);
    //         $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //         $cnt = 0;
    //         foreach ($data as $d) {
    //             if ($cnt == 0) {
    //                 $msg_1 .= $d['park'] . " " . date("g:i A", strtotime($d['history_time'])) . " \n";
    //             } else {
    //                 if ($d['method_transfer'] != "no")
    //                     $msg_1 .= "Then " . $d['method_transfer'] . " to " . $d['park'] . " at " . date("g:i A", strtotime($d['history_time'])) . " \n";
    //             }

    //             $cnt++;
    //         }
    //     }

    // }
    //send text

    try
    {
        $resp = $client->messages->create(
            $guest['country_code']. $guest['guest_mobile'],
            array(
                'from' => $twilio_number,
                'body' => htmlspecialchars_decode($msg_1)
            )
        );


    }
    catch (Exception $e) {
//        var_dump($e);
    }
}



?>