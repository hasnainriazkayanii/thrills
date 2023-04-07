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

$last_3_days_check = "";
if (@$_SESSION['level'] != 9){
    $last_3_days_check = " and order.date_of_visit >= DATE_SUB( CURDATE(), INTERVAL 7 DAY)";
}

if(isset($_POST['search_name']) && isset($_POST['activedata']) ){
    if($_POST['search_name']=="" && $_POST['activedata']==2){
        $upcoming_table_title ='Past Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name,tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
                LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn
                WHERE order.date_of_visit<='$current_date' $last_3_days_check $ishide group by order.order_id ORDER BY order.date_of_visit Desc,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
        // var_dump($sql)
    }
    else if($_POST['search_name']=="" && $_POST['activedata']==1){
        $upcoming_table_title = 'Active Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID  Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
               LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn 
               WHERE order.date_of_visit>='$current_date' $ishide AND  order.status not in (3) $ishide group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else if($_POST['search_name']!="" && $_POST['activedata']==0){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID 
        LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn
        WHERE order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%'  $ishide  group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else if($_POST['search_name']!="" && $_POST['activedata']==1){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
        LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn
        WHERE order.date_of_visit>='$secrch_Date' AND (order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%')  $ishide  AND order.status not in (3) group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";


    }
    else if($_POST['search_name']!="" && $_POST['activedata']==2){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID
        LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn
        WHERE (order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%'  $ishide  AND order.status in (3,11,12)) $last_3_days_check group by order.order_id ORDER BY order.date_of_visit DESC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
//  var_dump($sql);
    }
    else if(isset($_GET['active']) && $_GET['active']=="3"){
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID 
        LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn
        WHERE order.date_of_visit>=$current_date $last_3_days_check $ishide AND order.status  not in (3) group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else{

        // Fetch upcoming orders
        $sql = "SELECT *,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID 
       LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn
        WHERE order.date_of_visit>=$current_date $ishide AND order.status not in (3) group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
}
else{

    // Fetch upcoming orders
    $sql = "SELECT *, customer.country_code as customer_country_code ,(SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,order.customer_id as order_customer_id,order.customer_id as order_customer_id,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, tickettypes.image as ticket_image ,customer.Phone_number, order.customer_id as customer_code, accounting.orderID, SUM(accounting.paymentAmount) as sum_amount FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id LEFT JOIN `accounting` ON order.order_id=accounting.orderID Left JOIN guest as g ON order.id= g.order_id LEFT JOIN partners pa ON pa.id = order.sales_personID  
    LEFT JOIN `tickettypes` ON tickettypes.id = order.AddOn
    WHERE order.date_of_visit>=$current_date $ishide  group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
}

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
                <h4 class="modal-title">Daily Summary</h4>
                <h4 class="report_date"></h4>
                <button type="button" class="close ml-0" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div style="overflow-x:auto;">
                <table class="table table-bordered report_table" id="dataTable" style="border: 0px !important;width:1210px !important;" cellspacing="0">
                    <thead style="padding: 0px;">
                        <th style="border-right: 0px !important;">Customer</th>
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
                    <div class="col-md-7">
                        <div class="commission_div">
                        <span class="" style="text-decoration: underline;font-size: 18px;">Commission Payouts</span>
                        <table class="commission_container" style="margin-bottom: 20px;width: 100%">
                        </table>
                        </div>
                        <span class="expense_div" style="text-decoration: underline;font-size: 18px;margin-top: 10px;">Expenses</span>
                        <table class="expense_container" style="margin-bottom: 20px;width: 100%"></table>
                        <div class="manager_div">
                            <span class="" style="text-decoration: underline;font-size: 18px;">Manager Payouts</span>
                            <table class="manager_container" style="margin-bottom: 20px;width: 100%">
                            </table>
                        </div>

                        <div class="refund_div">
                            <span class="" style="text-decoration: underline;font-size: 18px;">Refund</span>
                            <div class="refund_container" style="margin-bottom: 20px;"><div><span style="margin-right: 136px;">Mike</span> <span>$10</span></div></div>
                        </div>

                    </div>
                    <div class="col-md-5">
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

                </div>
                <?php if (@$_SESSION['level'] == 9) {?>
                <div class="row">
                    <div class="col-md-7">
                    </div>
                    <div class="col-md-5" style="border-top: 1px solid;padding-top: 8px;margin-top: 6px;">
                        <h4>Manager Payouts:</h4>
                        <table style="width: 100%;">
                            <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="manager_amount" style="width: 66px;border: 2px solid;" name="manager_amount" placeholder="Amount">

                                    <select class="manager_sale" style="width: 152:px;">
                                        <option value="">Name</option>
                                        <?php

                                        $sales_persons_query = "SELECT * FROM partners where active = 1 ";
                                        $sales_persons_result = mysqli_query($db, $sales_persons_query);
                                        while ($row_sales = mysqli_fetch_assoc($sales_persons_result)){
                                            $sales_id = $row_sales['id'];
                                            $sales_name = $row_sales['name'];
                                            echo "<option value='$sales_id'>$sales_name</option>";
                                        }

                                        ?>
                                    </select>

                                    <select class="manager_type" style="width: 152:px;">
                                        <option value="">Source</option>
                                        <option>Zelle</option>
                                        <option>Web Deposit</option>
                                        <option>CashApp</option>
                                        <option>Venmo</option>
                                        <option>Cash</option>
                                    </select>

                                </td>
                                <td>
                                    <button class="btn btn-warning add_manager_payout" style="padding: 1px;margin-left: 8px;">Save</button>
                                </td>
                            </tr>
                            </tbody></table>
                    </div>

                </div>
                <?php } ?>
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
                          <input type="checkbox" name="sendText" value="1" checked="checked">
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



                <form method="post" action="Orderdetails.php?active3" id="upcoming_search" >
                    <div class="d-flex align-items-center" style="flex-wrap:wrap">
                        <div class="d-flex flex-wrap-sm" style="margin-right: 10px;align-items: center;">
                            <input type="text" class="form-control" name='search_name' placeholder="Search for" aria-label="Search"  style="min-width:100px">

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
                        <?php
                        if ($_SESSION['level'] >=2) {?>
                            <p class="m-0" style="text-align: right;width:44%;"><button class="btn btn-success daily_report" type="button">Daily Closeout Report</button> </p>
                        
                        <?php } ?>
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
//                                          continue;
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
//                                if($value['customer_country_code']=='+1'){
//                                    $country_code ='';
//                                }
//                                else{
                                    $country_code = $value['customer_country_code'];
//                                }
                                $telephone =  $country_code . $result19 ;
                            }else if($level >= 2){
                        
                     $result16 = mb_substr($value["Phone_number"], 0, 3);
                                $result17 = mb_substr($value["Phone_number"], 3, 3);
                                $result18 = mb_substr($value["Phone_number"], 6, 4);
                                $result19 = "(" . $result16 . ") " . $result17 . "-" . $result18;
//                                if($value['customer_country_code']=='+1'){
//                                    $country_code ='';
//                                }
//                                else{
                                    $country_code = $value['customer_country_code'];
//                                }
                                $telephone =  $country_code . $result19 ;
                    }else{
                      $telephone = "<td>(***) ***-" . substr($row1["Phone_number"], strlen($row1["Phone_number"]) - 4)."</td>";
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
                                                <?php if(isset($value['ticket_image']) && !empty($value['ticket_image'])){ 
                                                    $theImage = "../".$value['ticket_image'];?>
                                                    <img src="<?php echo  $theImage ?>" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                <?php } ?>
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
                                                    <input type="hidden" class="formatted_date" value="<?php echo date("D m/d/y", strtotime($title)); ?>">
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
                                    <div>
                                        <div class="text-center">
                                            <?php if($_SESSION['level'] == 9){ ?>
                                                 <p style=" font-size: 22px;margin-bottom: 0px;"><a href="order_activity_report.php?order_id=<?=$value['order_id']?>" target="_blank"><?= $value['first_name']." ".$value['Last_name'] ?></a></p>
                                          <?php  }
                                            else{ ?>
                                            <p style=" font-size: 22px;margin-bottom: 0px;"><?= $value['first_name']." ".$value['Last_name'] ?></p>
                                            <?php } ?>
                                            <?php
                                                if($_SESSION['level']>=2){
                                            ?>
                                            <p style=" font-size: 20px;margin-bottom: 0px;"><?=  $telephone ?></p>

                                        <?php }?>

                                        </div>


                                        <?php
                                        $status_lock = "";
                                        if( ($value['status']!=3 && $value['status'] != 11 && $value['status'] != 12 ) || $_SESSION['level'] >= 2 ){
                                            ?>
                                            <div class="mt-3 text-center" style="margin-top: 5px !important;">

                                          

                                                <button type="button" class="btn btn-warning payments-btn" data-toggle="modal" data-target="#payments-modal" data-id="<?= $value['order_code'] ?>" data-name="<?= $value['first_name']."".$value['Last_name'] ?>" data-order-id="<?php echo $value['order_id']; ?>" data-bal="<?= $value['total'] ?>" data-code="<?= $value['customer_code'] ?>">Payments</button>
                                                <?php
                                                if($_SESSION['level']>=2){
                                            ?>
                                                <a href='Addorders.php?id=<?php echo $value['order_customer_id']; ?>&order_id=<?= $value['order_id'] ?>' class="btn btn-info">Edit</a>
                                            <?php }?>
                                            </div>

                                        <?php }
                                        else{
                                            echo "";
                                            $status_lock = "disabled";
                                        }
                                        ?>
                                    </div>
                                </td>
                                <?php
                                if($_SESSION['level']>1){
                                            ?>
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

                                        if($value['status'] != 3){
                                            if($value['assign']>=1) {
                                                $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id=' . $value["order_id"] . '" class="btn btn-warning btn-sm px-3" role="button"> Ticket Info</a>';

                                                if ($value['isdisabled'] == 0) {
                                                    $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';

                                                } else {
                                                    $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                                }
                                                if ($value['theme_park_parent_id'] == "1") {
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

                                        $order_text = '';
                                        $disabled = "disabled";

                                    }


                                    if($_SESSION['level'] >1 && $value['status'] == 8 && $buttons == ""){
                                        if ($result = mysqli_query($db,"SELECT * FROM `guest` WHERE order_id = '".$value["order_id"]."'")) {

                                            // Return the number of rows in result set
                                            $rowcount = mysqli_num_rows( $result );
                                            if($rowcount >0){
                                                $buttons = ' <div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-warning btn-sm payments-btn" role="button"> Ticket Info</a>
                                                        ';

                                                if ($value['isdisabled'] == 0) {
                                                    $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';

                                                } else {
                                                    $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                                }
                                                if ($value['theme_park_parent_id'] == "1") {
                                                    $buttons .= ' <a href="text_history.php?order=' . $value["order_id"] . '" class="btn btn-primary btn-sm px-3" role="btn">Text Usage</a>                                                    ';
                                                }
                                                $buttons .= $logbtn;
                                            }else{
                                                $buttons = ' <div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm payments-btn" role="button"> Assign Ticket</a>
                                                        ';
                                            }
                                        }

                                        $buttons .= "</div>";
                                    }
                                    else if ($_SESSION['level'] >1 && $value['status'] == 3){
                                        $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id=' . $value["order_id"] . '" class="btn btn-warning btn-sm" role="button"> Ticket Info</a>';
                                           if ($_SESSION['level'] == 9){              
                                        $buttons .= '<a href="../History/AddHistory.php?order_id=' . $value["order_id"] . '&tab=usage" class="btn btn-info btn-sm ml-1" role="button"> Update Usage</a>';
                                        $buttons .= "</div>";
                                               
                                           }
                                    }else if ($_SESSION['level'] > 1 && ($value['status'] == 4 || $value['status'] == 5 || $value['status'] == 6)){
                                        $buttons = ' <div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-warning btn-sm payments-btn" role="button"> Ticket Info</a>
                                                        ';

                                        $buttons .= ' <a href="text_history.php?order=' . $value["order_id"] . '" class="btn btn-primary btn-sm px-3" role="btn">Text Usage</a>                                                    ';
                                        if ($value['isdisabled'] == 0) {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';

                                        } else {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                        }

                                        $buttons .= $logbtn."</div>";
                                    }else if($_SESSION['level'] >1 && ($value['status'] == 2 || $value['status'] == 15)  ){
                                        $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id=' . $value["order_id"] . '" class="btn btn-warning btn-sm" role="button"> Ticket Info</a>';
                                        if ($_SESSION['level'] == 9){
                                        $buttons .= '<a href="../History/AddHistory.php?order_id=' . $value["order_id"] . '&tab=usage" class="btn btn-info btn-sm  ml-1" role="button"> Update Usage</a>';
                                            
                                        }
                                        $space='';
                                        if ($value['isdisabled'] == 0) {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';

                                        } else {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                        }

                                        $buttons .= $logbtn."</div>";
                                    }else if($_SESSION['level'] >1 && $value['status'] == 7){
                                        $buttons = '<div class="mt-3 text-center"> <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-warning btn-sm payments-btn ml-1" role="button"> Ticket Info</a>
                                                        ';
                                        if ($value['isdisabled'] == 0) {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';

                                        } else {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                        }

                                        $buttons .= $logbtn."</div>";
                                    }else if($_SESSION['level'] >1 && $value['status'] == 1){
                                        $buttons = ' <div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-warning btn-sm payments-btn" role="button"> Ticket Info</a>
                                                        ';

                                        $buttons .= ' <a href="text_history.php?order=' . $value["order_id"] . '" class="btn btn-primary btn-sm px-3" role="btn">Text Usage</a>                                                    ';
                                        if ($value['isdisabled'] == 0) {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';

                                        } else {
                                            $logbtn = ' <button onclick="logutUser(' . $value["guest_id"] . ',' . $value["order_id"] . ',' . $value["Phone_number"] . ')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                        }

                                        $buttons .= $logbtn."</div>";
                                    }



                                    if($value['status'] == 0) {
                                        $buttons .= '<div class="d-flex align-items-center justify-content-around mt-3" style="margin-top: 7px !important;"><a href="/cheapthrills/Orders/text_order_details.php?order_id='.$value['order_code'].'" class="btn btn-success mr-2">Send Confirmation Text</a></div>';
                                    }
                                    if(($value['status']==3 || $value['status'] == 11 || $value['status'] == 12) && $_SESSION['level'] != 9){
                                        echo "";
                                        $buttons = "";
                                    }





                                    if (mysqli_num_rows($get_status_fire) > 0) {
                                        echo $order_text;
                                        if ($_SESSION['level'] == 9){
                                            $disabled = "";
                                            $status_lock = "";
                                        }
                                        
                                        $statutypeval=$value['status'];
                                        $disableselect='';
                                         if ($_SESSION['level'] < 9&& ($statutypeval==2 || $statutypeval==3 || $statutypeval==15 || $statutypeval==11)){
                                             
                                             $disableselect='disabled';
                                         }
                                        
                                        echo "<select  $disableselect $disabled $status_lock class='custom-select' data-order='{$value['order_id']}' onchange='mark(this); '>";
                                             $sql_group = "SELECT * FROM tickettypes WHERE id='$ticket_type_id' LIMIT 1";

                                            

                                            $result_t = mysqli_query($db, $sql_group);
                                           $ticket_type_stat=  mysqli_fetch_assoc($result_t) ;
                                           print_r ($statutypeval);
                                        while ($status_m = mysqli_fetch_assoc($get_status_fire)) {
                                            if ($_SESSION['level'] != 9 && $status_m['status_ID'] == 11){
                                                continue;
                                            }
                                            //check for the role for options
                                             if ($_SESSION['level'] != 9 && ($status_m['status_ID'] != 3&& $status_m['status_ID'] !=5&&$status_m['status_ID'] != 9&&$status_m['status_ID'] != 12&&$status_m['status_ID'] != 11&&$status_m['status_ID'] != 14)){
                                               if($ticket_type_stat['ticket_type'] =='base'){
                                                    if($status_m['status_ID']!=7 && $status_m['status_ID']!=15 ){
                                               
                                                        echo "<option " . ($value['status'] == $status_m['status_ID']?'selected':'') . " value='" . $status_m['status_ID'] ."' >" . $status_m['status_name'] . "</option>";
                                             
                                                    }
                                                 }
                                                 
                                                 else{
                                                       if($status_m['status_ID']!=2 ){
                                                            echo "<option " . ($value['status'] == $status_m['status_ID']?'selected':'') . " value='" . $status_m['status_ID'] ."' >" . $status_m['status_name'] . "</option>";
                                                       }
                                                 }
                                             }
                                             
                                             else{
                                                 
                                                  if($ticket_type_stat['ticket_type'] =='base'){
                                                    if($status_m['status_ID']!=7 && $status_m['status_ID']!=15 ){
                                               
                                                        echo "<option " . ($value['status'] == $status_m['status_ID']?'selected':'') . " value='" . $status_m['status_ID'] ."' >" . $status_m['status_name'] . "</option>";
                                             
                                                    }
                                                 }
                                                 
                                                 else{
                                                       if($status_m['status_ID']!=2 ){
                                                            echo "<option " . ($value['status'] == $status_m['status_ID']?'selected':'') . " value='" . $status_m['status_ID'] ."' >" . $status_m['status_name'] . "</option>";
                                                       }
                                                 }
                                             }
                                                 
                                             }
                                        echo "</select>";
                                        echo $buttons;

                                    }



                                    ?>
                                    </td>

                                <?php }?>

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
//include('../includes/footer.php');
?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<script>


    $('.expense_type').change(function () {
        var theme_park_id = $(this).val();
        $('.expense_setlink').prop('disabled',true);
        $('.expense_setlink').empty().append("<option value=''>Select</option>");
        $.ajax({
            "url": "../queries/get_all_expense_set_link.php",
            data: {theme_park_id:theme_park_id},
            "method": "POST",
            success: function (response) {
                $.each(JSON.parse(response), function (key,value) {
                    $('.expense_setlink').append("<option>"+value.set_link+"</option>");
                })
                $('.expense_setlink').prop('disabled',false);
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

        $('.add_manager_payout').click(function () {
            var manager_amount = $('.manager_amount').val();
            var manager_sales = $('.manager_sale').val();
            var manager_type = $('.manager_type').val();
            var manager_sales_name = $('.manager_sale option:selected').text();
            var order_date = $('.report_order_date').val();
            if(manager_amount == ""){
                alert("Please Add amount");
                return false;
            }
            if(manager_sales == ""){
                alert("Please select Name");
                return false;
            }

            if(manager_type == ""){
                alert("Please select Source");
                return false;
            }

            $.ajax({
                url: "../queries/add_manager_payout.php",
                type: "POST",
                data: {manager_amount: manager_amount, manager_sales:manager_sales,manager_type:manager_type,order_date:order_date},
                success: function (result) {
                    var d = new Date();
                    var strDate = (d.getMonth()+1) + "/" + d.getDate();
                    $('.manager_container').append('<tr><td style="width: 600px">'+manager_sales_name+' '+strDate+'</td> <td>$'+manager_amount+'</td></tr>')
                    $('.manager_div').show();
                    $('.manager_amount,.manager_type,.manager_sale').val('');
                }
            });
        })

        $('.daily_report').click(function(){
            var report_net_total = 0;
            var total_commission = 0;
            var net_profit_total = 0;
            $('.report_table > tbody').empty();
            var customer_array = [];
            var manager_payout_array = [];
            var order_date = "";
            var order_id_array = [];
            $(this).closest('.mb-2').next().find('.report_data').each(function () {
                var status = $(this).find('.order_status').val();

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
                $('.report_table tbody').append("<tr><td style='border-right: 0px !important;'>"+customer_name+"</td><td style='border-right: 0px !important;border-left: 0px !important;'>"+order_id +" </td><td style='border-right: 0px !important;border-left: 0px !important;'>"+ ticket_person +" "+ park_name +" " + ticket_type +"</td><td style='border-right: 0px !important;border-left: 0px !important;'>$"+total+"</td><td style='border-right: 0px !important;border-left: 0px !important;'>"+sales_person+"</td><td style='border-left: 0px !important;border-right: 0px !important;'>$"+commission+"</td> <td style='border-left: 0px !important;'>$"+ net_total +"<div style='' data-total='"+ net_total +"' class='refund_html_"+order_id+"'></div></td></tr>")

            })

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
                        var set_link = value.set_link;
                        expense_maount += parseInt(value.amount);
                        expense_html += '<tr><td style="width: 600px">'+expense_type+'('+set_link+')</td> <td>$'+value.amount+'</td></tr>';

                    })
                    $.each(response['refund'], function (key,value) {
                        var payment_amount = value.payment_amount;
                        var order_id = value.orderID;
                        refunnd_total += payment_amount;
                        var net_total = $('.refund_html_'+order_id).attr('data-total');
                        $('.refund_html_'+order_id).html("<div style='color: red;'>-$"+payment_amount+"</div><div>$"+(parseInt(net_total) - parseInt(payment_amount))+"</div>")
                        // refund_html += '<div><span style="margin-right: 112px;">'+order_id+'</span> <span>$'+payment_amount+'</span></div>';
                    })
                    $.each(response['payout'], function (key,value) {
                        var payout_amonut = value.payoutTotal;
                        var payout_sales = value.sales_name;
                        var is_manager_payout = value.is_manager_payout;
                        if(is_manager_payout == '1'){
                            if (payout_sales in manager_payout_array) {
                                manager_payout_array[payout_sales] += parseInt(payout_amonut);
                            } else {
                                manager_payout_array[payout_sales] = parseInt(payout_amonut);
                            }

                        }
                        else {
                            if(payout_amonut != 0 && payout_amonut != "") {
                                if (payout_sales in customer_array) {
                                    customer_array[payout_sales] += parseInt(payout_amonut);
                                } else {
                                    customer_array[payout_sales] = parseInt(payout_amonut);
                                }
                            }
                        }


                    })

                    var sales_html = "";
                    var manager_payout_html = "";

                    $('.commission_container').empty();
                    for (customer in customer_array){
                        var sales_name = customer;
                        var commission = customer_array[customer];
                        sales_html += '<tr><td style="width: 600px">'+sales_name+'</td> <td>$'+commission+'</td></tr>';
                    }

                    $('.manager_container').empty();
                    console.log(manager_payout_array);
                    for (manager_payout in manager_payout_array){
                        var sales_name = manager_payout;
                        var commission = manager_payout_array[manager_payout];
                        manager_payout_html += '<tr><td style="width: 600px">'+sales_name+'</td> <td>$'+commission+'</td></tr>';
                    }




                    // if(refund_html != ""){
                    //     $('.refund_div').show();
                    //     $('.refund_container').html(refund_html);
                    // }


                    $('.commission_container').html(sales_html);
                    $('.manager_container').html(manager_payout_html);
                    if (sales_html == ""){
                        $('.commission_div').hide();
                    }
                    else{
                        $('.commission_div').show();
                    }
                    if (manager_payout_html == ""){
                        $('.manager_div').hide();
                    }
                    else{
                        $('.manager_div').show();
                    }

                    if (expense_html == ""){
                        $('.expense_div').hide();
                    }
                    else{
                        expense_html += '<tr class="expense_total"><td>Expense Total:</td> <td class="expense_total_amount">$'+expense_maount+'</td></tr>';
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
        var expense_type = $('.expense_type option:selected').text();
        var order_amount = $('.order_amount').val();
        var set_link = $('.expense_setlink').val();

console.log(expense_type);
        if(expense_type == ""){
            alert("Please select expense type");
            return false;
        }


        if(order_amount == ""){
            alert("Please enter expense amount");
            return false;
        }

        if(set_link == ""){
            alert("Please select set link");
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
                return fetch('../queries/add_order_expense.php?order_date=' + order_date+'&expense_type='+expense_type+'&order_amount='+order_amount+"&set_link="+set_link)
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
                        $('.expense_container').append('<tr><td style="width: 600px">' + expense_type + '('+set_link+')</td> <td>$' + order_amount + '</td></tr>' +
                            '<tr class="expense_total"><td>Expense Total:</td> <td class="expense_total_amount">$'+order_amount+'</td></tr>');
                    }
                    else{
                        $('<div><span style="margin-right: 46px;">' + expense_type + '('+set_link+')</span> <span>$' + order_amount + '</span></div>').insertBefore('.expense_total');
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
                    var smslink =`<a href="text_payment_details.php?added_order=true&order_id=${value.orderID}&account_id=${value.id}">Send Receipt</a>`;
                    payment_container += "<div>"+payment_amount+" "+payment_method+" "+payment_type+" "+payment_date+" "+user_name+" "+refund_reason+" "+smslink+"</div>";

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
        if(status==7){
            
        $.ajax({
            url: '../Orders/update_status.php?mark='+ 2 +'&order_id=' + order,
            success: function(res) {
                console.log(res);
                //console.log(order, " marked as ", status);
                // location.reload();
                window.location.reload(true);

            }
        });
            
        }
      
        $.ajax({
            url: '../Orders/update_status.php?mark='+ status +'&order_id=' + order,
            success: function(res) {
                console.log(res);
                //console.log(order, " marked as ", status);
                // location.reload();
                window.location.reload(true);

            },
            beforeSend: function() {
                $(el).attr('disabled','disabled');
            },
            complete: function() {
                $(el).removeAttr('disabled');
            },
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