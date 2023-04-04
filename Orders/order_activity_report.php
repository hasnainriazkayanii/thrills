<?php

include('../Config/Connection.php');

session_start();
$login_check = $_SESSION['id'];

$level = $_SESSION['level'];
$data=[];
if ($login_check != '1') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $full_url = $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION['intended_url'] = $full_url;
    header("location: ../Login/login.php");
}

include('../includes/header.php');
if(isset($_GET['order_id']) && !empty($_GET['order_id'])){
    $order_id = $_GET['order_id'];
    $sql = "SELECT *, 
    customer.country_code as customer_country_code ,
    (SELECT SUM(paymentAmount) from accounting where orderID = `order`.order_id) as amount_sum,
    order.customer_id as order_customer_id,
    order.customer_id as order_customer_id,
    g.id as guest_id, 
    order.id as order_id, 
    status.status_name,
    order.order_id as order_code, 
    theme_parks.id as theme_park_id, 
    theme_parks.name AS park_name, 
    tickettypes.image as ticket_image ,
    customer.Phone_number, 
    order.customer_id as customer_code, 
    accounting.orderID, 
    SUM(accounting.paymentAmount) as sum_amount 
    FROM `order` 
    INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id 
    INNER JOIN `status` ON status.Status_Id=order.status 
    INNER JOIN `customer` ON order.customer_id=customer.id 
    LEFT JOIN `accounting` ON order.order_id=accounting.orderID 
    Left JOIN `guest` as g ON order.id= g.order_id 
    LEFT JOIN `partners` pa ON pa.id = order.sales_personID  
    LEFT JOIN `tickettypes` ON tickettypes.id = order.ticket_type_id
    WHERE order.id=$order_id  group by order.order_id";
    $result = mysqli_query($db,$sql);
    if (mysqli_num_rows($result) > 0) {
        $data['order'] = mysqli_fetch_assoc($result);
        $CusomerActivityQuery = " SELECT timestamps.* ,
         login_user.user_name
        from timestamps
        LEFT JOIN `login_user` ON login_user.id  = timestamps.action_by 
        where type='Customer' 
        and object_id=".$data['order']['customer_code'].
        " ORDER BY date_time ASC";
        $customerActivityResult = mysqli_query($db,$CusomerActivityQuery);
        if (mysqli_num_rows($customerActivityResult) > 0) {
            $data['order']['customer_activity'] =  mysqli_fetch_all($customerActivityResult, MYSQLI_ASSOC);
        }
        $OrderActivityQuery = " SELECT timestamps.*, 
        login_user.user_name 
        from timestamps 
        LEFT JOIN `login_user` ON login_user.id  = timestamps.action_by 
        where timestamps.type='Order'
        and timestamps.object_id=".$order_id.
        " ORDER BY timestamps.date_time ASC";
        //  echo $OrderActivityQuery;exit;
        $orderActivityResult = mysqli_query($db,$OrderActivityQuery);
        if (mysqli_num_rows($orderActivityResult) > 0) {
            $data['order']['order_activity'] = mysqli_fetch_all($orderActivityResult, MYSQLI_ASSOC);
        }
        $StatusActivityQuery = "SELECT status.status_name, 
        timestamps.date_time,
        timestamps.object_id,
        login_user.user_name
        from `timestamps`
        JOIN `status` ON status.Status_ID  = timestamps.object_id 
        LEFT JOIN `login_user` ON login_user.id  = timestamps.action_by 
        JOIN `order` ON order.id  = timestamps.order_id 
        where timestamps.type='Order Status' and timestamps.order_id=".$order_id." ORDER BY date_time ASC";
        $statusActivityResult = mysqli_query($db,$StatusActivityQuery);
        if (mysqli_num_rows($statusActivityResult) > 0) {
            $data['order']['status_activity'] = mysqli_fetch_all($statusActivityResult, MYSQLI_ASSOC);
        }
        // echo '<pre>',print_r($data);exit;
    }

    
}
else{
    echo "Order Not found";
}

