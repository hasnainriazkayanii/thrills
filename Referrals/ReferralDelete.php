<?php
 include('../Config/Connection.php');
$id=$_GET['id'];
$referral_delete ="DELETE FROM referral_types WHERE id='$id'";
if (mysqli_query($db, $referral_delete)) {
  header( "Location: ReferralDetails.php" );
}

?> 