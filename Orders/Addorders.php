<?php
include('../Config/Connection.php');
require_once "../libraries/vendor/autoload.php";
include "../Config/twilio.php";
use Twilio\Rest\Client;

//login check
session_start();

$login_check = $_SESSION['id'];
if ($login_check != '1') {
    $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}
$id = $_GET['id'];
if(@$_GET['order_id']){
    $order_id = $_GET['order_id'];
    $sql = "SELECT * FROM `order` o1, `customer` c where o1.customer_id = c.id and o1.id = $order_id";
}
else {
    $sql = "SELECT * FROM customer c  WHERE  c.id='$id'";
}
    $result = mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($result);
    $id = $user['id'];
    $first_name = $user['first_name'];
    $Last_name = $user['Last_name'];
    $name = $first_name . ' ' . $Last_name;
    $sales_commission = $user['sales_commission'] ?? '';
    $sales_personID = $user['sales_personID'] ?? '';
    $adult = @$user['adults'] ?? '';
    $kid = @$user['kids'] ?? '';
    $date_of_visit = @$user['date_of_visit'];
    $date_submitted = date('m/d/Y', strtotime($date_of_visit));
    $add_date = date('m/d/Y', strtotime($date_of_visit . '+1 day'));
    $balance = $user['balance'] ?? 0;
    $is_hide = $user['is_hide'];
    $discount = $user['discount'] ?? '';
    $theme_ticket_type = $user['ticket_type'] ?? "";
    $ticket_type_id = $user['ticket_type_id'] ?? "";
    $addon_ticket_type = $user['AddOn'] ?? "";
    $theme_park_id = $user['theme_park_id'] ?? "";
    $time = $user['time'] ?? "";

//login check end
// order parameters
if (isset($_POST['userpay'])) {
    $orderID = $_POST['orderID'];
    $customerID = $_POST['customerID'];
    $paymentMethod = $_POST['paymentMethod'];
    $paymentAmount = $_POST['paymentAmount'];
    $theme_park_id = $_POST['theme_park_id'];
    $typeOfPayment = $_POST['typeOfPayment'];
    $refund_reason = $_POST['refund_reason'];
    $commission_deduction = $_POST['commission_deduction'];
    $sendText = $_POST['sendText'];
    $session_user_id = $_SESSION['user_id'];

    if($commission_deduction != "" && $typeOfPayment == "Refund") {
        $query_update = "UPDATE `order` set `sales_commission` = sales_commission - $commission_deduction  where order_id = '$orderID'";
        $res = mysqli_query($db, $query_update);
    }
    /******
     fetching existing accounting detail.
     if count == 0 update order status from pending to confirmed
     Also send confirmation text message
    ******/
    $query_accounting = "SELECT id from accounting where orderID = '$orderID'";
    $smt = mysqli_query($db,$query_accounting);
    $accounting_count = mysqli_num_rows($smt);
    if ($accounting_count == 0){
        $order_id = @$_POST['order_id'];
        $status = "8";
        $order_confirmed = "1";
        include 'update_status_template.php';
    }

    $account = "INSERT INTO accounting(`sales_commission_amount`,`orderID`,`customerID`, `typeOfPayment`,  `paymentMethod`,`paymentAmount`, `sendText`,`personCollecting`,`refund_reason`) VALUES ('$commission_deduction','$orderID', '$customerID', '$typeOfPayment', '$paymentMethod','$paymentAmount', '$sendText','$session_user_id','$refund_reason')";
    $result = mysqli_query($db, $account);
//    var_dump($account);
    if(isset($_POST['sendText']) && $_POST['sendText'] == '1') {
        header("Location: text_payment_details.php?added_order=true&order_id=" . $orderID);
    } else {
        header("Location: Orderdetails.php?active=0&sucess=0");
    }

}

