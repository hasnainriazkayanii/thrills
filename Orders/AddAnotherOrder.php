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
$sql = "SELECT c.*, o1.date_of_visit, o1.adults, o1.kids FROM customer c LEFT JOIN `order` o1 ON (c.id = o1.customer_id) LEFT OUTER JOIN `order` o2 ON (c.id = o2.customer_id AND (o1.create_time < o2.create_time OR (o1.create_time = o2.create_time AND o1.id < o2.id))) WHERE o2.id is NULL AND c.id='$id'";
$result = mysqli_query($db, $sql);
$user = mysqli_fetch_assoc($result);
$id = $user['id'];
$first_name = $user['first_name'];
$Last_name = $user['Last_name'];
$name = $first_name . ' ' . $Last_name;
$adult = $user['adults'] ?? 0;
$kid = $user['kids'] ?? 0;
$date_of_visit = $user['date_of_visit'];
$date_submitted = date('m/d/Y', strtotime($date_of_visit));
$add_date = date('m/d/Y', strtotime($date_of_visit . '+1 day'));
$balance = $user['balance'] ?? 0;

//login check end
// order parameters
if (isset($_POST['userpay'])) {
    $orderID = $_POST['orderID'];
    $customerID = $_POST['customerID'];
    $paymentMethod = $_POST['paymentMethod'];
    $paymentAmount = $_POST['paymentAmount'];
    $theme_park_id = $_POST['theme_park_id'];

    $account = "INSERT INTO accounting(`orderID`,`customerID`, `paymentMethod`,`paymentAmount`) VALUES ('$orderID', '$customerID', '$paymentMethod','$paymentAmount')";
    
    $result = mysqli_query($db, $account);
    header("Location: text_payment_details.php?added_order=true&order_id=" . $orderID);

}

