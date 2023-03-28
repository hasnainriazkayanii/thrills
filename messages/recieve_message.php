<?php

if (! isset($_REQUEST['From']))
{
	http_response_code(404);
	die();
}

include('../Config/Connection.php');
require_once "../libraries/vendor/autoload.php";
use Twilio\Rest\Client;
include "../Config/twilio.php";

$from = trim(str_replace("+1", "", $_REQUEST['From']));
$message = trim($_REQUEST['Body']);

// Store in database
$sql = "INSERT INTO `massages` (contact_no, message, type) VALUES ('$from', '$message', 'recieved')";
$result = mysqli_query($db, $sql);

// Check if customer is unsubscribing
$message = strtolower($message);


$client = new Client($account_sid, $auth_token);
try
{
    $client->messages->create(
        $from,
        array(
            'from' => $twilio_number,
            'body' => "You cannot reply to this message. Please call 800-824-7299 for assistance."
        )
    );

}
catch (Exception $e) { }


if ($message === 'stop')
{
	$sql = "UPDATE `customer` SET is_subscribed=0 WHERE Phone_number='$from'";
	$result = mysqli_query($db, $sql);
}