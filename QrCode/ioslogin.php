<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


namespace chillerlan\QRCodeExamples;

use chillerlan\QRCode\{QRCode, QROptions};

require_once 'vendor/autoload.php';

//header('Content-Type: text/html; charset=utf-8');
include('../Config/Connection.php');





if(isset($_REQUEST['mobile']))
{

	$mobile=$_REQUEST['mobile'];
    //var_dump($mobile);die;

    
     $orderID=$_REQUEST['orderID'];
     
     //print_r($orderID);exit;



	$sql="SELECT g.*,t.set_link,t.ticket_type FROM guest g LEFT JOIN ticket t ON g.ticket_id = t.ticketshowid where g.guest_mobile='$mobile' and g.login_id='$orderID'";
//	var_dump($sql);
	$result=mysqli_query($db,$sql);

	$sql44="SELECT g.*,t.set_link,t.ticket_type FROM guest g LEFT JOIN ticket t ON g.ticket_id = t.ticketshowid where g.guest_mobile='$mobile' and g.login_id='$orderID'";
	$result44=mysqli_query($db,$sql44);
    
    //print_r($sql);exit;
    $user_cc=mysqli_fetch_assoc($result44);
    $isdisabled=$user_cc['isdisabled'];
    $is_screenchot = $user_cc['is_screenshot'];
    if($isdisabled==1)
    {
     echo json_encode(array('status'=>399,'message'=>"You Have Been Signed Out"),JSON_PRETTY_PRINT); 
     exit;
    }
    if(mysqli_num_rows($result) == 0)
    {
         echo json_encode(array('status'=>401,'message'=>"Incorrect Order ID and/or Mobile Number."),JSON_PRETTY_PRINT); 
         exit;
        
    }
    
    

    
    
$Gd=0;
$guestList=[];
$listAllguestQuery = "SELECT guest_name,guest_mobile FROM guest where  order_id='$order'";
$guestListResults = mysqli_query($db,$listAllguestQuery);
if (mysqli_num_rows($guestListResults) > 0) {
	$guestList =  mysqli_fetch_all($guestListResults, MYSQLI_ASSOC);
}
$order = $user_cc['order_id'];
//$sqlKidCount="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID' and type='kid' and inactive='0'";
$sqlKidCount="SELECT adults,kids,theme_park_id,ishidden, date_of_visit FROM `order` where id ='$order'";
$result44=mysqli_query($db,$sqlKidCount);
$user44=mysqli_fetch_assoc($result44);
$adults11=$user44['adults'];
$kids11=$user44['kids'];
$theme_park_id=$user44['theme_park_id'];
$dov=$user44['date_of_visit'];
$ishidden = $user44['ishidden'];

$sqlThemePark = "SELECT theme_park_parent_id , name FROM `theme_parks` where id='$theme_park_id'";
$resultThemePark = mysqli_query($db, $sqlThemePark);
$ThemeParks = mysqli_fetch_assoc($resultThemePark);
$theme_park_parent_id = $ThemeParks['theme_park_parent_id'];
$theme_park_name = $ThemeParks['name'];

    //$resultKidCount=mysqli_query($db,$sqlKidCount);
   // $TotalKidCount=mysqli_num_rows($resultKidCount);
   $TotalKidCount=$kids11;
    //var_dump($TotalKidCount);die;
    //$sqlAdultCount="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID' and type='adult' and inactive='0'";
    //$resultAdultCount=mysqli_query($db,$sqlAdultCount);
    //$TotalAdultCount=mysqli_num_rows($resultAdultCount
    $TotalAdultCount=$adults11;
    $ThemeParkParentId=$theme_park_parent_id;
    $ThemeParkName = $theme_park_name;
    $counta=0;
    $countb=0;
    //print_r($TotalAdultCount);exit;

  while($Orders = mysqli_fetch_assoc($result)) {
      //print_r($Orders);exit;
     
      if($Orders['inactive']==0)
      {
           if($Orders["type"]=="adult" && $TotalAdultCount>$counta)
      {
      $counta++;
          
if($Gd==0)
{
	$order_id=$Orders['order_id'];

	$sqlOrderCheck = "SELECT * FROM `order` where id='$order_id'";
	$resultGuestOrder = mysqli_query($db, $sqlOrderCheck);
	$GuestsOrder = mysqli_fetch_assoc($resultGuestOrder);
}

$Gd++;





$objet['ticketOrder']=$Orders['entitlement'];

$objet['Gid']=$Orders['id'];
$objet['Name']=$Orders['guest_name'];
$objet['set_link']=$Orders['set_link'];
$objet['Ticket_type']=$Orders['ticket_type'];

$objet['isdisabled']=$Orders['isdisabled'];

if($Orders["ticket_id"])
{
$objet['ticketId']=$Orders["ticket_id"];
}

$objet['orderId']=$Orders["order_id"];

$objet['loginId']=$Orders["login_id"];

$objet['mobile']=$Orders["guest_mobile"];
$objet['type']=$Orders["type"];

if($Orders["ticket_id"])
{
	$data = $Orders["ticket_id"];
}
	$options = new QROptions([
		'version' => 5,
		'outputType' => QRCode::OUTPUT_MARKUP_HTML,
		'eccLevel' => QRCode::ECC_L,
		'moduleValues' => [
			// finder
			1536 => '#A71111', // dark (true)
			6    => '#FFBFBF', // light (false)
			// alignment
			2560 => '#A70364',
			10   => '#FFC9C9',
			// timing
			3072 => '#98005D',
			12   => '#FFB8E9',
			// format
			3584 => '#003804',
			14   => '#00FB12',
			// version
			4096 => '#650098',
			16   => '#E0B8FF',
			// data
			1024 => '#4A6000',
			4    => '#ECF9BE',
			// darkmodule
			512  => '#080063',
			// separator
			8    => '#AFBFBF',
			// quietzone
			18   => '#FFFFFF',
		],
	]);

	//echo (new QRCode($options))->render($data);

	//echo (new QRCode)->render($data);

//	echo '<img src="'.(new QRCode)->render($data).'" />';

$QrCode=(new QRCode)->render($data);



$objet['QrCode']=$QrCode;


$dataArray = array();
$query_steps = "SELECT s.* from steps s, `order` o  where o.theme_park_id = s.ThemeParkID and o.id = '{$objet['orderId']}'";
$result_step = mysqli_query($db,$query_steps);
if($result_step){
   while ($row_steps = mysqli_fetch_assoc($result_step)){
	   $dataArray[] = $row_steps;
   }
	$objet['steps'] = $dataArray;
}else{
	$objet['steps']  = $dataArray;

}

$dataArray = array();
$query_legal = "SELECT s.* from legalTerms s, `order` o  where o.theme_park_id = s.ThemeParkID and o.id = '{$objet['orderId']}'";
$result_legal = mysqli_query($db,$query_legal);
if($result_legal){
   while ($row_legal = mysqli_fetch_assoc($result_legal)){
	   $dataArray[] = $row_legal;
   }
   $objet['legal'] = $dataArray;
}else{
   $objet['legal']  = $dataArray;

}




$AllTickets[]=$objet;

// print_r($AllTickets);
// exit;

}
elseif($Orders["type"]=="kid" && $TotalKidCount>$countb){
    
          $countb++;
          
if($Gd==0)
{
$order_id=$Orders['order_id'];

$sqlOrderCheck = "SELECT * FROM `order` where id='$order_id'";
$resultGuestOrder = mysqli_query($db, $sqlOrderCheck);
$GuestsOrder = mysqli_fetch_assoc($resultGuestOrder);
}
$Gd++;





$objet['ticketOrder']=$Orders['entitlement'];

$objet['Gid']=$Orders['id'];
$objet['Name']=$Orders['guest_name'];
$objet['set_link']=$Orders['set_link'];
$objet['Ticket_type']=$Orders['ticket_type'];

//$objet['Name']=$Orders['date_of_visit'];

////////////////////////////////////////////////////////////


//$objet['date_of_visit']=$Orders["date_of_visit"];
//$date_of_visit = $objet['date_of_visit'];
//$ThemeParkParentId=$theme_park_parent_id;



///////////////////////////////////////////////////////////



$objet['isdisabled']=$Orders['isdisabled'];

if($Orders["ticket_id"])
{
$objet['ticketId']=$Orders["ticket_id"];
}

$objet['orderId']=$Orders["order_id"];

$objet['loginId']=$Orders["login_id"];

$objet['mobile']=$Orders["guest_mobile"];
$objet['type']=$Orders["type"];

if($Orders["ticket_id"])
{
	$data = $Orders["ticket_id"];
}
	$options = new QROptions([
		'version' => 5,
		'outputType' => QRCode::OUTPUT_MARKUP_HTML,
		'eccLevel' => QRCode::ECC_L,
		'moduleValues' => [
			// finder
			1536 => '#A71111', // dark (true)
			6    => '#FFBFBF', // light (false)
			// alignment
			2560 => '#A70364',
			10   => '#FFC9C9',
			// timing
			3072 => '#98005D',
			12   => '#FFB8E9',
			// format
			3584 => '#003804',
			14   => '#00FB12',
			// version
			4096 => '#650098',
			16   => '#E0B8FF',
			// data
			1024 => '#4A6000',
			4    => '#ECF9BE',
			// darkmodule
			512  => '#080063',
			// separator
			8    => '#AFBFBF',
			// quietzone
			18   => '#FFFFFF',
		],
	]);

	//echo (new QRCode($options))->render($data);

	//echo (new QRCode)->render($data);

//	echo '<img src="'.(new QRCode)->render($data).'" />';

$QrCode=(new QRCode)->render($data);



$objet['QrCode']=$QrCode;

$AllTickets[]=$objet;
    
    
    
}
}



}

}
else
{
 echo json_encode(array('status'=>422,'orderDetails'=>"parameter missing"),JSON_PRETTY_PRINT);    
}
//print_r($AllTickets);exit;
if(!$AllTickets)
{
  echo json_encode(array('status'=>404,'message'=>"Please Contact Us For Access To This App"),JSON_PRETTY_PRINT);  exit;
}else{
	$_SESSION['orderId'] = $objet['orderId'];
	$_SESSION['loginId'] = $objet['loginId'];
	$_SESSION['mobile'] = $objet['mobile'];

	$sql = "UPDATE `guest` SET islogedin=1 WHERE login_id='{$objet['loginId']}' AND guest_mobile='{$objet['mobile']}'";
	$result = mysqli_query($db, $sql);

	 echo json_encode(array('status'=>200,'message'=>'Logged In! Click OK To Continue.','adultCount'=>"$TotalAdultCount",'kidCount'=>"$TotalKidCount",'ThemeParkParenId'=>"$ThemeParkParentId",'ThemeParkName'=>"$ThemeParkName",'orderDetails'=>$AllTickets,'dateOfvisit'=>$dov,'screenshot'=>$is_screenchot,'Guests'=>$guestList,'ishidden'=>$ishidden),JSON_PRETTY_PRINT);
}
  /*  
}

else
{
    echo json_encode(array('status'=>404,'orderDetails'=>"No data found"),JSON_PRETTY_PRINT);
}

}
else
{
    echo json_encode(array('status'=>422,'orderDetails'=>"parameter missing"),JSON_PRETTY_PRINT); 
}*/
    
   
    




?>




