<?php

  include('../Config/Connection.php');

$gid = $_REQUEST['gid'];

$moblie_no=$_REQUEST['moblie_no'];

$login_id = $_REQUEST['login_id'];

$name=$_REQUEST['name'];

$order_id=$_REQUEST['order_id'];

$type=$_REQUEST['type'];

if($name==null)

{

    echo json_encode(array('Status'=>1,'message'=>'Field can not be blank',));

    exit();

 }

 $text_msg = "$name took screenshot\nOrderID: $login_id\nPhoneNumber: $moblie_no";

$sql = "SELECT * FROM `settings` WHERE name='admin_mobile'";
$result = mysqli_query($db, $sql);
$property = mysqli_fetch_assoc($result);
if ($property)
	$admin_mobile = $property['value'];
else
	$admin_mobile = "";

require_once "../libraries/vendor/autoload.php";
use Twilio\Rest\Client;
include "../Config/twilio.php";

$client = new Client($account_sid, $auth_token);
try
{
    $client->messages->create(
        $admin_mobile,
        array(
            'from' => $twilio_number,
            'body' => $text_msg
        )
    ); 

    $admin_mobile = str_replace("+1", "", $admin_mobile); // Convert to local format from internation format
    // Store message in database
    $sql = "INSERT INTO `massages` (contact_no, message, type) VALUES ('$admin_mobile', '$text_msg', 'sent')";
    $result = mysqli_query($db, $sql);
}
catch (Exception $e)
{
    
}



$insert_users = "INSERT INTO screenshot(gid,moblie_no,login_id,name,order_id,type)VALUES('$gid','$moblie_no','$login_id','$name','$order_id','$type')";
$result =$db->query($insert_users);


//$id =$conn->insert_id;


if($result)
{

//    $sql = "UPDATE `guest` SET isdisabled=1 where id='$gid' AND id='$gid'";
    $sql = "UPDATE `guest` SET isdisabled=1 where guest_mobile='$moblie_no' AND order_id='$order_id'";
    $result = mysqli_query($db, $sql);

   echo json_encode(array('status'=>1,'message'=>'added successfully.'));

   exit();

}

else

{

   echo json_encode(array('status'=>1,'message'=>'adding failed'));

   exit();

}



?>