<?php
include('../Config/Connection.php');
$email=$_GET['email'];
$query="SELECT email FROM `login_user` WHERE email='$email'";
$runQuery=mysqli_query($db,$query);
 if(mysqli_num_rows($runQuery)>0){

      echo json_encode(['success' => true, 'msg' => 'Email already exists']);
      
   }else{
      
      echo json_encode(['success' => false, 'msg' => '']);

   }

?>