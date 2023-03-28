<?php

 include('../Config/Connection.php');

$id=$_POST['ticket_id'];
//$getprice_new = $_POST['getprice_new'];

if($id=='0')

{

echo "No";

}

else{

// $sql="SELECT * FROM tickettypes where ticket_name='$id' AND adult_price = '$getprice_new' AND child_price = '$getprice_new'";
    $sql="SELECT * FROM tickettypes where id='$id' ";

    $result=mysqli_query($db,$sql);

    $user=mysqli_fetch_assoc($result);


echo json_encode($user);

exit();

}

 ?>