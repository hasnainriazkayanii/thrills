<?php

include('../Config/Connection.php');

session_start();
$login_check=$_SESSION['id'];


 //var_dump($data1);

if ($login_check!='1') {
 $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location:http://cheapthrillstix.com/app/appadmin/Login/login.php");
  exit();
}


if (!isset($_REQUEST['id']))
{
	http_response_code(404);
	die();
}

$id = $_REQUEST['id'];

$query = "SELECT * FROM `text_messages` WHERE id=$id";
$query_fire = mysqli_query($db,$query);

if($query_fire && mysqli_num_rows($query_fire)>0){
    $data = mysqli_fetch_assoc($query_fire);
    $message_attach = $data['message_attachment'];
  
    
    unlink("../".$message_attach);
    
}

// Delete text message from database
$sql = "DELETE FROM `text_messages` WHERE id=$id";
$result = mysqli_query($db, $sql);

$_SESSION['success_msg'] = "Text message successfully deleted.";
header("Location: text_messages.php");
