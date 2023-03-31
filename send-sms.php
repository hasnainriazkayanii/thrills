<?php
require_once "libraries/vendor/autoload.php";
$account_sid = 'AC16f42580ae3d7630ea8f25818dae8bf0';
$auth_token = 'fa3a7301b02a9b4d1270040ad65b18a3';


$twilio_number = "+14094077660";
$client = new Twilio\Rest\Client($account_sid, $auth_token);

// Your Twilio phone number

// The phone number you want to send the SMS to
$to_number = '+923048448190';

// The SMS message body
$message = 'Hello, World!';

try {
    // Send the SMS message via Twilio's API
    $client->messages->create(
        $to_number,
        array(
            'from' => $twilio_number,
            'body' => $message
        )
    );
    echo "SMS sent successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}