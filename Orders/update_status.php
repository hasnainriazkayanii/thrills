<?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
include('../Config/Connection.php');

require_once "../libraries/vendor/autoload.php";
include "../Config/twilio.php";
use Twilio\Rest\Client;


session_start();
try{

    $login_check=$_SESSION['id'];

    if ($login_check!='1') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $_SESSION['intended_url'] = $full_url;
        header("location: ../Login/login.php");
    }


    if(isset($_REQUEST['mark']) && isset($_REQUEST['order_id'])){ // mark as 0->pending , 1->Arrived, 2->At the Park, 3->Archived


        $status = (int) $_REQUEST['mark'];
        $order_id = $_REQUEST['order_id'];

        include 'update_status_template.php';

      header( "Location: Orderdetails.php?active=0" );
    }


}catch(Exception $e){
    var_dump($e);
}

?>