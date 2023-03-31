<?php
require_once "libraries/vendor/autoload.php";
$account_sid = '';
$auth_token = '';


$twilio_number = "";
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
<<<<<<< HEAD
}
=======
}
>>>>>>> 38b34c1eafc42c69141dc5071711358323325dd0
