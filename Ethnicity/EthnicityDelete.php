<?php
 include('../Config/Connection.php');
$id=$_GET['id'];
$customer_delete ="DELETE FROM ethnicity WHERE id='$id'";
if (mysqli_query($db, $customer_delete)) {
    //echo "Record deleted successfully";
  header( "Location: EthnicityDetails.php" );
}

?> 