if (isset($_POST['orderadd'])) {
    // echo '<pre>',print_r($_POST);exit;
    $gateway = $_POST['gateway'];

    $customer_id = $_POST['idHidden'];
    $customer = $_POST['customer'];
    $date_of_vist1 = $_POST['date_of_vist'];
    if(isset($_POST['addentry'])) {
        $order_id = @$_POST['order_id'];
        $query_guest = "SELECT * FROM guest where order_id = '$order_id'";
        $smt = mysqli_query($dbb,$query_guest);
        while ($row = mysqli_fetch_assoc($smt)){
            $guest_name = $row['guest_name'];
        }
    }
    $customer_query = "SELECT * from customer where id = '$customer_id'";
    $customer_row = mysqli_fetch_assoc(mysqli_query($db,$customer_query));
    $customer_country_code = $customer_row['country_code'];
    $customer_phone_number = $customer_row['Phone_number'];
    $customer_name = $customer_row['first_name'] . " " . $customer_row['Last_name'];
    $country_code_name = $customer_row['country_code_name'];


    $time = strtotime($date_of_vist1);
    $formatDate = date('m/d/y',$time);
    $today=date("m/d/y");

    if($formatDate==date("m/d/y")){
        $visit_date = "Today";
    }
    else if ($formatDate==date('m/d/y', strtotime($today. ' +1 days'))){
        $visit_date = 'TOMORROW';
    }
    else{
        $visit_date = date('M d', strtotime($date_of_vist1));
    }


    $date_of_vist = date("Y/m/d", strtotime($date_of_vist1));
    $aa12 = $_POST['time'];
    $option = $_POST['option'];
    $price = $_POST['cost'];
    $ticket_type_post = explode('*****',$_POST['ticket_type']);
    $ticket_type_id = $ticket_type_post[1];
    $ticket_type = $ticket_type_post[0];

    $addOn = $_POST['AddOn'];

    $ticket_type33 = (explode(" ", $ticket_type));
    $ticket_type2 = array_pop($ticket_type33);
    $ticket_type34 = implode(" ", $ticket_type33);
    $adults = ($_POST['adults'] && $_POST['adults'] !== '') ? $_POST['adults'] : 0;
    $new_balance = $_POST['balance'] ?? 0;

    $kids = ($_POST['kids'] && $_POST['kids'] !== '') ? $_POST['kids'] : 0;

    $no_of_days = $_POST['no_of_days'];

    $discount = $_POST['discount'] ?? 0;

    $total = $_POST['total'] ?? 0;

    $hideorder = $_POST['is_hide'];

    $sales_personID = explode('*****',$_POST['sales_personID'])[0];
    

    $sales_commission = $_POST['sales_commission'];

    $deposit = $_POST['deposit'] ?? 0;

    $theme_park_id = $_POST['theme_park_id'];

    $_SESSION['orderadd_data'] = $_POST; // Save data for prefilling addorder form


    $sales_query = "SELECT name from partners where id = '$sales_personID'";
    $sales_name = mysqli_fetch_assoc(mysqli_query($db,$sales_query))['name'];

    $sql = "SELECT theme_park_parents.code,theme_parks.name
                                    FROM theme_parks 
                                    LEFT JOIN theme_park_parents ON theme_parks.theme_park_parent_id=theme_park_parents.id
                                    WHERE theme_parks.id='$theme_park_id'";

    $result = mysqli_query($db, $sql);

    $theme_park_parent = mysqli_fetch_assoc($result);
    $theme_park_parent_code = $theme_park_parent['code'];
    $theme_park_name = $theme_park_parent['name'];

    $timestamp = time();

    // end parameters

    $datetimeFormat = 'Y-m-d';

    $date = new \DateTime();

    $date->setTimestamp($timestamp);

    $create_date = $date->format($datetimeFormat);

    $sql = "SELECT MAX(id) as last_id FROM `order`";

    $result = mysqli_query($db, $sql);

    $user = mysqli_fetch_assoc($result);

    $max = $user['last_id'];

    $user_id = 1000 + $max + 1;

    $bdt_user = $theme_park_parent_code . $user_id;

    /* $ticket_order= $adults.'ad/'.$kids.'ch/'.$option; */

    $ticket_order = $adults . 'ad/' . $kids . 'ch/' . $ticket_type34;

    /* var_dump($ticket_order);die; */

    $customer_update = "UPDATE ticket SET 

                          isengaged=1

                           WHERE id='$ticket_type'";

    mysqli_query($db, $customer_update);

    // add balace in customer table
    $customer_balance = "UPDATE customer SET balance='$new_balance' WHERE id='$customer_id'";
    mysqli_query($db, $customer_balance);

    // order insert query
    $order_insert = "INSERT INTO `order`(`order_id`,`ticket_id`,`ticket_type`,`ticket_type_id`, `customer_id`,`customer`, `date_of_visit`,`time`,`option`, `price`,`no_of_days`, `adults`, `kids`,`discount`, `total`,`create_time`,`ticket_order`, `theme_park_id`, `is_hide`, `sales_personID`, `sales_commission`, `deposit`, `gateway`,`AddOn`)  

                        VALUES ('$bdt_user','','$ticket_type','$ticket_type_id','$customer_id','$customer',' $date_of_vist','$aa12','$option','$price','0','$adults','$kids','$discount','$total','$timestamp','$ticket_order', $theme_park_id, '$hideorder', '$sales_personID', '$sales_commission', '$deposit', '$gateway','$addOn')";
    $result = mysqli_query($db, $order_insert);
    $new_order_id = mysqli_insert_id($db);
    // echo $order_insert;exit;

    // inserting commission into table
    $user_id = $_SESSION['id'];
    $commission_date = str_replace('/','',$date_of_vist);
    $commission_query = "INSERT INTO `commPayouts` (`type`, `partnerID`, `payoutTotal`, `payoutDay`,`createdBy`,`orderID`,`customerID`) 
            VALUES ('','$sales_personID','$sales_commission','$commission_date','$user_id','$new_order_id','$customer_id')";
    mysqli_query($db,$commission_query);

    $last_id = mysqli_insert_id($db);
    $guest_insert = "INSERT INTO guest (`guest_name`,`order_id`,`type`,`country_code`,`guest_mobile`,`country_code_name`,`ticket_id`,`login_id`,`entitlement`,`inactive`,`isdisabled`,`took_ss`,`terms`)
                        VALUES ('$customer_name','$last_id','adult','$customer_country_code','$customer_phone_number','$country_code_name','','','',0,0,0,'')";
    $result_guest = mysqli_query($db, $guest_insert);

    if ($hideorder != "1") {
        $client_1 = new Client($account_sid, $auth_token);
        try {
            $query_admin_number = "SELECT group_concat(value) as numbers from settings where id in (1,2)";
            $admin_number = mysqli_fetch_assoc(mysqli_query($db, $query_admin_number))['numbers'];
            $numbers = explode(',', $admin_number);
            foreach ($numbers as $v) {
                $resp_1 = $client_1->messages->create(
                    $v,
                    array(
                        'from' => $twilio_number,
                      //  'body' => "$sales_name ($" . $sales_commission . ") scheduled $customer for $adults/$kids $theme_park_name $ticket_type34 $visit_date for $" . $total
                        'body' => "$sales_name ($" . $sales_commission . ") scheduled $customer for $adults/$kids $ticket_type34 $visit_date for $" . $total
                    )
                );
            }

        } catch (Exception $e) {
//            var_dump($e);
        }
    }

    if(isset($_POST['is_hide']) && $_POST['is_hide'] == '1'){
        header( "Location: Orderdetails.php?active=0&sucess=0" );
    } else {
        header("Location: text_order_details.php?added_order=true&order_id=" . $bdt_user);
    }
}

