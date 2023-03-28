<?php
session_start();
//
//if (!isset($_GET['id']))
//	header("Location: Orderdetails.php");

require_once "../libraries/vendor/autoload.php";
require_once "../Config/Connection.php";



$order_id = $_GET['order'];

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
//    var_dump($sql);
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
        $date = "";
        if ($cnt == 0)
        {
            if ($d['history_time'] != ""){
                $date = date("g:i A",strtotime($d['history_time']));
             }
            $text_msg .= $d['park']." ".$date." \n";
        }
        else
        {

            if ($d['history_time'] != ""){
                $date =  " at ".date("g:i A",strtotime($d['history_time']));
            }

            if ($d['method_transfer'] != "no")
                $text_msg .= "Then ".$d['method_transfer']." to ".$d['park']."".$date." \n";
        }

        $cnt++;
    }

    $client = new Client($account_sid, $auth_token);
    try
    {


        $client->messages->create(
           $guest['country_code'].$guest['guest_mobile'],
            array(
                'from' => $twilio_number,
                'body' => $text_msg
            )
        );
    }
    catch (Exception $e)
    {
        continue;
    }
}

$_SESSION['success_msg'] = 'History texted succssfully';
header('Location: Orderdetails.php?active=0');