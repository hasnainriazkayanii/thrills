<?php

include('../Config/Connection.php');

session_start();
$login_check=$_SESSION['id'];


 //var_dump($data1);

if ($login_check!='1') {
 $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location:http://cheapthrillstix.com/app/appadmin/Login/login.php");
}




if (!isset($_POST['message']) || empty($_POST['message']))
{
	$_SESSION['error_msg'] = "The message field is required";
	header("Location: text_messages.php");
	exit();
}

if (!isset($_POST['title']) || empty($_POST['title']))
	$title = null;
else
$title = $_POST['title'];

$message = htmlspecialchars($_POST['message'],ENT_QUOTES);
$theme_park_id = $_POST['theme_park_id'];
$status=$_POST['status'];

if ($_FILES['message_attachment']['size'] == 0 && $_FILES['message_attachment']['error'] == 0){
    $message_attachment_DBpath = "";
}
else{
$filename = $_FILES['message_attachment']['name'];
$message_attachment_DBpath = "images/message_attachments/".$filename;



move_uploaded_file($_FILES['message_attachment']['tmp_name'], "../images/message_attachments/".$filename);
    
}
$send_to='';
if(isset($_POST['send_to']) && !empty($_POST['send_to'])){
	$send_to = $_POST['send_to'];
	$send_to = json_encode($send_to);
}
$admin_message = $_POST['admin_message'];

// Store in database
$sql = "INSERT INTO `text_messages` (title, message,theme_park_id,status,message_attachment,send_to,admin_message) VALUES ('$title', '$message','$theme_park_id','$status','$message_attachment_DBpath','$send_to',admin_message)";
$result = mysqli_query($db, $sql);
//var_dump($sql);
$_SESSION['success_msg'] = "Text message successfully added.";
header("Location: text_messages.php");
