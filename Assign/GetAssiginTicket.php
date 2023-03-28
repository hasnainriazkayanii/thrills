<?php
 include('../Config/Connection.php');
$id=$_GET['id'];

if($id=='0')

{

echo "No";

}

else{

 $sql="SELECT * FROM ticket where ticketshowid ='$id'";

    $result=mysqli_query($db,$sql);

    $user=mysqli_fetch_assoc($result);
    //var_dump($user);die;


echo json_encode($user);

exit();

}

 ?>
