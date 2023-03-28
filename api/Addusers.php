<?php
  include('../Config/Connection.php');

$name=$_REQUEST['name'];
$email=$_REQUEST['email'];

if($name==null)
{
    echo json_encode(array('Status'=>1,'message'=>'Field can not be blank',));
    exit();
 }

$insert_users = "INSERT INTO users(name,email)VALUES('$name','$email')";

$result =$db->query($insert_users);
//$id =$conn->insert_id;

if($result)
{
    
   echo json_encode(array('status'=>1,'message'=>'Your request have been received successfully. Admin will contact you shortly..'));
   exit();
}
else
{
   echo json_encode(array('status'=>1,'message'=>'adding failed'));
   exit();
}

?>