<?php
 include('../Config/Connection.php');
$id=$_GET['id'];

$ticket_delete = "DELETE FROM tickettypes WHERE id='$id'";
if (mysqli_query($db,$ticket_delete))
{
  header( "Location: TicketsDetails.php" );
}

?> 