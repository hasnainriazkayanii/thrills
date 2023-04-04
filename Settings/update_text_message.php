<?php

include('../Config/Connection.php');

session_start();
$login_check=$_SESSION['id'];


 //var_dump($data1);

if ($login_check!='1') {
 $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location:http://cheapthrillstix.com/app/appadmin/Login/login.php");
}

if (! isset($_REQUEST['id']))
{
	http_response_code(404);
	exit();
}

$id = $_REQUEST['id'];

if (!isset($_POST['message']) || empty($_POST['message']))
{
	$_SESSION['error_msg'] = "The message field is required";
	header("Location: text_messages.php");
	exit();
}
$send_to='';
if(isset($_POST['send_to']) && !empty($_POST['send_to'])){
	$send_to = $_POST['send_to'];
	$send_to = json_encode($send_to);
}
$admin_message = $_POST['admin_message'];

if (!isset($_POST['title']) || empty($_POST['title']))
	$title = null;
else
	$title = $_POST['title'];

	$theme_park_id = $_POST['theme_park_id'];
	$status = $_POST['status'];
	$message = htmlspecialchars($_POST['message'],ENT_QUOTES);

if($_FILES['edit-message-attach']['name']==""){
    $sql = "UPDATE `text_messages` SET title='$title',theme_park_id='$theme_park_id',status='$status',message='$message',send_to = '$send_to',admin_message='$admin_message' WHERE id=$id";
}
else{
    $new_file = $_FILES['edit-message-attach']['name'];
    $image_path = $_POST['old-image-path'];
    $db_path = "images/message_attachments/".$new_file;
    
    
    unlink("../".$image_path);
    move_uploaded_file($_FILES['edit-message-attach']['tmp_name'], "../images/message_attachments/".$new_file);


    $sql = "UPDATE `text_messages` SET title='$title',theme_park_id='$theme_park_id',status='$status',message='$message',message_attachment='$db_path',send_to = '$send_to',admin_message='$admin_message' WHERE id=$id";
  //  var_dump($sql);
    
}



// Store in database

$result = mysqli_query($db, $sql);

$_SESSION['success_msg'] = "Text message successfully updated.";


 header("Location: text_messages.php");


