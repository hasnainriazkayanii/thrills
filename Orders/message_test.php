<?php
session_start();



require_once "../libraries/vendor/autoload.php";
require_once "../Config/Connection.php";



$order_id = 1114;

$sql = "SELECT * FROM `order` WHERE `id`=$order_id";
$result = mysqli_query($db,$sql);
$order = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM `guest` WHERE `order_id`={$order['id']}";
$result = mysqli_query($db,$sql);
$guests = mysqli_fetch_all($result, MYSQLI_ASSOC);

use Twilio\Rest\Client;
include "../Config/twilio.php";
foreach ($guests as $guest)
{
    
    $ticket_id = $guest['ticket_id'];
    $sql = "SELECT * FROM history WHERE barcode='$ticket_id' group by history_date";
    $result = mysqli_query($db, $sql);
    $history = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $history = $history[count($history) - 1];
    $guest_name_title=$guest['guest_name']; 
    $str=$guest['ticket_id'];
    $ticket_last_digits=substr($str, count($str) -7);
$text_msg=$guest_name_title.'('.$ticket_last_digits.')'." \n";
    
    $text_msg .= date("l F d",strtotime($history['history_date']))." \n";

    $sql = "SELECT * FROM history WHERE barcode='$ticket_id' and history_date='".$history['history_date']."' ORDER BY history_time ASC";
    $result = mysqli_query($db, $sql);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $cnt = 0;
    foreach ($data as $d)
    {
        if ($cnt == 0)
        {
            $text_msg .= $d['park']." ".date("g:i A",strtotime($d['history_time']))." \n";
        }
        else
        {
            if ($d['method_transfer'] != "no")
                $text_msg .= "Then ".$d['method_transfer']." to ".$d['park']." at ".date("g:i A",strtotime($d['history_time']))." \n";
        }

        $cnt++;
    }

    $client = new Client($account_sid, $auth_token);
    try
    {
        
        echo "done";
        $client->messages->create(
           $guest['country_code'].$guest['guest_mobile'],
            array(
                'from' => $twilio_number,
                'body' => $text_msg,
                "mediaUrl" => ["https://c1.staticflickr.com/3/2899/14341091933_1e92e62d12_b.jpg"]
            )
        ); 
    }
    catch (Exception $e)
    {
        continue;
    }
}

$_SESSION['success_msg'] = 'History texted succssfully';
