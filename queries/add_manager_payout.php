<?php
include('../Config/Connection.php');

session_start();

$manager_amount = @$_POST['manager_amount'];
$manager_sale = @$_POST['manager_sales'];
$manager_type = @$_POST['manager_type'];
$order_date = @$_POST['order_date'];
$user_id = $_SESSION['id'];
$returnArray = array();
$query = "INSERT INTO `commPayouts` (`type`, `partnerID`, `payoutTotal`, `payoutDay`,`createdBy`,`is_manager_payout`) 
            VALUES ('$manager_type','$manager_sale','$manager_amount','$order_date','$user_id','1')";
$result = mysqli_query($db,$query);
var_dump($query);
if($result){
    $returnArray['status'] = 1;
    $returnArray['developer_message'] = "Manager Payout added successfully";
}
else{
    $returnArray['status'] = -1;
    $returnArray['developer_message'] = "Failed to add the Manager Payout";

}

echo json_encode($returnArray);




?>