if (isset($_POST['orderadd'])) {
    $gateway = $_POST['gateway'];
    
    $customer_id = $_POST['idHidden'];
    $customer = $_POST['customer'];
    $date_of_vist1 = $_POST['date_of_vist'];
    

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
    $ticket_type = $_POST['ticket_type'];
    $ticket_type33 = (explode(" ", $ticket_type));
    $ticket_type2 = array_pop($ticket_type33);
    $ticket_type34 = implode(" ", $ticket_type33);
    $adults = ($_POST['adults'] && $_POST['kids'] !== '') ? $_POST['adults'] : 0;
    $new_balance = $_POST['balance'] ?? 0;

    $kids = ($_POST['kids'] && $_POST['kids'] !== '') ? $_POST['kids'] : 0;
    
    $no_of_days = $_POST['no_of_days'];

    $discount = $_POST['discount'] ?? 0;

    $total = $_POST['total'] ?? 0;

    $hideorder = $_POST['hideorder'] == true ? 1 : 0;
    
    $sales_personID = $_POST['sales_personID'];
    
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
    $order_insert = "INSERT INTO `order`(`order_id`,`ticket_id`,`ticket_type`, `customer_id`,`customer`, `date_of_visit`,`time`,`option`, `price`,`no_of_days`, `adults`, `kids`,`discount`, `total`,`create_time`,`ticket_order`, `theme_park_id`, `is_hide`, `sales_personID`, `sales_commission`, `deposit`, `gateway`)  

                        VALUES ('$bdt_user','','$ticket_type','$customer_id','$customer',' $date_of_vist','$aa12','$option','$price','0','$adults','$kids','$discount','$total','$timestamp','$ticket_order', $theme_park_id, $hideorder, '$sales_personID', '$sales_commission', '$deposit', '$gateway')";
    $result = mysqli_query($db, $order_insert);


            $client_1 = new Client($account_sid, $auth_token);
            try
            {
                $query_admin_number = "SELECT group_concat(value) as numbers from settings where id in (1,2)";
                 $admin_number = mysqli_fetch_assoc(mysqli_query($db,$query_admin_number))['numbers'];                
                $numbers = explode(',',$admin_number);
                foreach ($numbers as $v) {
                $resp_1 = $client_1->messages->create(
                    $v,
                    array(
                        'from' => $twilio_number,
                        'body' => "$sales_name ($".$sales_commission.") scheduled $customer for $adults/$kids $theme_park_name $ticket_type34 $visit_date for $".$total
                    )
                );
                }

            }
            catch (Exception $e) { 	var_dump($e);}



    header("Location: text_order_details.php?added_order=true&order_id=" . $bdt_user);
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
    $ticket_type = $_POST['ticket_type'];
    $ticket_type33 = (explode(" ", $ticket_type));
    $ticket_type2 = array_pop($ticket_type33);
    $ticket_type34 = implode(" ", $ticket_type33);
    $adults = ($_POST['adults'] && $_POST['kids'] !== '') ? $_POST['adults'] : 0;
    $new_balance = $_POST['balance'] ?? 0;

    $kids = ($_POST['kids'] && $_POST['kids'] !== '') ? $_POST['kids'] : 0;
    
    $no_of_days = $_POST['no_of_days'];

    $discount = $_POST['discount'] ?? 0;

    $total = $_POST['total'] ?? 0;

    $hideorder = $_POST['hideorder'] == true ? 1 : 0;
    
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
    $order_insert = "INSERT INTO `order`(`order_id`,`ticket_id`,`ticket_type`, `customer_id`,`customer`, `date_of_visit`,`time`,`option`, `price`,`no_of_days`, `adults`, `kids`,`discount`, `total`,`create_time`,`ticket_order`, `theme_park_id`, `is_hide`, `sales_personID`, `sales_commission`, `deposit`, `gateway`)  

                        VALUES ('$bdt_user','','$ticket_type','$customer_id','$customer',' $date_of_vist','$aa12','$option','$price','0','$adults','$kids','$discount','$total','$timestamp','$ticket_order', $theme_park_id, $hideorder, '$sales_personID', '$sales_commission', '$deposit', '$gateway')";
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

            <h3>Add Order</h3>

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
    $parktypesvalue = "SELECT * FROM tickettypes";

    $parkresult1 = mysqli_query($db, $parktypesvalue);

    $orderadd_data = isset($_SESSION['orderadd_data']) ? $_SESSION['orderadd_data'] : [];
    ?>

    <div class="container-fluid" style="display:flex;">

        <div class="col-md-6 ">

            <form action="Addorders.php" autocomplete='off' autocomplete='off' method="post">

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
                            $current_date = date('m/d/Y');
                            if (date('H') > 12) {
                                $current_date = date('m/d/Y', strtotime($current_date . ' +1 day'));
                            }
                            ?>
                            <input type="text" required name="date_of_vist" id="datepicker" class="form-control" value='<?= $add_date; ?>'>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Time *</label>
                        </div>

                        <div class="right-col">
                            <select class="form-control" name="time" required>
                                <option value="8:00 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '8:00 AM') echo 'selected'; ?>>8:00 AM</option>
                                <option value="8:30 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '8:30 AM') echo 'selected'; ?>>8:30 AM</option>
                                <option value="9:00 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '9:00 AM') echo 'selected'; ?>>9:00 AM</option>
                                <option value="9:30 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '9:30 AM') echo 'selected'; ?>>9:30 AM</option>
                                <option value="10:00 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '10:00 AM') echo 'selected'; ?>>10:00 AM</option>
                                <option value="10:30 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '10:30 AM') echo 'selected'; ?>>10:30 AM</option>
                                <option value="11:00 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '11:00 AM') echo 'selected'; ?>>11:00 AM</option>
                                <option value="11:30 AM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '11:30 AM') echo 'selected'; ?>>11:30 AM</option>
                                <option value="12:00 PM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '12:00 PM') echo 'selected'; ?>>12:00 PM</option>
                                <option value="12:30 PM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '12:30 PM') echo 'selected'; ?>>12:30 PM</option>
                                <option value="1:00 PM" <?php if (isset($orderadd_data['time']) && $orderadd_data['time'] === '1:00 PM') echo 'selected'; ?>>1:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Adults *</label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="form-control"onkeypress="return AllowNumbersOnly(event)" name="adults" id="adults" aria-describedby="adults" onkeyup="total_value()" placeholder="0" value='<?= $adult ?>' required>

                        </div>
                    </div>

                    <!-- </div> -->

                    <!-- <div class="col-md-12 col-small"> -->
                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Kids</label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="form-control" onkeypress="return AllowNumbersOnly(event)" name="kids" id="random" aria-describedby="Kids" onkeyup="total_value()" placeholder="0" value='<?= $kid ?>'>
                            <input type="hidden" class="form-control" name="child_price" id="child_price" aria-describedby="fname" readonly="true">
                            <input type="hidden" class="form-control" onkeyup="total_value()" name="cost" id="cost" aria-describedby="cost" placeholder="Cost *" value="">
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Theme Park *</label>
                        </div>
                        <div class="right-col">
                            <select class="my-form" id="theme_park" name="theme_park_id" onchange="change_theme_park()" required>

                                <?php
                                foreach ($theme_parks as $theme_park) {
                                    ?>

                                    <option value="<?= $theme_park['id'] ?>" <?php if (isset($orderadd_data['theme_park_id']) && $orderadd_data['theme_park_id'] === $theme_park['id']) echo 'selected'; ?>><?= $theme_park['name'] . " (" . $theme_park['code'] . ")" ?></option>

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
                            <select class="my-form" id="changeTicket" name="ticket_type" onchange="ticket(this.value)" required>

                            </select>
                        </div>

                    </div>
                    
                    <div class="col-md-12 d-flex">
                        <div class="left-col">
                            <label for="fname">Sales Person *</label>
                        </div>
                        <div class="right-col">
                            <div class="form-group d-flex">
                                <select class="form-control" id="sales_person" name="sales_personID" onchange="change_sales_person()" required>
                                    <option value="" selected>Select Sales Person</option>
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>
    
                                        <option value="<?= $sales_person['id'] ?>" <?php if (isset($orderadd_data['sales_personID']) && $orderadd_data['sales_personID'] === $sales_person['id']); ?>><?= $sales_person['name'] ?></option>
    
                                        <?php
                                    }
                                    ?>
    
                                </select>
                                <input type="text" class="form-control" name="sales_commission" id="commission" value='0' placeholder="Commission" required>
                            </div>
                        </div>
                    </div>

                    <!-- Deposit -->
                    <div class="col-md-12 d-flex">
                        <div class="left-col">
                            <label hidden for="deposit">Deposit</label>
                        </div>

                        <div class="right-col">
                            <div class="form-group d-flex">
                                <select name="gateway" id="gateway" class="form-control" hidden>
                                    <option value="" selected> Select Payment Method </option>
                                    <option value="Cash" <?php if (isset($orderadd_data['gateway']) && $orderadd_data['gateway'] === 'Cash') echo 'selected'; ?>>Cash </option>
                                    <option value="Website" <?php if (isset($orderadd_data['gateway']) && $orderadd_data['gateway'] === 'Website') echo 'selected'; ?>>Website</option>
                                    <option value="Venmo" <?php if (isset($orderadd_data['gateway']) && $orderadd_data['gateway'] === 'Venmo') echo 'selected'; ?>>Venmo</option>
                                    <option value="Zelle" <?php if (isset($orderadd_data['gateway']) && $orderadd_data['gateway'] === 'Zelle') echo 'selected'; ?>>Zelle</option>
                                    <option value="Cashapp" <?php if (isset($orderadd_data['gateway']) && $orderadd_data['gateway'] === 'Cashapp') echo 'selected'; ?>>Cashapp</option>
                                    <option value="Paypal" <?php if (isset($orderadd_data['gateway']) && $orderadd_data['gateway'] === 'Paypal') echo 'selected'; ?>>Paypal</option>
                                </select>

                                <input type="text" class="form-control" name="deposit" id="deposit" onkeypress="return AllowNumbersOnly(event)" onkeyup="total_value()" aria-describedby="Deposit" value='0' placeholder="Deposit Amount" hidden>
                            </div>
                        </div>

                    </div>
                    <!-- //Deposit -->

                    <div class="col-md-12">
                        <div class="left-col">
                            <label for="fname">Discount *</label>
                        </div>

                        <div class="right-col">
                            <input type="text" class="form-control" name="discount" id="discount" onkeypress="return AllowNumbersOnly(event)" onkeyup="total_value()" aria-describedby="total" value='' placeholder="Discount *">
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
                    <div class="col-md-12">

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
                        <?php if ((int) $level >= 8): ?>
                            <div class="right-col">
                                <input type="checkbox" class="form-check-inline" name="hideorder" id="hidefor" aria-describedby="hide">
                                <label for="hidefor">Hide for other users.</label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- // Balance -->


                    <!-- </div> -->

                </div>

                <div class="row text-center mt-2">
                    <div class="col form-group">
                        <button type="submit" name="orderadd" class="btn btn-success" style="" id="addOrder">Submit</button>
                        
                        <button type="submit" name="addentry" class="btn btn-dark" style="" id="addEntry">Add Another Day</button>
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

    <script>
                                $(function () {

                                    $("#datepicker").datepicker();

                                });
    </script>

    <script>
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
                theme_park_id: "<?= $parkvalue1['theme_park_id'] ?>"
            });
    <?php
}
?>

        console.log('ticket types');
        console.log(ticketTypes);


        function change_theme_park() {
            var theme_park_id = $('#theme_park').val();

            console.log("Theme park ID :::::  ", theme_park_id);

            var selectedTicketTypes = ticketTypes.filter(ticket_type => ticket_type.theme_park_id == theme_park_id);
            console.log(selectedTicketTypes);

            var ticketTypePreviousVal = '<?php echo $orderadd_data['ticket_type']; ?>';
            $('#changeTicket').html('<option value="" selected>Please Select Ticket Type..</option>');
            selectedTicketTypes.forEach(ticket_type => {
                var selected = ticket_type.ticketToShow === ticketTypePreviousVal ? '' : '';
                $('#changeTicket').append(`
                  <option value="${ticket_type.ticketToShow}" ${selected}>${ticket_type.ticketToShow}</option>
                  `)
            })

            $('#changeTicket [value=' + selectedTicketTypes + ']').prop("selected", "selected");

            $('#changeTicket').val('0');
            ticket('0');
        }

        function change_sales_person() {
            var sales_commission = document.getElementById("sales_person").value;
            var adults = $("#adults").val();
            var random = $("#random").val(); 
            
            var total_guests = parseInt(adults) + parseInt(random);
            
            console.log("Sum of total guests:::", total_guests);

            
            console.log("Sales Commission ID :::: ", sales_commission);


             if (sales_commission == "1") {
                document.getElementById("commission").value = "0" * total_guests;
            }

            else if (sales_commission == "2") {
                document.getElementById("commission").value = "0" * total_guests;
            }
            else if (sales_commission == "3") {
                document.getElementById("commission").value = "10" * total_guests;
            }
            else if (sales_commission == "4") {
                document.getElementById("commission").value = "10" * total_guests;
            }
            else {
                document.getElementById("commission").value = "0";
            }
        }

        function ticket(id) {
            if (id == '0') {
                return false;
            }
            var ticket_name = id;
            var ticket = ticket_name.split(' ');
            var ticket1 = ticket.pop();
            var final_ticket2 = ticket.toString();
            var final_ticket = final_ticket2.replace(/\,/g, " ");
            
            
            var getprice = ticket1.split('$');
            var getprice_new = getprice.pop();
            
            
            $.ajax({
                type: 'post',
                url: '../Ajax/Getorder.php',
                async: true,
                data:{final_ticket:final_ticket,getprice_new:getprice_new},
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

    </script>

    <script>
        function total_value() {
            var num1 = document.getElementById('adults').value;
            //console.log(num1);

            var num2 = document.getElementById('random').value;
            var num3 = document.getElementById('cost').value;
            var child_price = document.getElementById('child_price').value;
            var discount = document.getElementById('discount').value;

            if (num1 && num2 && num3) {
                //console.log(num2);

                var data2 = parseInt(num1);
                var data3 = parseInt(num2);
                var price = $("#cost").val();
                var child_cost = $("#child_price").val();
                var totalPrice = data2 * price + data3 * child_cost - discount;
                $("#total").val(totalPrice);

                //console.log(data2);
            } else if (num2 && child_price) {

                var data2 = parseInt(num2);
                var child_cost = $("#child_price").val();
                var totalPrice = data2 * child_cost - discount;

                $("#total").val(totalPrice);

            } else if (num1 && num3) {

                var data2 = parseInt(num1);
                var price = $("#cost").val();
                var totalPrice = data2 * price - discount;

                $("#total").val(totalPrice);
            }

            //balance();
            calculate_due();

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
            $('#balance_due').html("$"+due_total);
        }

        // Calaculates balance
        function balance() {
            let prev_balance = $('#prev_balance').val();
            var deposit = $("#deposit").val();
            let total = $("#total").val();

            console.log('previous balance :::::: ', prev_balance);
            new_balance = (prev_balance - 0) + (deposit - 0) - total;
            $('#balance').val(new_balance);
        }

        // Check for numbers input
        function AllowNumbersOnly(e) {
            var code = (e.which) ? e.which : e.keyCode;
            if (code > 31 && (code < 48 || code > 57)) {
                e.preventDefault();
            }
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
        
        $('#theme_park').change(function(){
            
            var park_id  = this.value;
            if(park_id != 18){
                $('#hidefor').prop('checked',true);
            }
            
        })
        
        
    </script>


</body>

</html>