<?php
 include('../Config/Connection.php');
$id=$_GET['id'];
$status_delete ="DELETE FROM status WHERE id='$id'";
if (mysqli_query($db, $status_delete)) {
  header( "Location: Status.php" );
}

?> 