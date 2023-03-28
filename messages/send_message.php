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

if (! isset($_POST['message']) || empty($_POST['message']))
{
	$_SESSION['notifications'][] = array(
		'type' => 'error',
		'message' => "Message field cannot be empty."
	);
	header("Location: ../Customers/CustomersDetails.php?manage_state=true");
	exit();
}

$message = trim($_POST['message']);

if (isset($_POST['member_id']))
{
	$member_id = $_POST['member_id'];
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
    }
    catch (Exception $e)
    {
    	if ($e->getCode() === 21211) // Invalid Number
    	{
    		
    	}
    }

    $_SESSION['notifications'][] = array(
		'type' => 'success',
		'message' => "Message sent to ".$member['first_name'].' '.$member['last_name']." successfully"
	);
	header("Location: ../Customers/CustomersDetails.php?manage_state=true");
}
elseif (isset($_POST['member_ids']))
{
	$member_ids = $_POST['member_ids'];

	foreach ($member_ids as $key => $member_id)
	{
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
	    }
	    catch (Exception $e)
	    {
	    	if ($e->getCode() === 21211) // Invalid Number
	    	{
	    	}
	    }
	}

	unset($_SESSION['message_system']['selected_members']);
	$_SESSION['notifications'][] = array(
		'type' => 'success',
		'message' => "Message sent to selected members successfully"
	);
	header("Location: ../Customers/CustomersDetails.php?manage_state=true");
}
else
{
	$_SESSION['notifications'][] = array(
		'type' => 'error',
		'message' => "You didn't select any members. Please selected atleast one member"
	);
	header("Location: ../Customers/CustomersDetails.php?manage_state=true");
}