if (isset($_POST['updateorder'])) {

    $id = @$_GET['id'];

    $sql = "SELECT * FROM `order` where id='$id'";

    $result = mysqli_query($db, $sql);

    $user = mysqli_fetch_assoc($result);

    $customer_id = $_POST['idHidden'];

    $gateway = $_POST['gateway'];

    $customer = $_POST['customer'];

    $date_of_vist1 = $_POST['date_of_vist'];

    $date_of_vist = date("Y/m/d", strtotime($date_of_vist1));

    $time1 = $_POST['time'];

    $aa12 = date("g:i A", strtotime($time1));

    $option = $_POST['option'];

    $price = $_POST['cost'];

    $ticket_type_post = explode('*****',$_POST['ticket_type']);
    $ticket_type_id = $ticket_type_post[1];
    $ticket_type = $ticket_type_post[0];


    $addOn = $_POST['AddOn'];
    $ticket_type33 = (explode(" ", $ticket_type));

    $ticket_type2 = array_pop($ticket_type33);

    $ticket_type34 = implode(" ", $ticket_type33);

    $adults = $_POST['adults'];

    $kids = $_POST['kids'];

    $discount = $_POST['discount'];

    $total = $_POST['total'];

    $theme_park_id = $_POST['theme_park_id'];
//echo '----'. $_POST['is_hide']; echo '+++++'.$_POST['hideorder']; exit();
    $hideorder = $_POST['is_hide'] == true ? 1 : 0;

    $deposit = $_POST['deposit'];

    //// for change order_id;
    $sql = "SELECT theme_park_parents.code 
                                    FROM theme_parks 
                                    LEFT JOIN theme_park_parents ON theme_parks.theme_park_parent_id=theme_park_parents.id
                                    WHERE theme_parks.id='$theme_park_id'";

    $result = mysqli_query($db, $sql);

    $theme_park_parent = mysqli_fetch_assoc($result);
    $theme_park_parent_code = $theme_park_parent['code'];

    $order_id = $user['order_id'];
    $order_id = $theme_park_parent_code . substr($order_id, 2);

    $sales_personID = $_POST['sales_personID'];

    $sales_commission = $_POST['sales_commission'];


    /// end change order_id

    $timestamp = time();

    //end parameters

    $datetimeFormat = 'Y-m-d';

    $date = new \DateTime();

    $date->setTimestamp($timestamp);

    $create_date = $date->format($datetimeFormat);

    $ticket_order = $adults . 'ad/' . $kids . 'ch/' . $ticket_type34;

//    $delete_guests = "DELETE from guest where order_id  = $id";
//    mysqli_query($db,$delete_guests);

    $order_insert = "UPDATE `order` SET
                `order_id`='$order_id',

                `customer_id`='$customer_id',

                `customer`='$customer',

                `date_of_visit`='$date_of_vist',

                `time`='$aa12',

                `option`='$option',

                `ticket_type`='$ticket_type',
                
                `ticket_type_id`='$ticket_type_id',

                `price`='$price',
                
                `sales_commission`='$sales_commission',
                
                `sales_personID`='$sales_personID',

                `ticket_order`='$ticket_order',

                `adults`='$adults',

                `kids`='$kids',

                `discount`='$discount',

                `total`='$total',

                `assign`=0,

                `create_time`='$timestamp',
                `theme_park_id`=$theme_park_id,
                `is_hide`=$hideorder,
                `AddOn`='$addOn'
                
                WHERE id='$id'";
//var_dump($order_insert);
    $result = mysqli_query($db, $order_insert);

    $commission_query = "update commPayouts set partnerID = '$sales_personID', payoutTotal = '$sales_commission' where orderID = '$id'";
    mysqli_query($db,$commission_query);


//    if ($kids > $user['kids']) {
//
//        $guest_insert = "INSERT INTO guest (order_id,guest_name,guest_mobile,inactive,isdisabled,type,login_id,ticket_id,entitlement)
//                                                    VALUES ('$id','$customer','',0,1,'kid','','','')";
//
//        $result = mysqli_query($db, $guest_insert);
//    }




    header("Location: Orderdetails.php?active=0&sucess=1");
}


if (isset($_POST['addentry'])) {
    $gateway = $_POST['gateway'];

    $customer_id = $_POST['idHidden'];
    $customer = $_POST['customer'];
    $date_of_vist1 = $_POST['date_of_vist'];
    $date_of_vist = date("Y/m/d", strtotime($date_of_vist1));
    $aa12 = $_POST['time'];
    $option = $_POST['option'];
    $price = $_POST['cost'];
    $ticket_type_post = explode('*****',$_POST['ticket_type']);
    $ticket_type = $ticket_type_post[0];
    $ticket_type33 = (explode(" ", $ticket_type));
    $ticket_type2 = array_pop($ticket_type33);
    $ticket_type34 = implode(" ", $ticket_type33);
    $adults = ($_POST['adults'] && $_POST['kids'] !== '') ? $_POST['adults'] : 0;
    $new_balance = $_POST['balance'] ?? 0;

    $addOn = $_POST['AddOn'];
    $kids = ($_POST['kids'] && $_POST['kids'] !== '') ? $_POST['kids'] : 0;

    $no_of_days = $_POST['no_of_days'];

    $discount = $_POST['discount'] ?? 0;

    $total = $_POST['total'] ?? 0;

    $hideorder = $_POST['is_hide'];

    $deposit = $_POST['deposit'] ?? 0;

    $sales_personID = $_POST['sales_personID'];

    $sales_commission = $_POST['sales_commission'];

    $theme_park_id = $_POST['theme_park_id'];

    $_SESSION['orderadd_data'] = $_POST; // Save data for prefilling addorder form

    $sql = "SELECT theme_park_parents.code 
                                    FROM theme_parks 
                                    LEFT JOIN theme_park_parents ON theme_parks.theme_park_parent_id=theme_park_parents.id
                                    WHERE theme_parks.id='$theme_park_id'";

    $result = mysqli_query($db, $sql);

    $theme_park_parent = mysqli_fetch_assoc($result);
    $theme_park_parent_code = $theme_park_parent['code'];

    $timestamp = time();

    // end parameters

    $datetimeFormat = 'Y-m-d';

    $date = new \DateTime();

    $date->setTimestamp($timestamp);

    $create_date = $date->format($datetimeFormat);

    $sql = "SELECT MAX(id) as last_id FROM `order`";

    $result = mysqli_query($db, $sql);

    $user = mysqli_fetch_assoc($result);

    $max = $user['last_id'];

    $user_id = 1000 + $max + 1;

    $bdt_user = $theme_park_parent_code . $user_id;

    /* $ticket_order= $adults.'ad/'.$kids.'ch/'.$option; */

    $ticket_order = $adults . 'ad/' . $kids . 'ch/' . $ticket_type34;

    /* var_dump($ticket_order);die; */

    $customer_update = "UPDATE ticket SET 

                          isengaged=1

                           WHERE id='$ticket_type'";

    mysqli_query($db, $customer_update);

    // add balace in customer table
    $customer_balance = "UPDATE customer SET balance='$new_balance' WHERE id='$customer_id'";
    mysqli_query($db, $customer_balance);

    // order insert query
    $order_insert = "INSERT INTO `order`(`order_id`,`ticket_id`,`ticket_type`,`ticket_type_id`, `customer_id`,`customer`, `date_of_visit`,`time`,`option`, `price`,`no_of_days`, `adults`, `kids`,`discount`, `total`,`create_time`,`ticket_order`, `theme_park_id`, `is_hide`, `sales_personID`, `sales_commission`, `deposit`, `gateway`,`AddOn`)  

                        VALUES ('$bdt_user','','$ticket_type','$ticket_type_id','$customer_id','$customer',' $date_of_vist','$aa12','$option','$price','0','$adults','$kids','$discount','$total','$timestamp','$ticket_order', $theme_park_id, '$hideorder', '$sales_personID', '$sales_commission', '$deposit', '$gateway','$addOn')";
    $result = mysqli_query($db, $order_insert);

    header("Location: AddAnotherOrder.php?id=" .$customer_id);

}


