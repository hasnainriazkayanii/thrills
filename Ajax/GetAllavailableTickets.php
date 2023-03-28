<?php

include('../Config/Connection.php');


//$GetDate=$_GET['GetDate'];

//print_r($GetDate);exit();

$sql = "SELECT * FROM ticket";

$result = mysqli_query($db, $sql);
 if (mysqli_num_rows($result) > 0) {

$data[] = '<option value="0">Please select.... </option>';

while($row = mysqli_fetch_assoc($result)) {



$id=$row['id'];

$tickeId=$row['ticketshowid'];

/*
$sql_2 = "SELECT * FROM ticket
INNER JOIN `order` ON ticket.id = order.tiket_type where order.tiket_type='$id' and order.date_of_visit='$GetDate' and iscancel=0";
$result_2=mysqli_query($db,$sql_2);
    $dataGet=mysqli_fetch_assoc($result_2);*/

//print_r($dataGet);exit;
//if(!$dataGet)
//{


	$data[] = '<option value="'.$tickeId.'">'.$tickeId.'</option>';
    

//}

     /*$Customer_name[]=$row["first_name"].' '.$row["Last_name"].'_'.$row["id"];*/
}

//print_r($data);exit;

echo implode(" ",$data);

//$Customer_name= json_encode($Customer_name);
//print_r($Customer_name);exit;
}



?>