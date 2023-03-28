<?php
include('../Config/Connection.php');

session_start();

$order_date = @$_GET['order_date'];
$expense_type = @$_GET['expense_type'];
$order_amount = @$_GET['order_amount'];
$set_link = @$_GET['set_link'];
$user_id = $_SESSION['id'];
$returnArray = array();
$query = "INSERT INTO `expenses` (`expense_type`, `amount`, `created_by_id`, `expense_day`,`set_link`) 
            VALUES ('$expense_type','$order_amount','$user_id','$order_date','$set_link')";
$result = mysqli_query($db,$query);
if($result){
    $returnArray['status'] = 1;
    $returnArray['developer_message'] = "Expense added successfully";
}
else{
    $returnArray['status'] = -1;
    $returnArray['developer_message'] = "Failed to add the expense";

}

echo json_encode($returnArray);




?>