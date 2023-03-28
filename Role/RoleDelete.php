<?php
include('../Config/Connection.php');
$id=$_GET['id'];
$admin_delete = "DELETE FROM login_user WHERE id='$id'";
if (mysqli_query($db, $admin_delete)) {
    //echo "Record deleted successfully";
     header( "Location: RoleDetails.php" );
}

?> 