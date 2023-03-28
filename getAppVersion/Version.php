<?php
header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];

	if ($requestMethod=='GET') {
		getAppVersion();
	}
	else{
		response(NULL, NULL, 400,"Invalid Request");
	}

function getAppVersion(){
	
	$androidVersion = '1.0';
	$iosVersion     = '1.0';
	
	$response['code']   		= 200;
	$response['status'] 		= 'success';
	$response['androidVersion'] = $androidVersion;
	$response['iosVersion'] 	= $iosVersion;
	
	echo json_encode($response);
	
}
?>