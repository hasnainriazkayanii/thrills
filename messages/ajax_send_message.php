<?php
session_start();
include('../Config/Connection.php');

$login_check=$_SESSION['id'];

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}

require_once "../libraries/vendor/autoload.php";
use Twilio\Rest\Client;
include "../Config/twilio.php";

if (!isset($_REQUEST['member_id']) || empty($_REQUEST['member_id']))
   return false;

$member_id = $_REQUEST['member_id'];
$message = $_REQUEST['message'];

$sql = "SELECT * FROM `customer` WHERE id=$member_id";
$result = mysqli_query($db, $sql);
$member = mysqli_fetch_assoc($result);

$client = new Client($account_sid, $auth_token);
 try
 {
     $client->messages->create(
         "+1".$member['Phone_number'],
         array(
             'from' => $twilio_number,
             'body' => $message
         )
     );

     // Store message in database
     $sql = "INSERT INTO `massages` (contact_no, message, type) VALUES ('{$member['Phone_number']}', '$message', 'sent')";
     $result = mysqli_query($db, $sql);

     echo json_encode(["status" => true]);
 }
 catch (Exception $e)
 {
   echo json_encode(["status" => false, "errorCode" => $e->getCode(), "errorMessage" => $e->getMessage()]);
 }