<?php
include('../Config/Connection.php');
    $date = $_POST['date'];
    $order_id = $_POST['order_id'];
    $returnArray = array();
    $query = "select * from expenses where expense_day = '$date'";
    $result = mysqli_query($db, $query);
    $temp_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp_array[] = $row;
    }
//    var_dump($query);
    $returnArray['expense'] = $temp_array;
    $temp_array = array();
    $query_refund = "select *,sum(paymentAmount) as payment_amount from accounting where typeOfPayment = 'Refund' and orderID in ($order_id) group by orderID";
    $result_refund = mysqli_query($db, $query_refund);
    while ($row_refund = mysqli_fetch_assoc($result_refund)) {
        $temp_array[] = $row_refund;
    }
    $returnArray['refund'] = $temp_array;

    $query_payout = "SELECT c.payoutTotal, p.name as sales_name,c.createdTime,c.is_manager_payout from commPayouts c, partners p where p.id = c.partnerID and c.payoutDay = '$date'";
    $result_payout = mysqli_query($db, $query_payout);
    $temp_array = array();
    while ($row_payout = mysqli_fetch_assoc($result_payout)) {
        $row_payout['createdTime'] = date("m/d", strtotime($row_payout['createdTime']));
        $row_payout['sales_name'] = $row_payout['sales_name'] . " " . $row_payout['createdTime'];
        $temp_array[] = $row_payout;
    }
    $returnArray['payout'] = $temp_array;




    echo json_encode($returnArray);
?>

