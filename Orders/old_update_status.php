<?php

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

    $mark_data = explode('-',$_REQUEST['mark']);
    $status = (int) $mark_data[1];
    $statu_id = $mark_data[0];
    $order_id = $_REQUEST['order_id'];

	// update Order status
    $sql = "UPDATE `order` SET `status`='$status' WHERE id='$order_id'";
	mysqli_query($db,$sql);

	//get guests
	$guests_sql = "SELECT * FROM `guest` WHERE order_id='$order_id'";
	$guests = mysqli_query($db,$guests_sql);
	
	//get order
	$order_sql = "SELECT * FROM `order` WHERE id='$order_id'";
	$order = mysqli_fetch_assoc(mysqli_query($db,$order_sql));
	
		$park_code = preg_replace('/[^a-zA-Z]/', '', $order['order_id']);
	//First message At the Park
	$text_msg1 = "SELECT * FROM `text_messages` WHERE theme_park_id='$park_code' ";
	
	$msg_1 = mysqli_fetch_assoc(mysqli_query($db,$text_msg1))['message'];

	//Second message Closed
	$text_msg2 = "SELECT * FROM `text_messages` WHERE id='12'";
	$msg_2 = mysqli_fetch_assoc(mysqli_query($db,$text_msg2))['message'];
	
	

    if($status == 1){   // mark as arrived

        header( "Location: text_history.php?id=$order_id" );
		
    }
    elseif($status == 2){   // mark as at the park
        $guest_logout = "UPDATE `guest` SET isdisabled=1, islogedin=0 WHERE order_id='$order_id'";
        mysqli_query($db,$guest_logout);
		
		while($guest = mysqli_fetch_assoc($guests)){
			
			//send text
			
			$client = new Client($account_sid, $auth_token);
			
			try
			{
				$resp = $client->messages->create(
					$guest['country_code']. $guest['guest_mobile'],
					array(
						'from' => $twilio_number,
						'body' => $msg_1
					)
				);
			
				
			}
			catch (Exception $e) { }
			
				// schedule for 10 minuts if p2p and US
// 			if($order['theme_park_id']>17 && $order['theme_park_id']<21 && $order['ticket_type'] == "1 Day Park-to-Park $160"){
				
				
// 				try
// 				{
					
// 					$delay = new DateTime('now');
				
// 					$delay->add(new DateInterval('PT10M'));
					
// 					$client->messages->create(
// 						$guest['country_code']. $guest['guest_mobile'],
// 						array(
// 							'from' => $twilio_number,
// 							'body' => $msg_1,
// 							'sendAt'=> $delay
// 						)
// 					);
					
// 				}
// 				catch (Exception $e) { }
					
// 			}
		
		}
		
		header( "Location: Orderdetails.php?active=0" );
		
    }
    elseif($status == 3){ // mark as closed
        $guest_logout = "UPDATE `guest` SET isdisabled=1, islogedin=0 WHERE order_id='$order_id'";
        mysqli_query($db,$guest_logout);
		
			$client = new Client($account_sid, $auth_token);
			while($guest = mysqli_fetch_assoc($guests)){
			try
			{
				$resp = $client->messages->create(
					$guest['country_code']. $guest['guest_mobile'],
					array(
						'from' => $twilio_number,
						'body' => $msg_2
					)
				);
				
				
				//schedule for 1 day
				
				// $delay = new DateTime('now');
			
				// $delay->add(new DateInterval('P1D'));
				
			 //$client->messages->create(
				// 	$guest['country_code']. $guest['guest_mobile'],
				// 	array(
				// 		'from' => $twilio_number,
				// 		'body' => $msg_1,
				// 		'sendAt'=> $delay
				// 	)
				// );
				
			}
			catch (Exception $e) { }
			
			}
			
		header( "Location: Orderdetails.php?active=0" );
		
    }else{

        header( "Location: Orderdetails.php?active=0" );
    }

}

}catch(Exception $e){
	var_dump($e);
}

?>