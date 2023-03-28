<?php
session_start();







namespace chillerlan\QRCodeExamples;



use chillerlan\QRCode\{QRCode, QROptions};



require_once 'vendor/autoload.php';



include('../Config/Connection.php');











if(isset($_REQUEST['mobile']))

{

    $mobile=$_REQUEST['mobile'];
    $orderID=$_REQUEST['orderID'];
    $sql="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID'";
    $result=mysqli_query($db,$sql);
    $sql44="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID'";
    $result44=mysqli_query($db,$sql44);
    $user_cc=mysqli_fetch_assoc($result44);
    $isdisabled=$user_cc['isdisabled'];

    if($isdisabled==1)
    {
     echo json_encode(array('status'=>399,'message'=>"You Have Been Signed Out"),JSON_PRETTY_PRINT); 
         exit;
    }

    if(mysqli_num_rows($result) == 0)

    {
         echo json_encode(array('status'=>401,'message'=>"Mobile number or order id is wrong"),JSON_PRETTY_PRINT); 
         exit;
    }

    

    



    

    

$Gd=0;
$_SESSION['order_id']=$orderID;


//$sqlKidCount="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID' and type='kid' and inactive='0'";

$sqlKidCount="SELECT adults,kids FROM `order` where  order_id ='$orderID'";

    $result44=mysqli_query($db,$sqlKidCount);

   $user44=mysqli_fetch_assoc($result44);

    $adults11=$user44['adults'];

     $kids11=$user44['kids'];

    //$resultKidCount=mysqli_query($db,$sqlKidCount);

   // $TotalKidCount=mysqli_num_rows($resultKidCount);

   $TotalKidCount=$kids11;

    //var_dump($TotalKidCount);die;

    //$sqlAdultCount="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID' and type='adult' and inactive='0'";

    //$resultAdultCount=mysqli_query($db,$sqlAdultCount);

    //$TotalAdultCount=mysqli_num_rows($resultAdultCount

    $TotalAdultCount=$adults11;

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



//print_r($AllTickets);exit;



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

}


$_SESSION['orderId'] = $objet['orderId'];
$_SESSION['loginId'] = $objet['loginId'];
$_SESSION['mobile'] = $objet['mobile'];

$sql = "UPDATE `guest` SET islogedin=1 WHERE login_id='{$objet['loginId']}' AND guest_mobile='{$objet['mobile']}'";
$result = mysqli_query($db, $sql);

 echo json_encode(array('status'=>200,'message'=>'You have been successfully logged in','adultCount'=>"$TotalAdultCount",'kidCount'=>"$TotalKidCount",'orderDetails'=>$AllTickets),JSON_PRETTY_PRINT);

  /*  

}



else

{

    echo json_encode(array('status'=>404,'orderDetails'=>"No data find"),JSON_PRETTY_PRINT);

}



}

else

{

    echo json_encode(array('status'=>422,'orderDetails'=>"parameter missing"),JSON_PRETTY_PRINT); 

}*/

    

   

    









?>