$sql = "SELECT * FROM customer";
$result = mysqli_query($db, $sql);
if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
        $objet = new stdClass;

        $objet->id = $row["id"];

        $objet->label = $row["first_name"] . ' ' . $row["Last_name"];

        $Customer_name[] = $objet;
    }

    $Customer_name = json_encode($Customer_name);
}

$datatable = false;
include('../includes/header.php');
?>
<style>
    .my-form {
        width: 100%;
        height: 38px;
        border-radius: 5px;
        border: 1px solid #33333338;
    }

    .tt-query,
        /* UPDATE: newer versions use tt-input instead of tt-query */ .tt-hint {
        width: 100%;
        height: 30px;
        padding: 8px 12px;
        font-size: 24px;
        line-height: 30px;
        border: 2px solid #ccc;
        border-radius: 8px;
        outline: none;

    }

    .tt-query {
        /* UPDATE: newer versions use tt-input instead of tt-query */ box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }

    .tt-hint {
        color: #999;
    }

    .tt-menu {
        /* UPDATE: newer versions use tt-menu instead of tt-dropdown-menu */ width: 100%;
        margin-top: 12px;
        padding: 8px 0;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
    }

    .tt-suggestion {
        padding: 3px 20px;
        font-size: 18px;
        line-height: 24px;
    }

    .tt-suggestion.tt-is-under-cursor {
        /* UPDATE: newer versions use .tt-suggestion.tt-cursor */ color: #fff;
        background-color: #0097cf;
    }

    .tt-suggestion p {
        margin: 0;
    }

    .left-col {
        margin-top: 10px;
        float: left !important;
        width: 25% !important;
    }

    .right-col {
        margin-top: 10px;
        float: left !important;
        width: 75% !important;
    }

    @media only screen and (max-width: 780px) and (min-width: 750px) {
        .left-col {
            margin-top: 10px;
            float: left !important;
            width: 52% !important;
        }

        .right-col {
            margin-top: 10px;
            float: left !important;
            width: 48% !important;
        }
    }

    @media only screen and (max-width: 1030px) and (min-width: 1020px) {
        .left-col {
            margin-top: 10px;
            float: left !important;
            width: 52% !important;
        }

        .right-col {
            margin-top: 10px;
            float: left !important;
            width: 48% !important;
        }
    }

    @media only screen and (max-width: 1290px) and (min-width: 1230px) {
        .left-col {
            margin-top: 10px;
            float: left !important;
            width: 28% !important;
        }

        .right-col {
            margin-top: 10px;
            float: left !important;
            width: 70% !important;
        }
    }

    @media only screen and (max-width: 678px) and (min-width: 0px) {

        label {
            font-size: 12px;
            display: inline-block;
            margin-bottom: .5rem;
        }

        .small-row {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        .col-small {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        .left-col {
            margin-top: 10px;
            float: left !important;
            width: 40% !important;
        }

        .right-col {
            margin-top: 10px;
            float: left !important;
            width: 60% !important;
        }

        footer.sticky-footer {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            position: fixed !important;
            right: 0;
            bottom: 0;
            width: calc(100% - 90px);
            height: 60px;
            background-color: #e9ecef;
        }

        .gj-timepicker-bootstrap [role=right-icon] button {
            width: 38px;
            height: 38px;
            border: 1px solid #ccc !important;
            position: relative;
        }
    }

    .top-col {
        margin-bottom: 1rem !important;
    }
    #balance_due{
        font-weight: bold;
        font-size: 24px;
    }
</style>
<div id="content-wrapper">
    <div class="container-fluid">

        <div class="col-md-12">

            <h3><?php if(@$_GET['order_id']){ echo "Update";} else{ echo "Add";} ?> Order</h3>

            <hr>

        </div>

    </div>

    <?php
    $sql = "SELECT * FROM ticket where isengaged=0";
    $result = mysqli_query($db, $sql);

    ?>

    <?php
    $theme_parks_query = "SELECT * FROM theme_parks where active = 1 ORDER BY code DESC";
    $theme_parks_result = mysqli_query($db, $theme_parks_query);
    $theme_parks = [];

    while ($theme_park = mysqli_fetch_assoc($theme_parks_result)) {
        array_push($theme_parks, array('id' => $theme_park['id'], 'name' => $theme_park['name'], 'code' => $theme_park['code']));
    }
    ?>

    <?php
    $sales_persons_query = "SELECT * FROM partners where active = 1 ";
    $sales_persons_result = mysqli_query($db, $sales_persons_query);
    $sales_persons = [];

    while ($sales_person = mysqli_fetch_assoc($sales_persons_result)) {
        array_push($sales_persons, array('id' => $sales_person['id'], 'name' => $sales_person['name'], 'setComission' => $sales_person['setComission']));
    }
    ?>

    <?php
    $parktype = "SELECT * FROM parktype";

    $parkresult = mysqli_query($db, $parktype);
    ?>

    <?php
    $parktypesvalue = "SELECT * FROM tickettypes where adctive = 'True' order by ticket_name ASC";

    $parkresult1 = mysqli_query($db, $parktypesvalue);

    $orderadd_data = isset($_SESSION['orderadd_data']) ? $_SESSION['orderadd_data'] : [];
    ?>

    <div class="container-fluid" style="display:flex;">

        <div class="col-md-6 ">

            <form action="Addorders.php?id=<?= @$_GET['order_id'] ?>" autocomplete='off' autocomplete='off' method="post">

                <div class="row mb-2">

                    <!-- <div class="col-md-12 col-small"> -->

                    <div class="col-md-12">
                        <div class="left-col">
                            <label style="display: block;" for="fname">Customer *</label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="typeahead form-control" name="customer" id="costomer" aria-describedby="customer" placeholder="Customer *" value='<?= $name ?>' readonly>
                            <input type="hidden" name="idHidden" id="idhide" value='<?= $id ?>'>
                        </div>
                    </div>

                    <!-- previous balance -->
                    <!-- <div class="col-md-12 mb-3">
                        <div class="left-col">
                            <label style="display: block;" for="prev_balance">Previous Balance</label>
                        </div>

                        <div class="right-col">
                            <span class="font-weight-bold"><?= $balance ?></span>
                            <input type="hidden" class="form-control" name="" id="prev_balance" value='<?= $balance ?>' readonly>
                        </div>
                    </div> -->
                    <!-- // previous balance -->

                    </hr>

                    <div class="col-md-12">
                        <div class="left-col">
                            <label style="display: block;" for="fname">Date Of Visit *</label>
                        </div>

                        <div class="right-col">
                            <?php
                            if(@$_GET['order_id']){
                                $current_date = date("m/d/Y", strtotime($user['date_of_visit']));
                            }
                            else {
                                $current_date = date('m/d/Y');
                                if (date('H') > 12) {
                                    $current_date = date('m/d/Y', strtotime($current_date . ' +1 day'));
                                }
                            }
                            ?>
                            <input type="text" required name="date_of_vist" id="datepicker" class="form-control" value='<?= $current_date; ?>'>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Time *</label>
                        </div>

                        <div class="right-col">
                            <select class="form-control time" name="time" required>
                                <option>Please select a value</option>
                                <?php 
                                
                                $timeslot="SELECT * FROM time_slots order by time ASC";
                                   $timeslotress = mysqli_query($db, $timeslot);
                                    $current_date= date("Y-m-d", strtotime($current_date));
                                while ($timeslotres = mysqli_fetch_assoc( $timeslotress)){
                                    $checktime= date('g:i A',strtotime($timeslotres['time']));
                                   
                                      $checks="SELECT * FROM `order`where date_of_visit = '$current_date' AND time= '$checktime'" ;
                                   $slotschecktime = mysqli_query($db, $checks);
                                   $slotsbook=mysqli_num_rows($slotschecktime);
                                    $ids=$_GET['order_id'];
                                    
                                     $currentorder="SELECT * FROM `order` where id='$ids' LIMIT 1";
                                     $currentorderq = mysqli_query($db, $currentorder);
                                     $currentorderqr = mysqli_fetch_assoc( $currentorderq);
                                   
                                     if($slotsbook<$timeslotres['slots']){
                                         $ava=$timeslotres['slots']-$slotsbook;
                                ?>
                                
                                
                                
                                    <option value="<?php echo $checktime ?>" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === $checktime) echo 'selected'; ?>><?php if($ava<1){echo $checktime;}
                                    else{echo $checktime.'  ('.$ava.')' ;}?></option>
                                <?php }
                                
                                
                                else{ if (!isset($_GET['order_id'])){?>
                                 <option disabled value="<?php echo $checktime ?>"><?php if($ava<1){echo $checktime;}
                                    else{echo $checktime.'  ('.$ava.')' ;} ?></option>
                                <?php
                                }
                                    
                                else{ ?>
                                <option value="<?php echo $checktime ?>" <?php if ( $currentorderqr['time'] == $checktime) {echo 'selected';} else{echo 'disabled';}  ?>><?php echo $checktime; ?>
                                  </option>
                                <?php
                                }
                                }   
                                } 
                                
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Adults *</label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="form-control"onkeypress="return AllowNumbersOnly(event)" name="adults" id="adults" aria-describedby="adults" onkeyup="total_value()" placeholder="" value='<?= $adult ?>' required>

                        </div>
                    </div>

                    <!-- </div> -->

                    <!-- <div class="col-md-12 col-small"> -->
                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Kids</label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="form-control" onkeypress="return AllowNumbersOnly(event)" name="kids" id="random" aria-describedby="Kids" onkeyup="total_value()" placeholder="" value='<?= $kid ?>'>
                            <input type="hidden" class="form-control" name="child_price" id="child_price" aria-describedby="fname" readonly="true">
                            <input type="hidden" class="form-control" onkeyup="total_value()" name="cost" id="cost" aria-describedby="cost" placeholder="Cost *" value="">
                            <input type="hidden" class="form-control"  readonly="true" name="addon_child_price" id="addon_child_price" aria-describedby="cost" placeholder="Cost *" value="">
                            <input type="hidden" class="form-control" onkeyup="total_value()" name="addon_cost" id="addon_cost" aria-describedby="cost" placeholder="Cost *" value="">
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Theme Park *</label>
                        </div>
                        <div class="right-col">
                            <select class="my-form theme_park" id="theme_park" name="theme_park_id" onchange="change_theme_park()" required>
                                <?php
                                foreach ($theme_parks as $theme_park) {
                                    $theme_selected = "";
                                    if (@$_GET['order_id']) {
                                        if ($user['theme_park_id'] == $theme_park['id']) {
                                            $theme_selected = "selected";
                                        }
                                    }
                                    ?>

                                    <option <?= $theme_selected ?> value="<?= $theme_park['id'] ?>" ><?= $theme_park['name'] . " (" . $theme_park['code'] . ")" ?></option>

                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Ticket Type *</label>
                        </div>

                        <div class="right-col">
                            <select class="my-form ticket_type" id="changeTicket" name="ticket_type" onchange="ticket(this.value)" required>
                            </select>
                        </div>

                    </div>


                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="addOn">AddOn</label>
                        </div>

                        <div class="right-col">
                            <select class="my-form AddOn" id="AddOn" name="AddOn" onchange="addOn(this.value)">

                            </select>
                        </div>

                    </div>

                    <?php if (!@$_GET['order_id'] || $_SESSION['level'] == 9){ ?>

                    <div class="col-md-12 d-flex">
                        <div class="left-col">
                            <label for="fname">Sales Person *</label>
                        </div>
                        <div class="right-col">
                            <div class="form-group d-flex">
                                <select class="form-control" id="sales_person" name="sales_personID" onchange="change_sales_person()" required>
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>


                                         <?php
                                        $loginID=$_SESSION["user_id"];
                                         
                                        $loginuser="SELECT id FROM partners  where loginID='$loginID' LIMIT 1";
                                   $loginuserquery = mysqli_query($db, $loginuser);
                                        
                                $loginuserresult = mysqli_fetch_assoc($loginuserquery);
                                       
                                        if($_SESSION['level'] < 9){
                                            if($sales_person['id']==$loginuserresult['id']   ){
                                                
                                           ?>
                                        <option selected
                                        value="<?= $sales_person['id'] ?>*****<?= $sales_person['setComission'] ?>" <?php if (isset($sales_personID) && $sales_personID == $sales_person['id']) { echo 'selected';} ?>><?= $sales_person['name'] ?></option>
                                         <?php
                                            }
                                            
                                        }
                                        else{?>
                                        
                                        <option <?php if(!@$_GET['order_id']) if($sales_person['id']==$loginuserresult['id'] ) { echo 'selected';} ?> 
                                        value="<?= $sales_person['id'] ?>*****<?= $sales_person['setComission'] ?>" <?php if (isset($sales_personID) && $sales_personID == $sales_person['id']) { echo 'selected';} ?>><?= $sales_person['name'] ?></option>
                                       
                                        <?php
                                            }
                                            
                                        }
                                    
                                    ?>

                                </select>
                                <input type="text" class="form-control" name="sales_commission" id="commission" value='<?php echo @$sales_commission ?>' placeholder="Commission" <?php  if($_SESSION['level'] <9) echo 'readonly' ?> required>
                            </div>
                        </div>
                    </div>
                    <?php } ?>


                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Discount *</label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="form-control" name="discount" id="discount" onkeypress="return AllowNumbersOnly(event)" onkeyup="total_value()" aria-describedby="total" value='<?php echo @$discount; ?>' placeholder="Discount *">
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="left-col">
                            <label for="total">Total Orders *</label>
                        </div>

                        <div class="right-col">
                            <input type="number" class="form-control" onkeypress="return AllowNumbersOnly(event)" name="total" id="total" aria-describedby="total" value='<?php echo $orderadd_data['total'] ?? ''; ?>' placeholder="Total Orders *" readonly>
                        </div>

                    </div>
                    <div class="col-md-12" style="display: none;">

                        <div class="left-col">
                            <label for="balance_due">Balance Due</label>
                        </div>

                        <div class="right-col">
                            <span id="balance_due">$0</span>
                            <!--<input type="number" class="form-control" name="balance_due" id="balance_due" aria-describedby="balance_due" value='0' placeholder="Balance Due" readonly>-->
                        </div>

                    </div>

                    <!-- Blance -->
                    <!--<div class="col-md-12">
                        <div class="left-col">
                            <label for="balance">Balance </label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="form-control" name="balance" id="balance" aria-describedby="balance" value='<?= $balance ?? 0 ?>' placeholder="Balance" readonly>
                        </div>
                    </div> -->
                    <!-- // Balance -->
                    <!-- Blance -->
                    <div class="col-md-12">
                        <div class="left-col">
                        </div>
                        <?php if($_SESSION['level'] == '9'): ?>
                            <div class="right-col">
                                <input type="checkbox" class="form-check-inline" name="is_hide" value="1" id="hidefor" aria-describedby="hide">
                                <label for="hidefor"></label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- // Balance -->
                    <!-- </div> -->

                </div>

                <div class="row text-center mt-2">
                    <div class="col form-group">
                        <button type="submit" name="<?php if(@$_GET['order_id']){ echo "updateorder";} else{ echo "orderadd";} ?>" class="btn btn-success form_submit" style="" id="addOrder">Submit</button>
                        <?php if (!@$_GET['order_id']) { ?>
                        <button type="submit" name="addentry" class="btn btn-dark form_submit" style="" id="addEntry">Add Another Day</button>
                        <?php }else if($_SESSION['level'] == '9'){ ?>
                        <button type="button" class="btn btn-danger delete_order">Delete</button>
                        <?php } ?>
                        <input type="hidden" class="submit_type">
                    </div>
                </div>

            </form>

        </div>

    </div>

    <!-- Sticky Footer -->

    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <!-- <span>Copyright © Universal Orlando Resort 2018</span> -->
            </div>
        </div>
    </footer>

