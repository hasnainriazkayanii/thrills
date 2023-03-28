<?php

include('../Config/Connection.php');

session_start();


$login_check = $_SESSION['id'];

$level = $_SESSION['level'];

if ($login_check != '1') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $full_url = $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION['intended_url'] = $full_url;
    header("location: ../Login/login.php");
}

$ishide = '';
if ($level < 8) {
    $ishide = "AND `is_hide`=0";
}


include('../includes/header.php');

$ishide = '';
if ($level < 8) {
    $ishide = "AND order.is_hide=0";
}

$upcoming_table_title  = 'Current Orders';

$current_date = date("Ymd");
$secrch_Date = date("Y-m-d");

$search_result_msg =0;

if(isset($_POST['search_name']) && isset($_POST['activedata']) ){
    if($_POST['search_name']=="" && $_POST['activedata']==2){
        $upcoming_table_title ='Past Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
                 WHERE order.date_of_visit<'$current_date' $ishide group by order.order_id ORDER BY order.date_of_visit Desc,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
        // var_dump($sql)
    }
    else if($_POST['search_name']=="" && $_POST['activedata']==1){
        $upcoming_table_title = 'Active Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID  Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
                WHERE order.date_of_visit>='$current_date' $ishide AND  order.status not in (3) $ishide group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else if($_POST['search_name']!="" && $_POST['activedata']==0){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID WHERE order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%'  $ishide  group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else if($_POST['search_name']!="" && $_POST['activedata']==1){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
        WHERE order.date_of_visit>='$secrch_Date' AND (order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%')  $ishide  AND order.status not in (3) group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";


    }
    else if($_POST['search_name']!="" && $_POST['activedata']==2){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
        WHERE (order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%'  $ishide  AND order.status in (3,11,12)) group by order.order_id ORDER BY order.date_of_visit DESC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
//  var_dump($sql);
    }
    else if(isset($_GET['active']) && $_GET['active']=="3"){
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID WHERE order.date_of_visit>=$current_date $ishide AND order.status  not in (3) group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else{

        // Fetch upcoming orders
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID WHERE order.date_of_visit>=$current_date $ishide AND order.status not in (3) group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
}
else{

    // Fetch upcoming orders
    $sql = "SELECT *, customer.country_code as customer_country_code ,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID  WHERE order.date_of_visit>=$current_date $ishide  group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
}
//var_dump($sql);
// if(isset($_POST['search_name']) && $_POST['search_name']!=""){
//     $search_name = $_POST['search_name'];
//     $search_result_msg =1;
//     $upcoming_table_title ='Search Orders';
//     $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%'  $ishide  AND order.status<=3 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
// }
//  if(isset($_POST['activedata'])){

//     if($_POST['activedata']==1){
//         $upcoming_table_title ='Past Orders';
//         $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id
// WHERE order.date_of_visit<$current_date $ishide AND  order.status<=3 or order.status=3  $ishide group by order.order_id ORDER BY order.date_of_visit Desc,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
//     }
//     else{
//         $upcoming_table_title = 'Active Orders';
//         $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id  Left JOIN guest as g ON order.id= g.order_id
// WHERE order.date_of_visit>=$current_date $ishide AND  order.status<=3 $ishide group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
//     }
// }
// else if(isset($_GET['active']) && $_GET['active']=="3"){
//     $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.date_of_visit>=$current_date $ishide AND order.status<=3 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
// }
// else{

// // Fetch upcoming orders
// $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number, order.customer_id as customer_code FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.date_of_visit>=$current_date $ishide AND order.status<=2 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
// }
//print_r($sql);
//var_dump($sql);
$result = mysqli_query($db, $sql);

$upcoming_orders_unsorted = mysqli_fetch_all($result, MYSQLI_ASSOC);

//print_r($upcoming_orders_unsorted);
// Format orders by date
$src_tz = new DateTimeZone('America/Chicago');
$dest_tz = new DateTimeZone('America/New_York');
$upcoming_orders = array();
foreach ($upcoming_orders_unsorted as $order) {
    $today = new DateTimeImmutable(date("Y-m-d H:i:s"), $src_tz);
    $date_of_visit = new DateTimeImmutable($order['date_of_visit'] . ' ' . $order['time'], $src_tz);

    $today = $today->setTimezone($dest_tz);
    $date_of_visit = $date_of_visit->setTimezone($dest_tz);

    $diff = $today->diff($date_of_visit);

    $upcoming_orders[$date_of_visit->format("Ymd")][] = $order;
}







// echo '<pre>';
// print_r($upcoming_orders);
// exit();
?>
<style>
    .table-bordered ,.table-bordered td, .table-bordered th{
        border: 3px solid black !important;
    }
    table.table-borderless td, table.table-borderless th {
        border: none !important;
    }
    .time_para{
        font-size: 20px;
        color: red;
        margin: 0;
    }

    @media(max-width:395px){
        .flex-wrap-sm{
            flex-wrap:wrap;
        }
    }
</style>


<div class="modal fade" id="report-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Daily Sales & Commission Report</h4>
                <h4 class="report_date"></h4>
            </div>
            <div class="modal-body">
                <div style="overflow-x:auto;">
                <table class="table table-bordered report_table" id="dataTable" style="border: 0px !important;width:1210px !important;" cellspacing="0">
                    <thead style="padding: 0px;">
                        <th style="border-right: 0px !important;">Cust</th>
                        <th style="border-right: 0px !important;border-left: 0px !important;">Order</th>
                        <th style="border-right: 0px !important;border-left: 0px !important;">Park</th>
                        <th style="border-right: 0px !important;border-left: 0px !important;">Gross</th>
                        <th style="border-right: 0px !important;border-left: 0px !important;">Sales</th>
                        <th style="border-right: 0px !important;border-left: 0px !important;">Comm</th>
                        <th style="border-left: 0px !important;">Net</th>
                    </thead>
                    <tbody style="padding: 0px;">
                    </tbody>
                    <tfoot style="padding: 0px;line-height: 0px;">
                        <tr>
                            <td colspan="6" style="text-align: right;border: 0px !important;"><b>Total:</td>
                            <td style="border: 0px !important;"><span class="net_profit_total"></span></td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="commission_div">
                        <span class="" style="text-decoration: underline;font-size: 18px;">Commissions Payouts</span>
                        <div class="commission_container" style="margin-bottom: 20px;">
                            <div><span style="margin-right: 136px;">Dough</span> <span>$200</span></div>
                            <div><span style="margin-right: 136px;">Dough</span> <span>$200</span></div>
                        </div>
                        </div>
                        <span class="expense_div" style="text-decoration: underline;font-size: 18px;margin-top: 10px;">Expenses</span>
                        <div class="expense_container"></div>
                        <div class="refund_div">
                            <span class="" style="text-decoration: underline;font-size: 18px;">Refund</span>
                            <div class="refund_container" style="margin-bottom: 20px;"><div><span style="margin-right: 136px;">Mike</span> <span>$10</span></div></div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <table style="width: 100%;">
                            <tbody>
                            <tr>
                                <td>
                                    <select class="expense_type" style="width: 152:px;">
                                        <option value="">Additional Expenses</option>
                                        <option value="1">Universal Tickets</option>
                                        <option value="2">Seaworld Tickets</option>
                                        <option value="6">Legoland Tickets</option>
                                    </select>
                                    <select class="expense_setlink" disabled style="width: 152:px;">
                                        <option value="">Select</option>
                                    </select>

                                </td>
                                <td>
                                    <input class="order_amount" placeholder="Amount" type="text" style="width: 66px;border: 2px solid;">
                                    <input type="hidden" class="report_order_date" value="20220826">
                                    <button class="btn btn-warning update_reporting" style="padding: 1px;margin-left: 8px;">Update</button>
                                </td>
                            </tr>
                            <tr style="font-size: 26px;margin-top: 10px;">
                                <td>Final Total</td>
                                <td><span class="final_total">$920</span></td>
                            </tr>
                            </tbody></table>
                    </div>
<!--                    <div class="col-md-6" style="text-align: right;">-->
<!--                        <div><span>Additional Expenses</span>-->
<!--                            <span style="padding-right: 65px;margin-left: 77px;">Amount</span>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            <span style="">-->
<!--                                <select class="expense_type" style="width: 152:px;">-->
<!--                                    <option>Universal Tickets</option>-->
<!--                                    <option>Seaworld Tickets</option>-->
<!--                                    <option>Legoland Tickets</option>-->
<!--                                </select></span>-->
<!--                            <span style="margin-left: 77px;">-->
<!--                                <input class="order_amount" type="text" style="width: 52px;border: 2px solid;">-->
<!--                                <input type="hidden" class="report_order_date" />-->
<!--                            </span>-->
<!--                            <button class="btn btn-warning update_reporting" style="padding: 1px;margin-left: 8px;">Update</button>-->
<!--                        </div>-->
<!--                        <div style="font-size: 26px;margin-top: 10px;">-->
<!--                            <span style="">Final Total</span>-->
<!--                            <span class="final_total" style="padding: 1px;margin-left: 109px;margin-right: 60px;">$200</span>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>]
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payments-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Payments</h4>
                <button type="button" class="close ml-0" data-dismiss="modal">&times;</button>
            </div>
            <form action="Addorders.php" class="payment_form" autocomplete='off' autocomplete='off' method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="font-weight-bold">Customer Name</label>
                        <input type="text" class="typeahead form-control" id="customer-name-field" placeholder="Customer Name" readonly>
                        <input type="hidden" name="customerID" id="customer-id-field">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Order Number</label>
                        <input type="text" class="typeahead form-control" name="orderID" id="order_id" placeholder="Order Number" readonly>
                    </div>

                    <div class="form-group">
                        <div class="form-group d-flex">
                            <select name="paymentMethod" id="paymentMethod" class="form-control">
                                <option value="" selected> Select Payment Method </option>
                                <option value="Cash">Cash </option>
                                <option value="Website">Website</option>
                                <option value="Venmo">Venmo</option>
                                <option value="Zelle">Zelle</option>
                                <option value="Cashapp">Cashapp</option>
                                <option value="Paypal">Paypal</option>
                            </select>
                            <input type="hidden" id="payment-order-id" name="order_id">
                            <input type="text" class="form-control" name="paymentAmount" id="payment-due-field" required aria-describedby="Deposit" placeholder="0">
                            <select name="typeOfPayment" id="typeOfPayment" class="form-control">
                                <option value="" selected> Reason </option>
                                <option value="Deposit">Deposit </option>
                                <option value="Payment">Payment</option>
                                <option value="Balance Payoff">Balance Payoff</option>
                                <option value="Security Deposit">Security Deposit</option>
                                <option value="Refund">Refund</option>
                            </select>
                        </div>

                        <div class="form-group refund_reason" style="display: none;">
                            <input type="text" class="form-control" name="refund_reason" id="" placeholder="Refund Reason">
                            <input type="text" class="form-control" name="commission_deduction" id="" placeholder="Sales Commission Deduction">
                        </div>

                        <div class="form-group">
                            <input type="checkbox" name="sendText" value="1">
                            <label for="checkmsg">Send Receipt to Customer</label>
                        </div>

                        <div class="row payment_box">
                            <div class="col-md-12">
                                    <span class="" style="text-decoration: underline;font-size: 18px;">Payment History</span>
                                    <div class="payment_container" style="margin-bottom: 20px;"></div>
                            </div>
                        </div>
                        <div class="" style="border-top: 1px solid;">
                            <label class="font-weight-bold" for="balance_due">Balance Due</label>
                            <span name="bal_due" id="bal-due-field">$280</span>
                        </div>


                    </div>


                    <div class="modal-footer">
                        <button type="submit" name="userpay" class="btn btn-success mr-auto">Submit</button>
                        <a style="display: none;" class="payment_history" href="payment_history.php"> <button type="button" class="btn btn-primary mr-auto">Payment History</button></a>
                    </div>


                </div>
            </form>
        </div>
    </div>
</div>

<div id="content-wrapper">

    <div class="container-fluid">

        <!--<div class="row">-->
        <!--    <div class="col-md-10">-->

        <!--        <div class="col-md-8" style="float:left;">-->
        <!--            <h3>Upcoming Orders</h3>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <?php
        if(isset($_SESSION['success_msg'])){
            ?>
            <div class="alert alert-success">
                <?= $_SESSION['success_msg'] ?>
            </div>

            <?php
            unset($_SESSION['success_msg']);
        }
        ?>

        <div class="card">
            <div class="card-header align-items-baseline card-header d-flex justify-content-between" style="flex-wrap: wrap;">
                <div class="d-flex align-items-baseline">
                    <p style="font-size: 22px;font-weight: 600;">

                        <i class="fas fa-table"></i>

                        <?= $upcoming_table_title ?>
                    </p>


                    <p class="ml-2">
                        <?php
                        if($search_result_msg==1){
                            echo'(Search Result For : <b>'. $_POST['search_name'].'</b>)';
                        }

                        ?>

                </div>

                <!--    <form class="mar-10" id="ssform" name="orderdata" method="post" action="upcoming_order.php">-->
                <!--<div class="d-flex" style="flex-wrap: wrap;align-items: center;">-->

                <!--<form action="upcoming_order.php?active=0" method="post">-->



                <!--</form>-->
                <!--         <div class="input-group" style="width: max-content;">-->

                <!--            <div class="input-group-append mar-10">-->

                <!--                <input type="text" class="form-control" name='search_name' placeholder="Search for"-->
                <!--                       aria-label="Search"  style="min-width:100px">-->

                <!--                <input style="padding: 8px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;"-->
                <!--                       type="submit" class="btn btn-primary" name="submit" value="Search">-->

                <!--                <a style="padding: 7px 6px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;"-->
                <!--                   href="upcoming_order.php?active=3" class="btn btn-primary" name="show_all">Show All</a>-->

                <!--            </div>-->

                <!--        </div>-->

                <!--        <div class="input-group" style="width: max-content;">-->

                <!--            <div class="input-group-append">-->

                <!--                <select class="form-control" id="orderid" name="activedata">-->

                <!--                     <option value="">Please select</option>-->

                <!--                    <option id="active" value="0">Current Orders</option>>-->

                <!--                    <option id="Past" value="1">Past orders</option>-->

                <!--                </select>-->

                <!--            </div>-->

                <!--        </div>-->


                <!--</div>-->
                <!--    </form>-->

                <form method="post" action="Orderdetails.php?active3" id="upcoming_search" >
                    <div class="d-flex align-items-center" style="flex-wrap:wrap">
                        <div class="d-flex flex-wrap-sm" style="margin-right: 10px;align-items: center;">
                            <input type="text" class="form-control" name='search_name' placeholder="Search for" aria-label="Search"  style="min-width:100px">

                            <!--<input style="padding: 8px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;"-->
                            <!--       type="button" class="btn btn-primary" name="submit" value="Search">-->
                            <button type="button" style="padding: 8px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;margin:10px" class="btn btn-primary" onclick="serach_orders()"> Search</button>

                            <a style="padding: 7px 6px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;"
                               href="Orderdetails.php?active=3" class="btn btn-primary" name="show_all" >Show All</a>
                        </div>
                        <div>
                            <div class="input-group-append">

                                <select class="form-control" id="orderid" name="activedata">

                                    <option <?php echo $_POST['activedata']==0?'selected':'' ?> value="0" selected>Please Select</option>

                                    <option <?php echo $_POST['activedata']==1?'selected':'' ?> id="active" value="1">Current Orders</option>>

                                    <option <?php echo $_POST['activedata']==2?'selected':'' ?> id="Past" value="2">Past Orders</option>

                                </select>

                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="card-body table-responsive">

                <?php
                if($search_result_msg==1){
                    echo "<p style='text-align: right;font-weight: 700;margin:0'> Total Records : ".count($upcoming_orders)."</p>";
                }

                $upcoming_orders_records = count($upcoming_orders);
                if($upcoming_orders_records<=0){
                    echo "<p class='text-center'>No Result Found</p>";
                }

                foreach ($upcoming_orders as $title => $orders) {




                    $count_records = count($orders);
                    $next_day = date('Ymd', strtotime( $current_date . " +1 days"));
                    $show_date = '';
                    $table_date ='';

                    if($current_date==$title){
                        if($count_records>1){
                            $show_date=$count_records.' Orders';
                        }
                        else{
                            $show_date=$count_records.' Order';
                        }
                        $table_date = 'Today';
                    }
                    else if($next_day == $title){
                        if($count_records>1){
                            $show_date=$count_records.' Orders';
                        }
                        else{
                            $show_date=$count_records.' Order';
                        }
                        $table_date = 'Tomorrow';

                    }
                    else{
                        if($count_records>1){
                            $show_date = $count_records.' Orders';
                        }
                        else{
                            $show_date = $count_records.' Order';
                        }
                        $table_date = date("D, M jS", strtotime($title));

                    }


                    ?>
                    <div class="d-flex align-items-center  mb-2 container_<?php echo $title; ?>">
                        <h4 class="m-0 w-50"><?= $table_date ?> </h4>
                        <p class="m-0"><?= $show_date ?></p>
                        <p class="m-0" style="text-align: right;width:44%;"><button class="btn btn-success daily_report" type="button">Daily Closeout Report</button> </p>
                    </div>
                    <table class="table table-bordered order_table" data-date="<?php echo $title; ?>" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                        <?php

                        foreach ($orders as $key => $value) {

                            $display_order = "";
                            if(@$_POST['search_name'] == "") {
                                if (@$_POST['activedata'] != 2) {
                                    if ($value['status'] == 3 || $value['status'] == 11) {
                                        if (date("G:i") > "15:00") {
//                                    continue;
                                            $display_order = "display:none";
                                        }
                                    }
                                }
                            }

                            $totalpersons = $value['adults'] + $value['kids'];
//                            if($totalpersons<=3){
                            $totalamount = $value['amount_sum'];
//                            }else {
//                                $totalamount = $value['sum_amount'] / $totalpersons;
//                            }
                            $totaldeposit = $value['deposit'] + $totalamount;

                            if($level>=8){
                                $result16 = mb_substr($value["Phone_number"], 0, 3);
                                $result17 = mb_substr($value["Phone_number"], 3, 3);
                                $result18 = mb_substr($value["Phone_number"], 6, 4);
                                $result19 = "(" . $result16 . ") " . $result17 . "-" . $result18;
                                if($value['customer_country_code']=='+1'){
                                    $country_code ='';
                                }
                                else{
                                    $country_code = $value['customer_country_code'];
                                }
                                $telephone =  $country_code . $result19 ;
                            }
                            else{
                                $telephone = "(***) ***-" . substr($row1["Phone_number"], strlen($row1["Phone_number"]) - 4)."";
                            }

                            ?>
                            <tr style="<?php echo $display_order; ?>">

                                <td class="w-25 position-relative" style="min-width: 245px;">


                                    <div class="text-center">
                                        <?php
                                        $ticket_order = $value['ticket_order'];
                                        list($a, $b,$c) = explode('/',$ticket_order);
                                        $test = str_replace("+", "_", $c);
                                        $adult = $value['adults'];
                                        $kid = $value['kids'];
                                        $ticket_type_id = $value['ticket_type_id'];
                                        $ticket_type_image = "";
                                        if($ticket_type_id != ""){
                                            $ticket_type_query = "SELECT image from tickettypes where id = '$ticket_type_id'";
                                            $result_ticket = mysqli_query($db, $ticket_type_query);
                                            while($ticket_row = mysqli_fetch_assoc($result_ticket)) {
                                                $ticket_type_image = "../".$ticket_row['image'];
                                            }
                                        }
                                        else if ($test == "Kings Island Front Line Pass _ Admission"){
                                            $ticket_type_image = "../images/kingsislandwithadmission.png";
                                        }
                                        else if (preg_match('/\Kings Island Front Line\b/i', $c)){
                                            $ticket_type_image = "../images/kingsisland.png";
                                        }
                                        else if (preg_match('/\Universal Studios\b/i', $c)){
                                            $ticket_type_image = "../images/universal-logo-small.png";
                                        }
                                        else if (preg_match('/\Islands Of Adventure\b/i', $c)){
                                            $ticket_type_image = "../images/IOA.png";
                                        }
                                        else if (preg_match('/\bAnnual Pass\b/i', $c)){
                                            $ticket_type_image = "../images/annualpass.jpg";
                                        }
                                        else if ($test == "Cedar Point Front Line Pass _ Admission"){
                                            $ticket_type_image = "../images/cedarpointwithadmission.png";
                                        }
                                        else if (preg_match('/\Cedar Point\b/i', $c)){
                                            $ticket_type_image = "../images/Cedar-point.png";
                                        }
                                        else if (preg_match('/\One Day Dollywood\b/i', $c)){
                                            $ticket_type_image = "../images/dollywood-logo-color.png";
                                        }
                                        else if (preg_match('/\Legoland\b/i', $c)){
                                            $ticket_type_image = "../images/Legoland_logo.svg.png";
                                        }
                                        else if(preg_match('/\bAquarium\b/i', $c)){
                                            $ticket_type_image = "../images/seaworld-logo-small.png";
                                        }
                                        else if ($test == "Carowinds Front Line Pass _ Admisson") {
                                            $ticket_type_image = "../images/carowindsw-admission.png";
                                        }
                                        else if (preg_match('/\Carowinds\b/i', $c)){
                                            $ticket_type_image = "../images/carowinds.png";
                                        }
                                        else if (preg_match('/\Busch\b/i', $c)){
                                            $ticket_type_image = "../images/BG-small.jpg";
                                        }
                                        else if (preg_match('/\Volcano bay\b/i', $c)){
                                            $ticket_type_image = "../images/VB-Logo-Trans.png";
                                        }
                                        else if (preg_match('/\b2 Park\b/i', $c)){
                                            $ticket_type_image = "../images/2ParkLogo-Universal.jpg";
                                        }
                                        else if (preg_match('/\HHN\b/i', $c)){
                                            $ticket_type_image = "../images/HHNStackedLogoBlack-300x153.png";
                                        }

                                        ?>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div style="flex:1">
                                                <img src="<?php echo $ticket_type_image ?>" alt="" style="width: 67%;margin: 0 auto;display: flex;">

                                            </div>
                                            <div style="flex:1">
                                                <p class="time_para"><?=  $value['time']?></p>
                                                <p style="font-size:25px;margin-bottom: 0px;"><?= $value['order_code'] ?></p>
                                                <p class="mb-0">
                                                    <?php
                                                    $ticket_person = "";
                                                    if($adult!=0 && $kid!=0){

                                                        if($adult>1 && $kid>1){
                                                            $ticket_person = $adult.' Adults'.'/'.$kid.' Childs';
                                                        }

                                                        else if($adult>1){
                                                            $ticket_person = $adult.' Adults'.'/'.$kid.' Child';
                                                        }
                                                        else if($kid>1){
                                                            $ticket_person = $adult.' Adult'.'/'.$kid.' Childs';
                                                        }
                                                        else{
                                                            $ticket_person = $adult.' Adult'.'/'.$kid.' Child';
                                                        }
                                                    }
                                                    else if($adult==0){
                                                        if($kid>1){
                                                            $ticket_person = $kid.' Childs';
                                                        }
                                                        else{
                                                            $ticket_person = $kid.' Child';
                                                        }

                                                    }else if($kid==0){
                                                        if($adult>1){
                                                            $ticket_person = $adult.' Adults';
                                                        }
                                                        else{
                                                            $ticket_person = $adult.' Adult';
                                                        }
                                                    }

                                                    $total_ticket = $adult;
                                                    if ($kid >= 1){
                                                        $total_ticket .= "/$kid";
                                                    }

                                                    echo $ticket_person;
                                                    ?>

                                                <?php if ($value['is_hide'] != "1") { ?>
                                                <div class="report_data" style="display: none">
                                                    <input type="hidden" class="order_date" value="<?php echo $title; ?>">
                                                    <input type="hidden" class="formatted_date" value="<?php echo date("D, M d, Y", strtotime($title)); ?>">
                                                    <input type="hidden" class="customer_name" value="<?php echo $value['customer']; ?>">
                                                    <input type="hidden" class="order_id" value="<?php echo $value['order_code']; ?>">
                                                    <input type="hidden" class="total" value="<?php echo $value['total']; ?>">
                                                    <input type="hidden" class="order_status" value="<?php echo $value['status']; ?>">
                                                    <input type="hidden" class="ticket_type" value="<?php echo $value['ticket_type']; ?>">
                                                    <input type="hidden" class="sales_person" value="<?php echo $value['name']; ?>">
                                                    <input type="hidden" class="ticket_person" value="<?php echo $total_ticket; ?>">
                                                    <input type="hidden" class="park_name" value="<?php echo $value['park_name']; ?>">
                                                    <input type="hidden" class="commission" value="<?php echo $value['sales_commission']; ?>">
                                                </div>
                                                <?php } ?>
                                                </p>

                                                <!--<p><?= "($".$value['price']." each)"?></p>-->
                                            </div>
                                        </div>
                                    </div>




                                    <div class='d-flex align-items-center justify-content-around' style="display: none !important;">
                                        <!--<div>-->
                                        <!--    <p style="font-size:25px"><?= $value['order_code'] ?></p>-->
                                        <!--</div>-->
                                        <div style="width:116px">
                                            <table class='table-borderless w-100'>
                                                <tbody>
                                                <tr>
                                                    <td class='p-0'>TOTAL</td>
                                                    <td class='p-0'>$<?= $value['total'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class='p-0'>DEPOSIT</td>
                                                    <!--<td class='p-0'>$<?= $totaldeposit ?></td>-->


                                                    <?php
                                                    $deposite = 0;
                                                    if($totaldeposit==0 || $totaldeposit>0){

                                                        $deposite = $totaldeposit;

                                                    }
                                                    else{
                                                        $deposite =0;
                                                    }
                                                    ?>
                                                    <td class='p-0'>$<?= (float)$deposite ?></td>
                                                </tr>
                                                <tr>
                                                    <td class='p-0'>BAL DUE</td>
                                                    <td class='text-danger p-0'>$<?= $value['total'] - $totaldeposit ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </td>

                                <td class='td-1 w-25' style="min-width: 315px;position:relative;height: 138px;vertical-align: middle;">
<!--                                    <div style=" position: absolute;left: 50%;top: 50%;transform: translate(-50%,-50%);">-->
                                        <div>
                                        <div class="text-center">
                                            <p style=" font-size: 22px;margin-bottom: 0px;"><?= $value['first_name']." ".$value['Last_name'] ?></p>
                                            <p style=" font-size: 20px;margin-bottom: 0px;"><?=  $telephone ?></p>
                                        </div>


                                        <?php
                                        $status_lock = "";
                                        if($value['status']==3 || $value['status'] == 11 || $value['status'] == 12){
                                            echo "";
                                            $status_lock = "disabled";
                                        }
                                        else{
                                            ?>
                                            <div class="mt-3 text-center" style="margin-top: 5px !important;">

<!--                                                <a href="/cheapthrills/Orders/text_order_details.php?order_id=--><?//= $value['order_code'] ?><!--" class="btn btn-success mr-2 mb-2">Send Confirmation Text</a>-->

                                                <button type="button" class="btn btn-warning payments-btn" data-toggle="modal" data-target="#payments-modal" data-id="<?= $value['order_code'] ?>" data-name="<?= $value['first_name']."".$value['Last_name'] ?>" data-order-id="<?php echo $value['order_id']; ?>" data-bal="<?= $value['total'] ?>" data-code="<?= $value['customer_code'] ?>">Payments</button>

                                                <a href='Addorders.php?id=<?php echo $value['order_customer_id']; ?>&order_id=<?= $value['order_id'] ?>' class="btn btn-info">Edit</a>

                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td class="w-25" style="min-width: 300px;">
                                    <?php
                                    $theme_park_id = $value['theme_park_id'];
                                    $getStatus = "SELECT DISTINCT t.id,s.* FROM `status` s, text_messages t where  t.status = s.status_ID and s.is_active =  '1' and t.theme_park_id = $theme_park_id order by s.status_order asc";
                                    $get_status_fire = mysqli_query($db, $getStatus);
                                    $order_text = '';
                                    $buttons = "";
                                    $disabled = "";

                                    if($totaldeposit!="" && $totaldeposit>0){

                                        $order_IDS = $value['order_id'];
//                                        $order_text = "<h4 class='text-center'>ORDER CONFIRMED  {$value['assign']}</h4>";
//                                        echo $value['assign'] . " < --- assign";
                                        if($value['status']==0){

//                                            $buttons = ' <div class="mt-3 text-center">
//                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assign Tickets</a>
//                                                        ';
                                        }
                                        else{
                                            if($value['assign']>=1) {
                                                $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id=' . $value["order_id"] . '" class="btn btn-warning btn-sm px-3" role="button"> Ticket Info</a>';

                                                if ($value['isdisabled'] == 0) {
                                                    $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';

                                                } else {
                                                    $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                                }

                                                if ($value['park_name'] == "Universal Orlando") {
                                                    $buttons .= ' <a href="text_history.php?order=' . $value["order_id"] . '" class="btn btn-primary btn-sm px-3" role="btn">Text Usage</a>                                                    ';
                                                }
                                                $buttons .= $logbtn . "</div>";
                                            }
                                            else{
                                                $buttons = ' <div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assign Tickets</a>
                                                        ';
                                            }

                                        }


                                    }
                                    else{


                                        if($_SESSION['level'] == 9 && $value['status'] == 8){
                                            $buttons = ' <div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assign Tickets</a>
                                                        ';

                                        }


                                        $order_text = '';
                                        $disabled = "disabled";
//                                        if($value['status'] == 0){
//                                        }
                                        // $buttons = '';
                                        // $order_text = "<h4 class='text-center'>ORDER CONFIRMED</h4>";

                                    }


                                    if($value['status'] == 0) {
                                        $buttons .= '<div class="d-flex align-items-center justify-content-around mt-3" style="margin-top: 7px !important;"><a href="/cheapthrills/Orders/text_order_details.php?order_id='.$value['order_code'].'" class="btn btn-success mr-2">Send Confirmation Text</a></div>';
                                    }
                                    if($value['status']==3 || $value['status'] == 11 || $value['status'] == 12){
                                        echo "";
                                        $buttons = "";
                                    }





                                    if (mysqli_num_rows($get_status_fire) > 0) {
                                        echo $order_text;
                                        if ($_SESSION['level'] == 9){
                                            $disabled = "";
                                            $status_lock = "";
                                        }
                                        echo "<select $disabled $status_lock class='custom-select' data-order='{$value['order_id']}' onchange='mark(this);'>";

                                        while ($status_m = mysqli_fetch_assoc($get_status_fire)) {
                                            echo "<option " . ($value['status'] == $status_m['status_ID']?'selected':'') . " value='" . $status_m['status_ID'] ."' >" . $status_m['status_name'] . "</option>";
                                        }
                                        echo "</select>";
                                        echo $buttons;

                                    }



                                    ?>
                                </td>

                            </tr>
                            <?php
                        }

                        ?>
                        </tbody>
                    </table>

                    <?php

                }

                ?>



            </div>

        </div>
    </div>

</div>
<?php
include('../includes/footer.php');
?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<script>


    $('.expense_type').change(function () {
        var theme_park_id = $(this).val();
        $.ajax({
            "url": "../queries/get_all_expense_set_link.php",
            "method": "POST",
            success: function (response) {
                console.log(response);
            }
        })
    })

    $('.order_table').each(function () {
        if($(this).find('tr:visible').length == 0){
            var date_obj = $(this).attr('data-date');
            $('.container_'+date_obj).remove();
        }
    })


    $('#typeOfPayment').change(function () {
        var reason = this.value;
        if(reason =='Refund'){
            $('.refund_reason').show();
        }
        else{
            $('.refund_reason').hide();
        }
    })
    $('.payment_form').submit(function () {
        if($('#paymentMethod').val() == ""){
            alert("Please select payment method");
            return false;
        }
        if($('#typeOfPayment').val() == ""){
            alert("Please select payment reason");
            return false;
        }

        if(!confirm('You are about to make a payment. This cannot be undone. Do you wish to proceed?')){
            return false;
        }
    })

    $(document).ready(function () {
        $('.daily_report').click(function(){
            var report_net_total = 0;
            var total_commission = 0;
            var net_profit_total = 0;
            $('.report_table > tbody').empty();
            var customer_array = [];
            var order_date = "";
            var order_id_array = [];
            $(this).closest('.mb-2').next().find('.report_data').each(function () {
                var status = $(this).find('.order_status').val();
                console.log(status);
                if (status == "11"){
                    return;
                }
                var customer_name = $(this).find('.customer_name').val();
                var order_id = $(this).find('.order_id').val();
                order_id_array.push("'"+order_id+"'");
                var total = $(this).find('.total').val();
                var formatted_date = $(this).find('.formatted_date').val();
                var ticket_type = $(this).find('.ticket_type').val();
                order_date = $(this).find('.order_date').val();
                var ticket_person = $(this).find('.ticket_person').val();
                var park_name =  $(this).find('.park_name').val();
                var sales_person = $(this).find('.sales_person').val();
                var commission = $(this).find('.commission').val();
                if (commission == "") { commission = "0" };
                var net_total = parseInt(total) - parseInt(commission);
                $('.report_date').text(formatted_date);
                $('.report_order_date').val(order_date);
                report_net_total += parseInt(total);
                total_commission += parseInt(commission);
                net_profit_total += parseInt(net_total);
                if (commission > 0) {
                    if(sales_person in customer_array){
                        customer_array[sales_person] += parseInt(commission);
                    }else{
                        customer_array[sales_person] = parseInt(commission);
                    }


                }
                $('.report_table tbody').append("<tr><td style='border-right: 0px !important;'>"+customer_name+"</td><td style='border-right: 0px !important;border-left: 0px !important;'>"+order_id +" </td><td style='border-right: 0px !important;border-left: 0px !important;'>"+ ticket_person +" "+ park_name +" " + ticket_type +"</td><td style='border-right: 0px !important;border-left: 0px !important;'>$"+total+"</td><td style='border-right: 0px !important;border-left: 0px !important;'>"+sales_person+"</td><td style='border-left: 0px !important;border-right: 0px !important;'>$"+commission+"</td> <td style='border-left: 0px !important;'>$"+ net_total +"<div style='' data-total='"+ net_total +"' class='refund_html_"+order_id+"'></div></td></tr>")

            })

            var sales_html = "";

            $('.commission_container').empty();
            for (customer in customer_array){
                var sales_name = customer;
                var commission = customer_array[customer];
                sales_html += '<div><span style="margin-right: 136px;">'+sales_name+'</span> <span>$'+commission+'</span></div>';
            }
            $('.refund_div').hide();
            var refunnd_total = 0;
            $.ajax({
                url: "../queries/get_all_expense.php",
                type: "POST",
                data: {date: order_date,order_id:order_id_array.join(",")},
                success: function (result) {
                    var expense_maount = 0;
                    var expense_html = "";
                    var refund_html = "";
                    var response = JSON.parse(result);
                    $.each(response['expense'], function (key,value) {
                        var expense_type = value.expense_type;
                        expense_maount += parseInt(value.amount);
                        expense_html += '<div><span style="margin-right: 46px;">'+expense_type+'</span> <span>$'+value.amount+'</span></div>';

                    })
                    $.each(response['refund'], function (key,value) {
                        var payment_amount = value.payment_amount;
                        var order_id = value.orderID;
                        refunnd_total += payment_amount;
                        var net_total = $('.refund_html_'+order_id).attr('data-total');
                        $('.refund_html_'+order_id).html("<div style='color: red;'>-$"+payment_amount+"</div><div>$"+(parseInt(net_total) - parseInt(payment_amount))+"</div>")

                        // refund_html += '<div><span style="margin-right: 112px;">'+order_id+'</span> <span>$'+payment_amount+'</span></div>';

                    })
                    // if(refund_html != ""){
                    //     $('.refund_div').show();
                    //     $('.refund_container').html(refund_html);
                    // }


                    $('.commission_container').html(sales_html);
                    if (sales_html == ""){
                        $('.commission_div').hide();
                    }
                    else{
                        $('.commission_div').show();
                    }
                    if (expense_html == ""){
                        $('.expense_div').hide();
                    }
                    else{
                        expense_html += '<div class="expense_total"><span style="margin-right: 65px;">Expense Total:</span> <span class="expense_total_amount">$'+expense_maount+'</span></div>';
                        $('.expense_div').show();
                    }

                    $('.expense_container').html(expense_html);

                    $('.report_net_total').text('$'+report_net_total);
                    $('.total_commission').text('$'+total_commission);
                    $('.net_profit_total').text('$'+(parseInt(net_profit_total) - parseInt(refunnd_total)));
                    $('.final_total').text('$'+ (parseInt(net_profit_total) - parseInt(expense_maount) - parseInt(refunnd_total)));
                    $('#report-modal').modal('show');
                }
            });


        })

    })




    $('.update_reporting').click(function () {

        var order_date = $('.report_order_date').val();
        var expense_type = $('.expense_type').val();
        var order_amount = $('.order_amount').val();


        if(expense_type == ""){
            alert("Please select expense type");
            return false;
        }


        if(order_amount == ""){
            alert("Please enter expense amount");
            return false;
        }

        swal.fire({
            //buttonsStyling: false,

            html: "Are you sure you want to add this expense? Once added will not be deleted.",
            type: "question",

            confirmButtonText: "Yes, add!",
            confirmButtonClass: "btn btn-sm btn-bold btn-danger-navigation-icon",

            showCancelButton: true,
            cancelButtonText: "No, cancel",
            cancelButtonClass: "btn btn-sm btn-bold btn-default",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return fetch('../queries/add_order_expense.php?order_date=' + order_date+'&expense_type='+expense_type+'&order_amount='+order_amount)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {

                swal.fire({
                    title: 'Added!',
                    text: "Added Successfully.",
                    type: 'success',
                    buttonsStyling: false,
                    confirmButtonText: "Okay",
                    confirmButtonClass: "btn btn-sm btn-bold btn-primary",
                }).then(function (result) {
                    var final_total = $('.final_total').text().replace('$','');
                    var expense_total = $('.expense_total_amount').text().replace('$','');
                    $('.final_total').text('$'+ (parseInt(final_total) - parseInt(order_amount)));
                    if($('.expense_div').css('display') == "none") {
                        $('.expense_container').append('<div><span style="margin-right: 46px;">' + expense_type + '</span> <span>$' + order_amount + '</span></div>' +
                            '<div class="expense_total"><span style="margin-right: 65px;">Expense Total:</span> <span class="expense_total_amount">$'+order_amount+'</span></div>');
                    }
                    else{
                        $('<div><span style="margin-right: 46px;">' + expense_type + '</span> <span>$' + order_amount + '</span></div>').insertBefore('.expense_total');
                        $('.expense_total_amount').text('$'+ (parseInt(expense_total) + parseInt(order_amount)));
                    }

                    $('.expense_div').show();
                    $('.expense_type').val('');
                    $('.order_amount').val('');

                })
            }
        })

    });


    var bal_due
    $('.payments-btn').click(function(){
        var customer_name = $(this).attr('data-name');
        var id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-code');
        var order_id = $(this).attr('data-order-id');
        bal_due = $(this).attr('data-bal');


        $('#payment-due-field').val(bal_due);
        $('#payment-order-id').val(order_id);
        $('#customer-name-field').val(customer_name);
        $('#customer-id-field').val(customer_id);
        $('.payment_history').attr('href','payment_history.php?order_id='+id);
        $('#order_id').val(id);
        $('.payment_container').empty();
        $('.payment_box').hide();
        var deposit = 0;
        var refund = 0;
        $.ajax({
            url: "../queries/get_all_payment.php?order_id="+id,
            success:function (response) {
                var data = JSON.parse(response);
                var payment_container = "";
                $.each(data,function (key, value) {
                    var refund_reason = "";
                    var payment_date = value.dateOfPayment;
                    var payment_type = value.typeOfPayment;
                    var user_name = value.user_name;
                    var payment_method = value.paymentMethod;
                    var payment_amount = value.paymentAmount;
                    if(payment_type == "Refund"){
                        payment_type = "<span style='color:red;'>Refund</span>";
                        refund += parseInt(payment_amount);
                        payment_amount = "-$"+payment_amount;
                        refund_reason = "("+value.refund_reason+")";
                    }
                    else{
                        deposit += parseInt(payment_amount);
                        payment_amount = "$"+payment_amount;
                    }

                    payment_container += "<div>"+payment_amount+" "+payment_method+" "+payment_type+" "+payment_date+" "+user_name+" "+refund_reason+"</div>";

                });
                if(payment_container != ""){
                    $('.payment_box').show();
                }
                $('.payment_container').html(payment_container);

                console.log(bal_due + " " + deposit + " " + refund);
                var due_total = bal_due - deposit + refund;
                $('#bal-due-field').html("$"+(bal_due - deposit + refund));

                $('#payment-due-field').val(bal_due - deposit + refund);


            },
            type: "GET"
        })
        // calculate_due();


    })

    function calculate_due() {
        var deposit = document.getElementById('deposit').value;
        var due_bal = bal_due;
        var due_total = due_bal - deposit;
        $('#bal-due-field').html("$"+due_total);
        console.log("DUE::", due_total);
    }



    $('#orderid').change(function () {
        $('#upcoming_search').submit();
    });

    function serach_orders(){
        $('#upcoming_search').submit();
    }

    function mark(el){
        let status = el.value;
        let order = $(el).data('order');

        $.ajax({
            url: '../Orders/update_status.php?mark='+ status +'&order_id=' + order,
            success: function(res) {
                console.log(res);
                //console.log(order, " marked as ", status);
                // location.reload();
                window.location.reload(true);

            }
        });

    }


    function logutUser(guest_id,order_id,phone_number){
        $.ajax({
            url:'../Ajax/DisableUserUpcomingOrder.php',
            method:'post',
            data:{guest_id:guest_id,order_id:order_id,phone_number:phone_number},
            success:function(data){
                $.ajax({
                    url:"../Assign/Addassign.php?id=" + order_id + "",
                    method:'get',
                    success:function(data){
                        location.reload();
                    }
                })
            }
        })
    }
</script>