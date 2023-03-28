<?php
 include('../Config/Connection.php');
$id=$_GET['id'];
if($id=='0')
{
echo "No";
}
else{
 $sql="SELECT * FROM tickettype where id='$id'";
    $result=mysqli_query($db,$sql);
    $user=mysqli_fetch_assoc($result);

echo json_encode($user);
exit();
}
 ?>