</div>

<!-- /.content-wrapper -->

</div>

<!-- /#wrapper -->

<!-- Scroll to Top Button-->

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <!--<script src="../vendor/jquery/jquery.min.js"></script>-->
    <!--  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Core plugin JavaScript-->
    <!-- <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>-->
    <!-- Custom scripts for all pages-->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>-->-->



    <!--   <script src="../js/sb-admin.min.js"></script>-->

    <!--</script>-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
$('#datepicker').change(function () {
   
    var date=$('#datepicker').val();
    
     $.ajax({
            url:"update_slots.php",    //the page containing php script
            type: "get",    //request type,
            dataType: 'json',
            data: {status: "success", date:date},
            success:function(result){
                $('.time').html(result.timeslots)
            }
        });
    
    
    
    
    
});

        $(function () {
            $('.delete_order').click(function () {

                var order_id = '<?php echo @$_GET['order_id'] ?>';
                swal.fire({
                    //buttonsStyling: false,

                    html: "Are you sure you want to delete this order? Once deleted will not be restored.",
                    type: "question",

                    confirmButtonText: "Yes, delete!",
                    confirmButtonClass: "btn btn-sm btn-bold btn-danger-navigation-icon",

                    showCancelButton: true,
                    cancelButtonText: "No, cancel",
                    cancelButtonClass: "btn btn-sm btn-bold btn-default",
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        return fetch('delete_order.php?order_id=' + order_id)
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
                            title: 'Deleted!',
                            text: "Deleted Successfully.",
                            type: 'success',
                            buttonsStyling: false,
                            confirmButtonText: "Okay",
                            confirmButtonClass: "btn btn-sm btn-bold btn-primary",
                        }).then(function (result) {

                            // redirect here
                            window.location.href = "Orderdetails.php";

                        })
                    }
                })

            });


            $("#datepicker").datepicker();

        });
    </script>

    <script>


        $('.form_submit').click(function (e) {
            $('.submit_type').attr('name', $(this).attr('name'));

        })


        $('form').submit(function (e) {
            if($('#changeTicket').val() == ""){
                e.preventDefault();
                alert('Please select ticket type');
            }else {
                $('button[type="submit"]').prop('disabled', true);
            }
        })
        ticketTypes = [];

        <?php
        while ($parkvalue1 = mysqli_fetch_assoc($parkresult1)) {
        $ticket_type = $parkvalue1['ticket_name'];
        $adult_price = $parkvalue1['adult_price'];
        $ticketToShow = $ticket_type . ' $' . $adult_price;
        ?>
        ticketTypes.push({
            id: <?= $parkvalue1['id'] ?>,
            ticket_name: "<?= $parkvalue1['ticket_name'] ?>",
            adult_price: <?= $parkvalue1['adult_price'] ?>,
            ticketToShow: "<?php echo $ticket_type . ' $' . $adult_price; ?>",
            theme_park_id: "<?= $parkvalue1['theme_park_id'] ?>",
            add_on: "<?= $parkvalue1['addon'] ?>"
        });
        <?php
        }
        ?>


        if("<?php echo @$_GET['order_id'] ?>" != "") {
            var boolean = true; // created for update order ticket assignment
            var sales_person = '<?php echo @$sales_personID ?>';
            if (sales_person != ""){
      //          $('#sales_person').val(sales_person);
            }
        }
        else{
            var boolean = false;
        }
        function change_theme_park() {
            var theme_park_id = $('#theme_park').val();

            if(theme_park_id != 18 && theme_park_id != 46 && theme_park_id != 54 && theme_park_id != 55){
            //    $('#hidefor').prop('checked',true);
            // HIDDEN DUE TO ERROR --Not autopopulating -- hidden for autoselection of these four theme parks to be checked
            }

            var selectedTicketTypes = ticketTypes.filter(ticket_type => ticket_type.theme_park_id == theme_park_id);
            var selectedAddOnTypes = ticketTypes.filter(ticket_type => ticket_type.theme_park_id == theme_park_id && ticket_type.add_on == '1');
            var ticketTypePreviousVal = '<?php echo $orderadd_data['ticket_type']; ?>';
            var AddOnPreviousVal = '<?php echo $orderadd_data['AddOn']; ?>';
            $('#changeTicket').html('<option value="" selected>Please Select Ticket Type..</option>');
            $('#AddOn').html('<option value="">Please Select AddOn</option>');
            selectedTicketTypes.forEach(ticket_type => {
                var selected = ticket_type.ticketToShow === ticketTypePreviousVal ? '' : '';
                $('#changeTicket').append(`
                <option value="${ticket_type.ticketToShow}*****${ticket_type.id}" ${selected}>${ticket_type.ticketToShow}</option>
                `)
            })
            selectedAddOnTypes.forEach(ticket_type => {
                var selected = ticket_type.id === AddOnPreviousVal ? '' : '';
                $('#AddOn').append(`
                  <option value="${ticket_type.id}" ${selected}>${ticket_type.ticketToShow}</option>
                  `)
            })
            if (!boolean) {
                 $('#changeTicket[value=' + selectedTicketTypes + ']').prop("selected", "selected");
                 $('#AddOn[value=' + selectedAddOnTypes + ']').prop("selected", "selected");
            }
            var ticket_type = 0;
            if("<?php echo @$_GET['order_id'] ?>" != ""){
                if (boolean) {
                    ticket_type = "<?php echo $theme_ticket_type ?>*****<?php echo $ticket_type_id ?>";
                    add_on_type = "<?php echo $addon_ticket_type ?>";
                    boolean = false;
                }
            }
            console.log(ticket_type + " console");
            $('#changeTicket').val(ticket_type);
            $('#AddOn').val(add_on_type)
            ticket(ticket_type);
            addOn(add_on_type);
        }

       
        function change_sales_person() {
            var commission = document.getElementById("sales_person").value.split('*****');
            var sales_commission = commission['0']
            var comission_value = commission['1'];
            var adults = $("#adults").val();
            var random = $("#random").val();
            if (adults == ""){
                adults = 0;
            }
            if (random == ""){
                random = 0;
            }

            var total_guests = parseInt(adults) + parseInt(random);

            console.log("Sum of total guests:::", total_guests);


            console.log("Sales Commission ID :::: ", sales_commission);


            document.getElementById("commission").value = parseInt(comission_value) * parseInt(total_guests);
        }

        function ticket(id) {
            id = id.split("*****")[1];
            console.log(id);
            if (id == '0') {
                return false;
            }
            // var ticket_name = id;
            // var ticket = ticket_name.split(' ');
            // var ticket1 = ticket.pop();
            // var final_ticket2 = ticket.toString();
            // var final_ticket = final_ticket2.replace(/\,/g, " ");
            //
            //
            // var getprice = ticket1.split('$');
            // var getprice_new = getprice.pop();


            $.ajax({
                type: 'post',
                url: '../Ajax/Getorder.php',
                async: true,
                data:{ticket_id:id},
                success: function (data) {
                    console.log(data);
                    if (data == "No") {
                        $('#cost').val("");
                        //$('#numberofdays').val("");

                    } else {
                        let dataAll = JSON.parse(data);
                        console.log('dataAll :::  ', dataAll);
                        console.log(dataAll.adult_price);

                        var adults = $("#adults").val();
                        var random = $("#random").val()
                        var discount = $("#discount").val();

                        var total = parseInt(dataAll.adult_price) * parseInt(adults) + parseInt(dataAll.child_price) * parseInt(random) - discount;

                        $("#total").val(total);
                        $('#cost').val(dataAll.adult_price);
                        $('#child_price').val(dataAll.child_price);

                        total_value();
                    }
                }
            })


        }

        function addOn(id){
            console.log(id);
            if (id == '' || id=='undefined' || id==null) {
                $('#addon_cost').val("");
                $("#addon_child_price").val("");
                return false;
            }
            $.ajax({
                type: 'post',
                url: '../Ajax/Getorder.php',
                async: true,
                data:{ticket_id:id},
                success: function (data) {
                    console.log(data);
                    if (data == "No") {
                        $('#addon_cost').val("");
                        $("#addon_child_price").val("");
                        //$('#numberofdays').val("");

                    } else {
                        let dataAll = JSON.parse(data);
                        $('#addon_cost').val(dataAll.adult_price);
                        $('#addon_child_price').val(dataAll.child_price);

                        total_value();
                    }
                }
            })
        }

    </script>

    <script>
        function total_value() {
            var num1 = document.getElementById('adults').value;
            //console.log(num1);

            var num2 = document.getElementById('random').value;
            var num3 = document.getElementById('cost').value;
            var child_price = document.getElementById('child_price').value;
            var discount = document.getElementById('discount').value;
            var addOnPrice = document.getElementById('addon_cost').value;
            var addOnPriceChild = document.getElementById('addon_child_price').value;

            if (num1 && num2 && num3) {
                //console.log(num2);

                var data2 = parseInt(num1);
                var data3 = parseInt(num2);
                var price = $("#cost").val();
                var child_cost = $("#child_price").val();
                var totalPrice = data2 * price + data3 * child_cost;
                if(addOnPrice){
                    var addOnPricee  =  $("#addon_cost").val();
                    totalPrice = (parseInt(addOnPricee) * data2) + totalPrice;
                }
                if(addOnPriceChild){
                    var ChildPrice  =  $("#addon_child_price").val();
                    totalPrice = (parseInt(ChildPrice) * data3) + totalPrice;
                }
                totalPrice = totalPrice - discount;
                $("#total").val(totalPrice);

                //console.log(data2);
            } else if (num2 && child_price) {

                var data2 = parseInt(num2);
                var child_cost = $("#child_price").val();
                var totalPrice = data2 * child_cost;
                if(addOnPriceChild){
                    var ChildPrice  =  $("#addon_child_price").val();
                    totalPrice = (parseInt(ChildPrice) * data2) + totalPrice;
                }
                totalPrice = totalPrice - discount;
                $("#total").val(totalPrice);

            } else if (num1 && num3) {

                var data2 = parseInt(num1);
                var price = $("#cost").val();
                var totalPrice = data2 * price;
                if(addOnPrice){
                    var addOnPricee  =  $("#addon_cost").val();
                    totalPrice = (parseInt(addOnPricee) * data2) + totalPrice;
                }
                totalPrice = totalPrice - discount;
                $("#total").val(totalPrice);
            }

            //balance();
            calculate_due();
         var commission = document.getElementById("sales_person").value.split('*****');
            var sales_commission = commission['0']
            var comission_value = commission['1'];
            var adults = $("#adults").val();
            var random = $("#random").val();
            if (adults == ""){
                adults = 0;
            }
            if (random == ""){
                random = 0;
            }

            var total_guests = parseInt(adults) + parseInt(random);

            console.log("Sum of total guests:::", total_guests);


            console.log("Sales Commission ID :::: ", sales_commission);


            document.getElementById("commission").value = parseInt(comission_value) * parseInt(total_guests);
        }

        function calculate_due() {
            /*
             * balance due should be adults + kids *(multiply) that by ticket type, then subtract discount equals ORDER TOTAL then
             * subtract deposit, equals BALANCE DUE.
             * This same information will also be added to the ORDERS page where appropriate
             */
            var adults = $("#adults").val();    //adults
            var random = $("#random").val();   //kids
            var discount = $("#discount").val();
            var ticket_type = $('#cost').val();
            var deposit = $('#deposit').val();
            var total_person = parseInt(adults) + parseInt(random);
            console.log("total_person",total_person);
            var ticket_rate = total_person * ticket_type;
            console.log("adults",adults);
            console.log("kids",random);
            console.log("ticket_rate",ticket_rate);
            var due_total = (ticket_rate) - discount - deposit ;
            // $('#balance_due').html("$"+due_total);
        }

        // Calaculates balance
        function balance() {
            // let prev_balance = $('#prev_balance').val();
            var deposit = $("#deposit").val();
            let total = $("#total").val();

            console.log('previous balance :::::: ', prev_balance);
            new_balance = ((deposit - 0) - total);
            console.log((deposit - 0) + " " + total + " " + new_balance);
            $('#balance').val(new_balance);
        }

        // Check for numbers input
        function AllowNumbersOnly(e) {
            var code = (e.which) ? e.which : e.keyCode;
            if (code > 31 && (code < 48 || code > 57)) {
                e.preventDefault();
            }
        }


        if("<?php echo @$_GET['order_id'] ?>" != ""){
            if("<?php echo $is_hide ?>" == "1"){
                $('#hidefor').attr('checked','checked');
            }
            $('.time').val("<?php echo @$time ?>");
            $('#theme_park').val("<?php echo @$theme_park_id; ?>");
        }
        change_theme_park();

    </script>

    <script>
        function validateOrderData(e)
        {
            e.preventDefault();
            let valid = true;
            let total = document.querySelector('#total');

            if (total.value === '') {
                alert("Please fill all the required fields");
                valid = false;
            }

            if (valid === true) {
                e.target.removeEventListener('click', validateOrderData);
                e.target.click();
            }
        }

        document.getElementById('addOrder').addEventListener('click', validateOrderData);
        document.getElementById('addEntry').addEventListener('click', validateOrderData);


    </script>


    </body>

    </html>