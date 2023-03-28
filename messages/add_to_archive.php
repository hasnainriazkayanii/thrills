<?php

session_start();
include('../Config/Connection.php');

$login_check=$_SESSION['id'];

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}

if (! isset($_REQUEST['contact_no']))
{
	http_response_code(404);
	die();
}

$contact_no = $_REQUEST['contact_no'];

$sql = "INSERT INTO `archived_chats` (contact_no) VALUES ('$contact_no')";
$result = mysqli_query($db, $sql);

// $_SESSION['success_msg'] = "Added to archive successfully";

//header ("Location: {$_SERVER['HTTP_REFERER']}");
header("location: ../messages/index.php");