?>
<div id="content-wrapper">

    <div class="container">
        <h1 class="text-center">Order Report</h1>
        <?php if($data && isset($data['order'])){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Customer Details</h5>
                    </div>
                    <div class="card-body">

                        <p>Name: <?=$data['order']['customer']?></p>
                        <p>Phone: <?=$data['order']['Phone_number']?></p>
                    </div>
                </div>


            </div>
          
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="float-right"> <?php $image  = "../".$data['order']['image']; ?>
                <img src="<?=$image?>" alt="" style="width: 170px;margin: 0 auto;display: flex;"></div>
                        <p>Order No: <b><?=$data['order']['order_code']?></b></p>
                        <p>Date Time: 
                        <?=date('m/d/Y',strtotime($data['order']['date_of_visit'])).' '.$data['order']['time']?></p>
                        <p>Adults: <?=$data['order']['adults']?></p>
                        <p>Kids: <?=$data['order']['kids']?></p>
                        <p>Total Amount: <?=$data['order']['total']?></p>
                        <p>Status: <?=$data['order']['status_name']?></p>
                    </div>
                </div>


            </div>
          
        </div>

        <!-- <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Order Details</h2>
                <?php $image  = "../".$data['order']['image']; ?>
                <img src="<?=$image?>" alt="" style="width: 10%;margin: 0 auto;display: flex;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Date Time</th>
                            <th>Adults</th>
                            <th>Order Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> <?=$data['order']['orderID']?></td>
                            <td>
                                <?=$data['order']['date_of_visit']?>:<?=$data['order']['time']?></p>
                            </td>
                            <td> <?=$data['order']['adults']?></td>
                            <td><?=$data['order']['total']?></td>
                            <td><?=$data['order']['status_name']?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->

        <!-- Customer Activity -->
        <div class="row mt-3">
            <div class="col-md-12">
                <h5>Customer Activity</h5>
                <ul class="list-group">
                <?php if(isset($data['order']['customer_activity'])) { 
                    foreach($data['order']['customer_activity'] as $ca){
                        $datetime = new DateTime($ca['date_time']);
                        $formatted_time = $datetime->format('m/d/Y h:i A');
                        $formatted_time = date('m/d/Y h:i A',strtotime('+1 hour',strtotime($formatted_time)));
                       
                        ?>
                        <li class="list-group-item">Customer <?=$ca['action']?> - <?=$formatted_time?>  - <?=$ca['user_name']?></li>
                    <?php } } else{?>
                        <li class="list-group-item">No Activity Track</li>
                <?php } ?>
                </ul>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h5>Order Activity</h5>
                <ul class="list-group">
                <?php if(isset($data['order']['order_activity'])) { 
                    foreach($data['order']['order_activity'] as $ca){
                        $datetime = new DateTime($ca['date_time']);
                        $formatted_time = $datetime->format('m/d/Y h:i A');
                        $formatted_time = date('m/d/Y h:i A',strtotime('+1 hour',strtotime($formatted_time)));?>
                        <li class="list-group-item">Order <?=$ca['action']?> - <?=$formatted_time?>  - <?=$ca['user_name']?></li>
                    <?php } } else{?>
                        <li class="list-group-item">No Activity Track</li>
                <?php } ?>
                </ul>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h5>Order Status</h5>
                <ul class="list-group">
                <?php if(isset($data['order']['status_activity'])) { 
                    foreach($data['order']['status_activity'] as $ca){
                        $datetime = new DateTime($ca['date_time']);
                        $formatted_time = $datetime->format('m/d/Y h:i A');
                        $formatted_time = date('m/d/Y h:i A',strtotime('+1 hour',strtotime($formatted_time)));
                        $statusArray = array(2,4,6,7,15);
                        $statusArrayP1 = array(2,4,6);
                        $statusArrayP2 = array(7,15);
                        $parkName ='';
                        if(in_array($ca['object_id'],$statusArray)){
                            if($data['order']['ticket_type']=='ptp'){
                                if(in_array($ca['object_id'],$statusArrayP1)){
                                    $ticket_parts = explode("&", $data['order']['ticket_name']);
                                    if(isset($ticket_parts[0])){
                                        $parkName =' - '.$ticket_parts[0];
                                    }
                                }
                                else{
                                    $ticket_parts = explode("&", $data['order']['ticket_name']);
                                    if(isset($ticket_parts[1])){
                                        $Park2Name = trim($ticket_parts[1]);
                                        $exploded = explode(' ',$Park2Name);
                                        if(isset($exploded[0])){
                                            $parkName = ' - '.$exploded[0];
                                        }
                                    }
                                }
                            }
                            else{
                                $parkName = ' - '.$data['order']['park_name'];
                            }
                        }
                        ?>
                        <li class="list-group-item">Order Status Changed to - <?=$ca['status_name']?> <?=$parkName?> - <?=$formatted_time?> - <?=$ca['user_name']?></li>
                    <?php } } else{?>
                        <li class="list-group-item">No Activity Track</li>
                <?php } ?>
                </ul>
            </div>
        </div>
        <?php } else{?>
        <div class="col-md-12 text-center">
            <h4> Order Not found</h3>
        </div>
        <?php } ?>


    </div>
</div>