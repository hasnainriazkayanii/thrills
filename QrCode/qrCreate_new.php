<?php



/**

 *

 * @filesource   html.php

 * @created      21.12.2017

 * @author       Smiley <smiley@chillerlan.net>

 * @copyright    2017 Smiley

 * @license      MIT

 */



namespace chillerlan\QRCodeExamples;



use chillerlan\QRCode\{QRCode, QROptions};



require_once 'vendor/autoload.php';



header('Content-Type: text/html; charset=utf-8');

include('../Config/Connection.php');











if(isset($_REQUEST['mobile']))

{

    $mobile=$_REQUEST['mobile'];

    

    $orderID=$_REQUEST['orderID'];

     

    // print_r($orderID);exit;

    

    //$sql="SELECT * FROM customer where Phone_number='$mobile' and ticket_order='$orderID'";

    

    $sql="SELECT * FROM guest where guest_mobile='$mobile' and login_id='$orderID'";

    $result=mysqli_query($db,$sql);

    //$user=mysqli_fetch_assoc($result);

    if(mysqli_num_rows($result) == 0)

    {

         echo json_encode(array('status'=>401,'message'=>"Mobile number or order id is wrong"),JSON_PRETTY_PRINT); 

         exit;

        

    }

    

$Gd=0;

$countkid=0;

$countadult=0;

  while($Orders = mysqli_fetch_assoc($result)) {

      

      

      

      if($Orders['inactive']==0)

      {

if($Gd==0)

{

$order_id=$Orders['order_id'];



$sqlOrderCheck = "SELECT * FROM `order` where id='$order_id'";

$resultGuestOrder = mysqli_query($db, $sqlOrderCheck);

$GuestsOrder = mysqli_fetch_assoc($resultGuestOrder);

}

$Gd++;

$object =  new \stdClass();

if($Orders['type']=="kid")

{

    print_r($Orders['type']);

$countkid++;







//print_r($Orders);exit;

if($countkid==1)

{

$object->ticketOrder=$GuestsOrder['ticket_order'];



$object->Gid=$Orders['id'];

$object->Name=$Orders['guest_name'];

$object->ticketId=$Orders["ticket_id"];



$object->orderId=$Orders["order_id"];



$object->loginId=$Orders["login_id"];



$object->mobile=$Orders["guest_mobile"];

$object->type=$Orders["type"];

$object->count=$countkid;





	$data = $Orders["ticket_id"];



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







$object->QrCode=$QrCode;







}

else

{

	$object->Name.$countkid=$Orders['guest_name'];

}





$AllTickets[]=$object;

}



else

{

$countadult++;



//$object =  new \stdClass();

if($countadult==1)

{

//print_r($Orders);exit;

$object->ticketOrder=$GuestsOrder['ticket_order'];



$object->Gid=$Orders['id'];

$object->Name=$Orders['guest_name'];

$object->ticketId=$Orders["ticket_id"];



$object->orderId=$Orders["order_id"];



$object->loginId=$Orders["login_id"];



$object->mobile=$Orders["guest_mobile"];

$object->type=$Orders["type"];

$object->count=$countadult;





	$data = $Orders["ticket_id"];



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







$object->QrCode=$QrCode;



$AllTickets[]=$object;



}



else

{

$object->Name.$countadult=$Orders['guest_name'];

$object->count=$countadult;

}



$AllTickets[]=$object;



}





}





}

}

if(!$AllTickets)

{

  echo json_encode(array('status'=>404,'message'=>"No active ticket found for your account"),JSON_PRETTY_PRINT);  exit;

}

   //$AllTickets= json_encode($AllTickets);

 //print_r($AllTickets);exit;

$_SESSION['orderId'] = $object->orderId;
$_SESSION['loginId'] = $object->loginId;
$_SESSION['mobile'] = $object->mobile;

$sql = "UPDATE `guest` SET islogedin=1 WHERE login_id='{$objet['loginId']}' AND guest_mobile='{$objet['mobile']}'";
$result = mysqli_query($db, $sql);

 echo json_encode(array('status'=>200,'message'=>'You have been successfully logged in','orderDetails'=>$AllTickets),JSON_PRETTY_PRINT);

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









