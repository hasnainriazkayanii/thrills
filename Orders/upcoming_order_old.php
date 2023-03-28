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
// print_r($_POST);

if(isset($_POST['search_name']) && isset($_POST['activedata']) ){
    if($_POST['search_name']=="" && $_POST['activedata']==2){
        $upcoming_table_title ='Past Orders';
         $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id
                 WHERE order.date_of_visit<$current_date $ishide AND  order.status<=3 or order.status=3  $ishide group by order.order_id ORDER BY order.date_of_visit Desc,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else if($_POST['search_name']=="" && $_POST['activedata']==1){
         $upcoming_table_title = 'Active Orders';
        $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id  Left JOIN guest as g ON order.id= g.order_id
                WHERE order.date_of_visit>='$current_date' $ishide AND  order.status<=3 $ishide group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else if($_POST['search_name']!="" && $_POST['activedata']==0){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%'  $ishide  AND order.status<=3 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
        
    }
     else if($_POST['search_name']!="" && $_POST['activedata']==1){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id 
        WHERE order.date_of_visit>='$secrch_Date' AND (order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%')  $ishide  AND order.status<=3 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
        
        
     }
     else if($_POST['search_name']!="" && $_POST['activedata']==2){
        $search_name = $_POST['search_name'];
        $search_result_msg =1;
        $upcoming_table_title ='Search Orders';
        $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id 
        WHERE order.date_of_visit<'$secrch_Date' AND (order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%')  $ishide  AND (order.status<=3 or order.status=3 )group by order.order_id ORDER BY order.date_of_visit DESC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
        
        
     }
    else if(isset($_GET['active']) && $_GET['active']=="3"){
        $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.date_of_visit>=$current_date $ishide AND order.status<=3 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
    else{
    
    // Fetch upcoming orders
    $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.date_of_visit>=$current_date $ishide AND order.status<=2 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }
}
else{
    
    // Fetch upcoming orders
    $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.date_of_visit>=$current_date $ishide AND order.status<=2 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
    }

// if(isset($_POST['search_name']) && $_POST['search_name']!=""){
//     $search_name = $_POST['search_name'];
//     $search_result_msg =1;
//     $upcoming_table_title ='Search Orders';
//     $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.customer like '%$search_name%' or order.order_id like '%$search_name%' or customer.Phone_number like '%$search_name%' or theme_parks.name like '%$search_name%'  $ishide  AND order.status<=3 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
// }
//  if(isset($_POST['activedata'])){
    
//     if($_POST['activedata']==1){
//         $upcoming_table_title ='Past Orders';
//         $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id
// WHERE order.date_of_visit<$current_date $ishide AND  order.status<=3 or order.status=3  $ishide group by order.order_id ORDER BY order.date_of_visit Desc,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
//     }
//     else{
//         $upcoming_table_title = 'Active Orders';
//         $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id  Left JOIN guest as g ON order.id= g.order_id
// WHERE order.date_of_visit>=$current_date $ishide AND  order.status<=3 $ishide group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
//     }
// }
// else if(isset($_GET['active']) && $_GET['active']=="3"){
//     $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.date_of_visit>=$current_date $ishide AND order.status<=3 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
// }
// else{
    
// // Fetch upcoming orders
// $sql = "SELECT *,g.id as guest_id, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id Left JOIN guest as g ON order.id= g.order_id WHERE order.date_of_visit>=$current_date $ishide AND order.status<=2 group by order.order_id ORDER BY order.date_of_visit ASC,timestamp(order.time) ASC,STR_TO_DATE(time, '%l:%i %p')";
// }
// print_r($sql);
$result = mysqli_query($db, $sql);
$upcoming_orders_unsorted = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
</style>



<div id="content-wrapper">

    <div class="container-fluid">

        <!--<div class="row">-->
        <!--    <div class="col-md-10">-->

        <!--        <div class="col-md-8" style="float:left;">-->
        <!--            <h3>Upcoming Orders</h3>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

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
        
                <!--                    <option id="active" value="0">Active orders</option>>-->
        
                <!--                    <option id="Past" value="1">Past orders</option>-->
        
                <!--                </select>-->
        
                <!--            </div>-->
        
                <!--        </div>-->
        
                    
                <!--</div>-->
                <!--    </form>-->
                
                <form method="post" action="upcoming_order.php?active3" id="upcoming_search" >
                    <div class="d-flex align-items-center">
                        <div class="d-flex" style="margin-right: 10px;align-items: center;">
                             <input type="text" class="form-control" name='search_name' placeholder="Search for" aria-label="Search"  style="min-width:100px">
        
                                <!--<input style="padding: 8px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;"-->
                                <!--       type="button" class="btn btn-primary" name="submit" value="Search">-->
                                <button type="button" style="padding: 8px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" class="btn btn-primary" onclick="serach_orders()"> Search</button>
        
                                <a style="padding: 7px 6px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;"
                                   href="upcoming_order.php?active=3" class="btn btn-primary" name="show_all" >Show All</a>
                        </div>
                        <div>
                            <div class="input-group-append">
        
                                <select class="form-control" id="orderid" name="activedata">
        
                                     <option <?php echo $_POST['activedata']==0?'selected':'' ?> value="0" selected>Please select</option>
        
                                    <option <?php echo $_POST['activedata']==1?'selected':'' ?> id="active" value="1">Active orders</option>>
        
                                    <option <?php echo $_POST['activedata']==2?'selected':'' ?> id="Past" value="2">Past orders</option>
        
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
                    <div class="d-flex align-items-center  mb-2">
                        <h4 class="m-0 w-50"><?= $table_date ?> </h4>
                        <p class="m-0"><?= $show_date ?></p>
                    </div>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                    <?php

                foreach ($orders as $key => $value) {
                   
                    if($level>=8){
                          $result16 = mb_substr($value["Phone_number"], 0, 3);
                          $result17 = mb_substr($value["Phone_number"], 3, 3);
                          $result18 = mb_substr($value["Phone_number"], 6, 4);
                          $result19 = "(" . $result16 . ") " . $result17 . "-" . $result18;
                          if($value['country_code']=='+1'){
                              $country_code ='';
                          }
                          else{
                              $country_code = $value['country_code'];
                          }
                           $telephone = $country_code . $result19 ;
                    }
                    else{
                        $telephone = "(***) ***-" . substr($row1["Phone_number"], strlen($row1["Phone_number"]) - 4)."";
                    }
                    
                    ?>
                                <tr>

                                    <td class="w-25 position-relative" style="min-width: 158px;">
                                        
                                        
                                        <div class="text-center">
                                            <?php 
                                        $ticket_order = $value['ticket_order'];
                                        list($a, $b,$c) = explode('/',$ticket_order);
                                        if (preg_match('/\Kings Island Front Line\b/i', $c)){
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/KI-Logo-Hero.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                     <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                         <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                    
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\Universal Studios\b/i', $c)){
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/universal-logo-small.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                    <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                        <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                   
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\Islands Of Adventure\b/i', $c)){
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/IOA.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                    <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                        <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                   
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        } 
                                        else if (preg_match('/\bAdmission ($150 each)\b/i', $c)){
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                   <img src="../images/cedarpointwithadmission.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                   <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                         <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                    
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\Cedar Point\b/i', $c)){
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                   <img src="../images/Cedar-point.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                   <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                        <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                   
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\One Day Dollywood\b/i', $c)){
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                   <img src="../images/dollywood-logo-color.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                   <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                         <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                    
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\Legoland\b/i', $c)){
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                   <img src="../images/Legoland_logo.svg.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                   <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                        <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                    
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if(preg_match('/\bAquarium\b/i', $c)){
                                            ?>
                                            
                                            <div class="d-flex align-items-center justify-content-center">
                                                    <div style="flex:1">
                                                        <p class="time_para"><?=  $value['time']?></p>
                                                        <img src="../images/seaworld-logo-small.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                        <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    </div>
                                                    <div style="flex:1">
                                                        <p class="mb-0 mt-3">
                                                             <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                        </p>
                                                        
                                                        <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                        
                                                    </div>
                                                </div>
                                                
                                               
                                            <?php
                                        }
                                        
                                        
                                        else if (preg_match('/\Carowinds\b/i', $c)){
                                           
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/1200px-Carowinds_Logo.svg.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                    <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                         <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                   
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\Busch\b/i', $c)){
                                           
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                     <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/BG-small.jpg" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                    <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                        <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                   
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\Volcano bay\b/i', $c)){
                                           
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                     <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/VB-Logo-Trans.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                    <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                         <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                    
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\b2 Park\b/i', $c)){
                                           
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/2ParkLogo-Universal.jpg" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                    <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                        <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                   
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        else if (preg_match('/\HHN\b/i', $c)){
                                           
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div style="flex:1">
                                                    <p class="time_para"><?=  $value['time']?></p>
                                                    <img src="../images/HHNStackedLogoBlack-300x153.png" alt="" style="width: 67%;margin: 0 auto;display: flex;">
                                                    <p style="font-size:25px"><?= $value['order_code'] ?></p>
                                                    
                                                </div>
                                                <div style="flex:1">
                                                    <p class="mb-0 mt-3">
                                                         <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?>
                                                    </p>
                                                    
                                                    <!--<p><?= "($".$value['price']." each)"?></p>-->
                                                </div>
                                            </div>
                                            <?php
                                        }
                                           
                                        else{
                                            ?>
                                                <p class="mb-0 mt-3"> <?php
                                                            if($a!="0ad" && $b!="0ch"){
                                                                echo $a.'ult'.'/'.$b.'ild';
                                                            }
                                                            else if($a=="0ad"){
                                                                echo $b.'ild';
                                                            }else if($b=="0ch"){
                                                                echo $a.'ult';
                                                            }
                                                        ?></p>
                                             
                                                <!--<p><?= "($".$value['price']." each)"?></p>-->
                                            <?php
                                        }
                                        
                                        ?>
                                        </div>
                                        
                                        
                                        <!--<div class='text-center  ' style="font-size: 21px;"><?= $value['time'] ?></div>-->
                                        
                                        <div class='d-flex align-items-center justify-content-around '>
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
                                                            <!--<td class='p-0'>$<?= $value['deposit'] ?></td>-->
                                                            
                                                                
                                                                <?php 
                                                                $deposite = 0;
                                                                    if($value['deposit']==0 || $value['deposit']>0){
                                                                       
                                                                            $deposite = $value['deposit'];
                                                                        
                                                                    }
                                                                    else{
                                                                       $deposite =0;
                                                                    }
                                                                ?>
                                                            <td class='p-0'>$<?= (float)$deposite ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class='p-0'>BAL DUE</td>
                                                            <td class='text-danger p-0'>$<?= $value['total'] - $value['deposit'] ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                    </td>

                                    <td class='td-1 w-25' style="min-width: 315px;position:relative">
                                        <div style=" position: absolute;left: 50%;top: 50%;transform: translate(-50%,-50%);">
                                            
                                            <div class="text-center">
                                                <p style=" font-size: 22px;"><?= $value['customer'] ?></p>
                                                <p style=" font-size: 20px;"><?=  $telephone ?></p>
                                            </div>
    
                                            
                                                <?php
                                                
                                                    if($value['status']==3){
                                                        echo "";
                                                    }
                                                    else{
                                                        ?>
                                                            <div class="d-flex align-items-center justify-content-around mt-3">
                    
                                                                <a href="/cheapthrills/Orders/text_order_details.php?order_id=<?= $value['order_code'] ?>" class="btn btn-success mr-2">Send Confirmation Text</a>
                    
                                                                <a href='Updateorders.php?id=<?= $value['order_id'] ?>' class="btn btn-info">Edit</a>
                    
                                                            </div>
                                                        <?php
                                                    }
                                                ?>
                                        </div>
                                    </td>
                                    <td class="w-25" style="min-width: 300px;">
                                        <?php
                                        $getStatus = "SELECT * FROM `mark_status`";
                                        $get_status_fire = mysqli_query($db, $getStatus);
                                        $order_text = '';
                                        $buttons = '';
                                        if ($value['status'] == 0) {
                                                if($value['deposit']!="" && $value['deposit']>0){
                                                     $order_IDS = $value['order_id'];
                                                    $order_text = "<h4 class='text-center'>ORDER CONFIRMED</h4>";
                                                    
                                                    $checkticket = "SELECT * FROM `guest` WHERE order_id = '$order_IDS' AND (ticket_id = 'null' or ticket_id ='')";
                                                    $checkticket_fire = mysqli_query($db, $checkticket);
                                                    if($checkticket_fire && mysqli_num_rows($checkticket_fire)>0){
                                                       
                                                        $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assign Tickets</a>
                                                        </div>';
                                                    }
                                                    else{
                                                         $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assigned</a>
                                                        </div>';
                                                        
                                                    }
                                                    
                                                    
                                                }
                                                else{
                                                    $order_text = '';
                                                   
                                                    // $buttons = '';
                                                    // $order_text = "<h4 class='text-center'>ORDER CONFIRMED</h4>";
                                                    
                                                    if($value['assign']==0){
                                                        $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assign Tickets</a>
                                                        </div>';
                                                    }
                                                    else{
                                                         $buttons = '<div class="mt-3 text-center">
                                                        <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assigned</a>
                                                        </div>';
                                                    }
                                                }
                                            
                                        } else if ($value['status'] == 1 || $value['status'] == 2 || $value['status'] == 3) {
                                            $order_text = '<h4 class="text-center">ORDER CONFIRMED</h4>';
                                        }

                                        if ($value['status'] == 1 || $value['status'] == 2) {
                                            if($value['isdisabled']==0){
                                                $logbtn = '<button onclick="logutUser('.$value["guest_id"].','.$value["order_id"].','.$value["Phone_number"].')"  class="btn btn-success btn-sm px-3" role="button">Login</button>';
                                                
                                            }
                                            else{
                                                $logbtn = '<button onclick="logutUser('.$value["guest_id"].','.$value["order_id"].','.$value["Phone_number"].')"  class="btn btn-danger btn-sm px-3" role="button">Logout</button>';
                                            }
                                            
                                            $buttons = '<div class="mt-3 text-center">
                                                    <a href="text_history.php?order=' . $value["order_id"] . '" class="btn btn-primary btn-sm" role="btn">Text Usage</a>
                                                    <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info btn-sm" role="button"> Assigned</a>
                                                    '.$logbtn.'
                                    </div>';
                                        }
                                        else if($value['status']==3){
                                            $buttons = '<div class="mt-3 text-center">
                                                            <a href="../Assign/Addassign.php?id='.$value["order_id"].'" class="btn btn-info" role="button">Ticket Info</a>
                                                        </div>';
                                        }
                                        
                                        if (mysqli_num_rows($get_status_fire) > 0) {
                                            echo $order_text;
                                            echo "<select class='custom-select' data-order='{$value['order_id']}' onchange='mark(this);'>";

                                            while ($status_m = mysqli_fetch_assoc($get_status_fire)) {
                                                echo "<option " . ($value['status'] == $status_m['mark_status'] ? 'selected' : '') . " value='" . $status_m['mark_status'] . "' class='" . $status_m['color_class'] . "'>" . $status_m['name'] . "</option>";
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
<script>
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
          console.log(order, " marked as ", status);
          location.reload();
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