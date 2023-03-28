<?php
include('../Config/Connection.php');
$order_id = $_GET['order_id'];
$query = "DELETE from `order` where id = $order_id";
$returnArray = array();
$result = mysqli_query($db, $query);
if($result){
    $returnArray['status'] = 1;
    $returnArray['developer_message'] = "Order deleted successfully";
}
else{
    $returnArray['status'] = -1;
    $returnArray['developer_message'] = "Failed to delete the order";

}
echo json_encode($returnArray)

?>