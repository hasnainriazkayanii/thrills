<?php
include('../Config/Connection.php');
$order_id = @$_GET['order_id'];
$returnArray = array();
$query = "SELECT a.*,u.user_name from accounting a LEFT JOIN login_user u on u.id = a.personCollecting  where a.orderID = '$order_id' order by a.id desc";
$result = mysqli_query($db,$query);
while($row = mysqli_fetch_assoc($result)){
    if ($row['dateOfPayment'] != "") {
        $payment_date = date('m/d/y h:i A', strtotime($row['dateOfPayment']));
        $row['dateOfPayment'] = $payment_date;
    }


    $returnArray[] = $row;
}
//var_dump($query);

echo json_encode($returnArray);


?>