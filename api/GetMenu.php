<?php 
 include('../Config/Connection.php');
include_once'Functions.php';
header('Content-type: application/json');
/*extract($_REQUEST);
$inputJSON = file_get_contents('php://input');
$input = json_decode( $inputJSON, TRUE ); //convert JSON into array*/

//$id = $input['id'];
$get_menu_detail = get_menu_detail();


if($get_menu_detail)
{
	$message = array('status'=>1,'message'=>'Menu Details','data'=>$get_menu_detail);
	send_response($message);
	exit;
}
else
{
	$message = array('status'=>0,'message'=>'No details found');
	send_response($message);
	exit;
}
?>