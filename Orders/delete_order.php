<?php
include('../Config/Connection.php');
$order_id = $_GET['order_id'];
$orderdata=[];
$OrderQuery ="SELECT * from `order` where id=$order_id";
$OrderQueryResult = mysqli_query($db,$OrderQuery);
if (mysqli_num_rows($OrderQueryResult) > 0){
    $orderdata = mysqli_fetch_assoc($OrderQueryResult);
}
$query = "DELETE from `order` where id = $order_id";
$returnArray = array();
$result = mysqli_query($db, $query);
if($result){
    if($orderdata && isset($orderdata['order_id'])){
        $orderId = $orderdata['order_id'];
        $deleteQuery ="DELETE from `accounting` where orderID = '$orderId'";
        $del = mysqli_query($db, $deleteQuery);
    }
    $deleteQuery ="DELETE from `commPayouts` where orderID = $order_id";
    $del = mysqli_query($db, $deleteQuery);

    $deleteQuery ="DELETE from `guest` where order_id = $order_id";
    $del = mysqli_query($db, $deleteQuery);
    
    $deleteQuery ="DELETE from `history` where order_id = $order_id";
    $del = mysqli_query($db, $deleteQuery);

    $deleteQuery ="DELETE from `screenshort` where order_id = $order_id";
    $del = mysqli_query($db, $deleteQuery);

    $deleteQuery =" DELETE from `timestamps` where order_id = $order_id";
    $del = mysqli_query($db, $deleteQuery);


    $deleteQuery ="DELETE from `timestamps` where object_id = $order_id";
    $del = mysqli_query($db, $deleteQuery);

    $returnArray['status'] = 1;
    $returnArray['developer_message'] = "Order deleted successfully";
}
else{
    $returnArray['status'] = -1;
    $returnArray['developer_message'] = "Failed to delete the order";

}
echo json_encode($returnArray)

?>