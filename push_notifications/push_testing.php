<?php
include_once 'GlobalDatabase.php';
header('Content-type: application/json');
$id=$_REQUEST['id'];
$title = "TESTING";
$message = "testing";
$data = "abcd";
//var_dump($id);

//$android_id = "dj2pOI-IGvU:APA91bH8KXpgE9CWwF_4RiKqykXupE_55Eg_f8K6iKxYgi_9nMWGAgxyV4Yf-Mvtr_E_oIb2LtQ6gQYqT4oGyh5J4MeS8rnPlggW19RL80FgbQ0x-Z_SoZgj7YA43dDQ0kpGT1FVmwli";
//$send_ios_push = send_push_IOS_internal_news($title,$message,$id,$data);
$order_data = device_token($id);
$device_token = $order_data['device_token'];
$device_type = $order_data['device_type'];


$sendpush_android = sendpush_internal_news($title,$message,$device_token,$data); 

function device_token($id)
{
    global $conn;
    $q['query'] = "SELECT `device_token`,`device_type` FROM `order` WHERE id='$id'"; 
    $q['run'] = $conn->query($q['query']);
    $q['result'] = $q['run']->fetch_assoc();
    //var_dump($q['result']);die;
    return $q['result'];
}

function send_push_IOS_internal_news($title,$message,$id,$data)
{
    //certificate file
 
  $payload['aps'] = array(
    'alert' =>array('title'=>$title,
                    'body'=>$message),
                    'Status' => 1,
                     'badge' => 1,
                     'sound' => 'default',
                     'data'=>$data, 
                     'title'=>$title);
  $payload = json_encode($payload);
 // var_dump($payload);


  //$payload['aps'] = array('alert' => $data, 'Status' => 1,'badge' => 1, 'sound' => 'default'); 
//  $apnsHost = 'gateway.push.apple.com'; // distribution
  if(BASE_URL=='http://webservices.lyndonmarine.com/')
      {
        
        $apnsHost = 'gateway.push.apple.com'; 
        $apnsPort = '2195';
        $apnsCert = 'lmdist.pem';
      }
      elseif(BASE_URL=='http://stagingwebservices.lyndonmarine.com/')
      {
        $apnsHost = 'gateway.sandbox.push.apple.com'; 
        $apnsPort = '2195';
        $apnsCert = 'lmdev.pem';
      }
  

    $passPhrase = '';
  $streamContext = stream_context_create();
  stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);     
  $apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
  

  if($apnsConnection == false){
    echo "False";//return;
    exit;
  } 
    $apnsMessage = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $id)) . pack("n",strlen($payload)) . $payload;
    //var_dump($deviceToken);
   //var_dump($apnsMessage);
     if(fwrite($apnsConnection, $apnsMessage)) 
  {
  
   // echo "Message Delivered.";
  }
  else
  {
   // echo "Message Not Delivered.";
  }
  

  //echo strlen($apnsMessage);
  
  unset($payload);
  fclose($apnsConnection);  
}


function sendpush_internal_news($title,$message,$id,$data) 
{

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
        'notification'=>array("title" => $title,"priority"=> "max","body" => $message,"icon" => "default","sound" => "default"),
        'registration_ids' => array($id),
        'data' => array("data"=>$data)
    );


    $fields = json_encode($fields);
    
    //var_dump($id);
    $headers = array (               
        'Authorization: key=' . "AIzaSyAqUjPOsSflakEjU8KuxFF261rCq9TW81c",
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch, CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4); 
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);

    $result = curl_exec($ch);
    if ($result===false) 
    {
        echo 'Curl error:' . curl_error($ch);exit;
    }
    curl_close($ch);
}
/*if($sendpush_android)
  {
    $message = array('status'=>1,'message'=>'Push sent');
    send_response($message);
    exit;
  }
  else
  {
    $message = array('status'=>0, 'message' => 'Oops! Failed to send push');
    send_response($message);
    exit;
  }*/
?>