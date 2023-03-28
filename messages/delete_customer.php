<?php 
 require "../Config/Connection.php";
 if (isset($_REQUEST['id'])) {
 	$id=$_REQUEST['id'];
 	$sql="DELETE From `customer` where id=$id";
 	$result=mysqli_query($db, $sql);
 	if($result){
 		echo "Successfully Deleted";
 	}
 	else{
 		echo "unknown error occured";
 	}
 }
 else{
 	echo "Please provide id of customer"; 
 }

 ?>