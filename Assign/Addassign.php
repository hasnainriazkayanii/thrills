<?php
session_start();

include('../Config/Connection.php');

$login_check = $_SESSION['id'];

$sql="SELECT * FROM `login_user` where email='".$_SESSION['login_user']."' AND level IN ('9','2') ";

$auth_user = mysqli_query($db, $sql);
while($roww=mysqli_fetch_assoc($auth_user)){
    $auth_email=$roww['email'];
}
if($auth_email){
    $custom_class="";
    $disabled="";
    $required="required";
}else{
    $custom_class="hide_select";
    $disabled="disabled";
    $required="";
}


//var_dump($data1);

if ($login_check != '1') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $full_url = $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION['intended_url'] = $full_url;
    header("location: ../Login/login.php");
}

$orderno = $_GET['id'];
$sqlGuest = "SELECT * FROM `order` where id='$orderno'";
$resultGuest = mysqli_query($db, $sqlGuest);
$GetOrderId = mysqli_fetch_assoc($resultGuest);
$loginId = $GetOrderId['order_id'];

if (isset($_POST['customerdata'])) {


    $sqlGuest = "SELECT * FROM `order` where id='$orderno'";
    $resultGuest = mysqli_query($db, $sqlGuest);

    $GetOrderId = mysqli_fetch_assoc($resultGuest);
    $loginId = $GetOrderId['order_id'];

    $sqlGuest = "SELECT * FROM `guest` where order_id='$orderno'";

    $resultGuest = mysqli_query($db, $sqlGuest);

    if (mysqli_num_rows($resultGuest) > 0) {
        $loopCount = count($_POST['guest']);


        for ($i = 0; $i < $loopCount; $i++) {

            $orderId = $_POST['ordernumber'];
            $Name = $_POST['guest'][$i];
            $ticketId = $_POST['ticket'][$i];
            $mobile = $_POST['mobile'][$i];
            $country_code = $_POST['country_code'][$i];
            $country_code_name = $_POST['country_code_name'][$i];

            $mobile = str_replace('-', '', $mobile);
            $mobile = str_replace(' ', '', $mobile);
            $mobile = str_replace('(', '', $mobile);
            $mobile = str_replace(')', '', $mobile);

            $type = $_POST['type'][$i];
            $gid = $_POST['gid'][$i];

            $sql44 = "SELECT * FROM `ticket` where ticketshowid ='$ticketId'";
            $result44 = mysqli_query($db, $sql44);

            $user44 = mysqli_fetch_assoc($result44);


            $entitlement = $user44['entitlement'];

            if ($gid) {

                $guest_update = "UPDATE guest SET order_id='$orderId',guest_name='$Name',ticket_id='$ticketId',login_id='$loginId',guest_mobile='$mobile',country_code='$country_code',country_code_name='$country_code_name',type='$type',entitlement='$entitlement' where id=$gid";

                $Guestresult = mysqli_query($db, $guest_update);
            } else {

                $guest_insert = "INSERT INTO guest (order_id,guest_name,ticket_id,login_id,country_code,country_code_name,guest_mobile,type,entitlement,isdisabled)
        VALUES ('$orderId','$Name','$ticketId','$loginId','$country_code','$country_code_name','$mobile','$type','$entitlement',1)";

                $Guestresult = mysqli_query($db, $guest_insert);

            }

            $order_assigned = "UPDATE `order` SET assign='1' WHERE id='$orderId'";

            $data_assigned = mysqli_query($db, $order_assigned);

            // $resultDeleteGuest = mysqli_query($db, $guest_update);

            header("Location: ../Orders/Orderdetails.php?active=0");
        }
        exit();

    }
    else {

        $loopCount = count($_POST['guest']);

        for ($i = 0; $i < $loopCount; $i++) {

            $orderId = $_POST['ordernumber'];
            $country_code = $_POST['country_code'][$i];
            $country_code_name = $_POST['country_code_name'][$i];
            $Name = $_POST['guest'][$i];
            $ticketId = $_POST['ticket'][$i];
            $mobile = $_POST['mobile'][$i];
            $type = $_POST['type'][$i];

            $mobile = str_replace('-', '', $mobile);
            $mobile = str_replace(' ', '', $mobile);
            $mobile = str_replace('(', '', $mobile);
            $mobile = str_replace(')', '', $mobile);

            $sql44 = "SELECT * FROM `ticket` where ticketshowid ='$ticketId'";

            $result44 = mysqli_query($db, $sql44);

            $user44 = mysqli_fetch_assoc($result44);

            $entitlement = $user44['entitlement'];

            // Get latitude and longitude
            $sql = "SELECT * FROM `customer` WHERE id={$GetOrderId['customer_id']}";
            $result = mysqli_query($db, $sql);
            $customer = mysqli_fetch_assoc($result);

            $address = $customer['homecity'];
            $address = urlencode($address);

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyAI0TJsSinxPjQXjFj9yDm0bvgHjHN9WsM";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $resp = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($resp);

            $lat = $resp->results[0]->geometry->location->lat ?? 0;
            $lng = $resp->results[0]->geometry->location->lng ?? 0;

            $guest_insert = "INSERT INTO guest (order_id,guest_name,ticket_id,login_id,country_code,country_code_name,guest_mobile,type,entitlement, isdisabled, user_lat, user_long)

      VALUES ('$orderId','$Name','$ticketId','$loginId','$country_code','$country_code_name','$mobile','$type','$entitlement', 1, '$lat', '$lng')";

            $Guestresult = mysqli_query($db, $guest_insert);

            $sql44 = "SELECT * FROM `ticket` where ticketshowid ='$ticketId'";

            $result44 = mysqli_query($db, $sql44);

            $user44 = mysqli_fetch_assoc($result44);

            $order_update66 = "UPDATE `order` SET assign=1 WHERE id='$orderno'";

            $data22 = mysqli_query($db, $order_update66);
        }
        $order_assigned = "UPDATE `order` SET assign='1' WHERE id='$orderId'";

        $data_assigned = mysqli_query($db, $order_assigned);

        header("Location: ../Orders/Orderdetails.php?active=0");
    }
}
include('../includes/header.php');
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>


<style>

    .select2-selection{
        height: auto !important;
    }
    .select2-selection__rendered{
        word-break: break-all !important;
        width: auto !important;
        white-space: pre-line !important;
    }

    .selection{
        width: 100%;
    }

    .my-form {
        width: 100%;

        height: 38px;

        border-radius: 5px;

        border: 1px solid #33333338;
    }

    .hide_select{
        display:none!important;
    }

    .tt-query,
    / UPDATE: newer versions use tt-input instead of tt-query / .tt-hint {

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
    / UPDATE: newer versions use tt-input instead of tt-query / box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);

    }



    .tt-hint {

        color: #999;

    }



    .tt-menu {
    / UPDATE: newer versions use tt-menu instead of tt-dropdown-menu / width: 100%;

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
    / UPDATE: newer versions use .tt-suggestion.tt-cursor / color: #fff;

        background-color: #0097cf;



    }
div#showticketusage {
    overflow: auto !important;
}


    .tt-suggestion p {

        margin: 0;

    }




    .block {

        display: block;

        border: 1px solid #ccc;

        padding: 20px;

    }

    input {

        width: 50%;

        display: inline-block;

    }

    span {

        display: inline-block;

        cursor: pointer;



    }

    .add-btn {
        background-color: #212529;

        padding: 5px 17px;

        color: #fff;

        text-decoration: none;
    }

    .red {
        background-color: red;

        padding: 5px 10px;

        color: #fff;

        margin-bottom: 10px;

        text-decoration: none;
    }
</style>


<div id="content-wrapper">
    <div class="container-fluid">

        <div class="col-md-12">
            <?php
            if (isset($_SESSION['success_msg'])) : ?>
                <div id=messagediv class="alert alert-success">
                    <strong><?php echo $_SESSION['success_msg'];
                        unset($_SESSION['success_msg']) ?></strong>
                </div>
            <?php endif; ?>

            <h3>Assign Tickets</h3>
            <hr>
        </div>



        <div class="row">
            <!--<div class="col-md-6">
              <h4>Order Id</h4>
              </div>  -->
            <div class="col-md-12">
                <h4 style=" text-align: center"><?= $loginId ?></h4>
                <hr>
            </div>
        </div>

    </div>

    <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
        <div class="col-md-8">

            <form name="customerdata" action="" method="POST">
                <div class="optionBox">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" class="typeahead form-control" required name="ordernumber" id="order" value="<?= $orderno ?>" placeholder="Order No.">
                        </div>
                    </div>
                    <?php
                    $sqlCustomer = "SELECT `order`.*, `customer`.*, 
                                      theme_parks.theme_park_parent_id as theme_park_parent_id, 
                                      theme_parks.name as theme_park_name, 
                                      theme_park_parents.is_universal as is_universal, 
                                      theme_park_parents.code as theme_park_code 
                                FROM `order` 
                                join customer on `order`.customer_id=`customer`.id 
                                LEFT JOIN theme_parks on `order`.theme_park_id=`theme_parks`.id
                                LEFT JOIN theme_park_parents on `theme_parks`.theme_park_parent_id=`theme_park_parents`.id
                                where `order`.id='$orderno'";
                    ///// get order details;
                    $resultCustomer = mysqli_query($db, $sqlCustomer);

                    $GetCustomer = mysqli_fetch_assoc($resultCustomer);
                    if (!$GetCustomer) {
                        echo "Customer with this id not find in our database";
                        exit;
                    }

                    // theme park data for certain order
                    // $tp_id = $GetCustomer['theme_park_id'];
                    // $tp_universal = $GetCustomer['theme_park_univeral'];
                    // $tp_code = $GetCustomer['theme_park_code'];
                    $current_date = date("Y-m-d");
                    $tpp_id = $GetCustomer['theme_park_parent_id'];    //// theme park parent id
                    $sql_ticket = "SELECT ticket.*,  theme_park_parents.is_universal as tp_universal, theme_park_parents.code as tp_code
                                FROM ticket LEFT JOIN theme_park_parents ON ticket.theme_park_parent_id=theme_park_parents.id
                                where theme_park_parents.id='$tpp_id' and expire_date >= '$current_date' and ticket.active='True' ";
//                    var_dump($sql_ticket);
                    $CustomerName = $GetCustomer['customer'];
                    $cusMobile = $GetCustomer['Phone_number'];
                    $Customer_country_code = $GetCustomer['country_code_name'];

                    // Mask the mobile field if account type is sale
                    $mobile_field_type = '';
                    if ((int) $level === 1) {
                        $mobile_field_type = 'password';
                    } else {
                        $mobile_field_type = 'tel';
                    }

                    $sqlUpdateCheck = "SELECT * FROM `guest` where order_id='$orderno' ORDER BY `guest`.`type` ASC ";

                    $resultGuestUpdate = mysqli_query($db, $sqlUpdateCheck);

                    if (mysqli_num_rows($resultGuestUpdate) > 0) {

                        $sqlKidCount = "SELECT * FROM guest where order_id='$orderno' and type='kid'";
                        $resultKidCount = mysqli_query($db, $sqlKidCount);
                        $TotalKidCount = mysqli_num_rows($resultKidCount);
                        $sqlAdultCount = "SELECT * FROM guest where order_id='$orderno' and type='adult'";
                        $resultAdultCount = mysqli_query($db, $sqlAdultCount);
                        $TotalAdultCount = mysqli_num_rows($resultAdultCount);

                        $countAdult = 0;
                        $countKid = 0;
                        $e = 0;
                        $DbCountAdult = $GetCustomer['adults'];


                        $DbCountKid = $GetCustomer['kids'];
                        $country_index =0;
                        $colorArray = array();

                        while ($GuestsUpdate = mysqli_fetch_assoc($resultGuestUpdate)) {

                            if ($GuestsUpdate['inactive'] == 0) {
                                $active = "Active";
                                $clasname = "btn btn-success";
                            } else {
                                $active = "Inactive";
                                $clasname = "btn btn-danger";
                            }
                            if ($GuestsUpdate['isdisabled'] == 0) {
                                $active_log = "LogIn";
                                $Loginclasname = "btn btn-success";
                            } else {
                                $active_log = "LogOut";
                                $Loginclasname = "btn btn-danger";
                            }
                            if ($GuestsUpdate['type'] == "adult") {
                                $countAdult++;
                                $sCount = $countAdult;
                            }
                            if ($GuestsUpdate['type'] == "kid") {
                                $countKid++;
                                $sCount = $countKid;
                            }

                            $e++;


                            if (($DbCountAdult >= $countAdult && $GuestsUpdate['type'] == "adult") ) {
                                echo "<input type='hidden' name='country_code[".($country_index)."]' value='" . $GuestsUpdate['country_code'] . "'>";
                                echo "<input type='hidden' name='country_code_name[".($country_index)."]' value='" . $GuestsUpdate['country_code_name'] . "'>";

                                $country_index++;

                                echo "<div class='block'>
                            <div class='row'>
                              <div class='col-md-6'>
                                <input type=hidden name='type[]' value='" . $GuestsUpdate['type'] . "'>
                                <input type=hidden name='gid[]' value='" . $GuestsUpdate['id'] . "'>
                                
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>" . ucfirst($GuestsUpdate['type']) . " Guest  " . $sCount . "*</label>
                                  <input type='text' class='typeahead form-control typeahead" . $e . "'  name='guest[]' value='" . $GuestsUpdate['guest_name'] . "' placeholder='Name' >
                                </div>
                              </div>
                              <div class='col-md-6'>
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>Phone Number*</label>
                                  <input type='".$mobile_field_type."' class='typeahead form-control'  country-code-name='".$GuestsUpdate['country_code_name']."' country='".$GuestsUpdate['country_code']."' name='mobile[]' value='" . $GuestsUpdate['guest_mobile'] . "'  placeholder='Phone Number' >
                                </div>
                              </div>
                              <div class='col-md-6 ". $custom_class."'>

                                <div class='form-group'>
                                  <label style='display: block;'>Ticket</label>

                                  <select id='selectPark" . $e . "' data-placeholder='Please Select Ticket 1' class='change adult_select form-control my' name='ticket[]' ". $disabled." onchange='sel_park($(this))'> <option value=''>Please Select Ticket 1 </option>";

                                if ($GuestsUpdate['type'] == "adult") {
                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                    $sqlTicket = $sql_ticket . "and type<>'child'";
                                } else {
                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'";
                                    $sqlTicket = $sql_ticket . "and type<>'adult' and type<>'youth'";
                                }
                                $sqlGetTicket = $sqlTicket . " order by expire_date asc";

                                $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                $ticket_count = 1;
                                if (mysqli_num_rows($resultGetTicket) > 0) {
                                    while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                        include('ticket_item.php');
                                        $ticket_count = $ticket_count + 1;
                                    }
                                }

                                // $guest_history="SELECT * FROM guest";
                                // $result=mysqli_query($db,$guest_history);
                                // $order_individual=mysqli_fetch_assoc($result);
                                // $indiv_id=$order_individual['id'];

                                $sql = "SELECT * FROM `screenshot` WHERE gid={$GuestsUpdate['id']}";
                                $result = mysqli_query($db, $sql);
                                $screenshot = mysqli_fetch_assoc($result);
                                if ($screenshot)
                                    $screenshot_message = "Screenshot Attempted<br><b>OrderID: {$screenshot['login_id']}</b>";
                                else
                                    $screenshot_message = "";


                                echo "</select>
                                </div>
                              </div>

                              <div class='col-md-6 text-danger'>
                                ".$screenshot_message."
                              </div>";
                                if($tpp_id==1){
                                    if ((int) $level > 1) {
                                        echo   "<div class='d-flex flex-wrap w-100 justify-content-start justify-content-md-end'>
                                              <a href='javascript:void(0);' onclick='show(".$GuestsUpdate['id'].")' class='btn btn-dark mr-2 mb-1' role='button' >Show Usage</a>
                                                <a href='tel:".$GuestsUpdate['country_code'] . $GuestsUpdate['guest_mobile'] . "' class='btn btn-primary mr-2 mb-1' role='button' >Call</a>
            
                                              <a href='../Orders/individual_text_history.php?id=" . $GuestsUpdate['id'] . "' class='btn btn-primary mr-2 mb-1' role='button' >Text Usage</a>
            
                                              <a href='../Ajax/Inactive.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "' class='" . $clasname . " mr-2 mb-1' role='button'>" . $active . "</a>
            
                                              <a href='../Ajax/DisableUser.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "&mobile=" . $GuestsUpdate['guest_mobile'] . "' class='" . $Loginclasname . " mr-2 mb-1' role='button'>" . $active_log . "</a>
                                              </div>";
                                    }

                                }
                                else{
                                    if ((int) $level > 1) {
                                        echo   "<div class='d-flex flex-wrap w-100 justify-content-start justify-content-md-end'>
                                          <a href='javascript:void(0);'  onclick='show(".$GuestsUpdate['id'].")' class='btn btn-dark mr-2 mb-1' role='button' >Show Usage</a>
                                            <a href='tel:".$GuestsUpdate['country_code'] . $GuestsUpdate['guest_mobile'] . "' class='btn btn-primary mr-2 mb-1' role='button' >Call</a>
        
        
                                          <a href='../Ajax/Inactive.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "' class='" . $clasname . " mr-2 mb-1' role='button'>" . $active . "</a>
        
                                          <a href='../Ajax/DisableUser.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "&mobile=" . $GuestsUpdate['guest_mobile'] . "' class='" . $Loginclasname . " mr-2 mb-1' role='button'>" . $active_log . "</a>
                                          </div>";
                                    }
                                }


                                echo          "</div>
                          </div>
                          <script> if ('".$GuestsUpdate['ticket_id']."' != ''){ $('#selectPark" . $e . "').val('" . $GuestsUpdate['ticket_id'] . "');}</script>";

                                if ($DbCountAdult > $TotalAdultCount) {

                                    $AddNewRow = $DbCountAdult - $TotalAdultCount;
                                    if ($TotalAdultCount == $countAdult) {

                                        $checkAddRow = 0;

                                        while ($AddNewRow > $checkAddRow) {

                                            $checkAddRow++;
                                            $sCount++;

                                            echo "<div class='block'>
                                  <div class='row'>
                                    <div class='col-md-12'>
                                <input type='hidden' name='country_code[$country_index]'>
                                <input type='hidden' name='country_code_name[$country_index]'>
                                    
                                      <input type=hidden name='type[]' value='adult'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Adult Guest " . $sCount . "*</label>
                                        <input type='text' class='typeahead form-control typeahead" . $sCount . "'  required name='guest[]'  placeholder='Name' >
                                      </div>
                                    </div>

                                    <div class='col-md-6'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Phone Number*</label>
                                        <input type='text' class='typeahead form-control' required name='mobile[]'  placeholder='Phone Number' >
                                      </div>
                                    </div>
                                    <div class='col-md-6 ". $custom_class."'>
                                      <div class='form-group'>
                                        <label style='display: block;'>Ticket</label>
                                        <select id='selectPark" . $sCount . "' ". $disabled ." name='ticket[]' class='change adult_select form-control my' onchange='sel_park($(this))'><option value=''>Please Select Ticket 2 </option>";
                                            $country_index++;
                                            //$sqlGetTicket = "SELECT * FROM ticket";
                                            //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                            $sqlGetTicket = $sql_ticket . "and type<>'child' and type<>'youth' order by expire_date asc";
//                                            var_dump($sqlGetTicket);
                                            $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                            $ticket_count = 1;
                                            if (mysqli_num_rows($resultGetTicket) > 0) {

                                                while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                                    include('ticket_item.php');
                                                    $ticket_count = $ticket_count + 1;
                                                }
                                            }

                                            echo "</select>
                                      </div>
                                    </div>
                                  </div>
                                </div>";
                                        }
                                    }
                                }
                            }

                            if($DbCountAdult >= $countAdult && $countAdult == 0){ ?>
                                <div class='block'>
                                    <div class='row'>
                                        <div class='col-md-12'>
                                            <input type=hidden name='type[]' value='adult'>
                                            <!-- <input type=hidden name='iscustomer[]' value=1> -->
                                            <div class='form-group'>
                                                <label style='display: block;' for='fname'>Adult Guest 1*</label>
                                                <input type='text' class='typeahead form-control typeahead1' required name='guest[]' value="<?= $CustomerName ?>">
                                            </div>
                                        </div>

                                        <input type="hidden" name="country_code[<?php echo $country_index ?>]"  value="">
                                        <input type="hidden" name="country_code_name[<?php echo $country_index ?>]"  value="">
                                        <?php $country_index++ ?>

                                        <div class='col-md-6'>
                                            <div class='form-group'>
                                                <label style='display: block;' for='fname'>Phone Number*</label>
                                                <input type='<?php echo $mobile_field_type; ?>' class='typeahead form-control' required name='mobile[]' on-load-code="<?= $Customer_country_code ?>" value="<?= $cusMobile ?>" placeholder='Phone Number' >
                                            </div>
                                        </div>
                                        <div class='col-md-6 <?php echo $custom_class?>'>
                                            <div class='form-group'>
                                                <label style='display: block;'>Ticket</label>

                                                <select id='selectPark1' name='ticket[]' onchange="sel_park($(this))" class='change form-control my adult_select' <?php echo $disabled ?> >
                                                    <option value=''>Please Select Ticket 1 </option>

                                                    <?php

                                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                                    $sqlGetTicket = $sql_ticket . "and type<>'child' order by expire_date asc";

                                                    //$sqlGetTicket = "SELECT * FROM ticket";
                                                    $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                                    $ticket_count = 1;
                                                    if (mysqli_num_rows($resultGetTicket) > 0) {
                                                        while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                                            include('ticket_item.php');
                                                            $ticket_count = $ticket_count + 1;
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $a = 1;
                                $c = 1;

                                while ($GetCustomer['adults'] > $c) {
                                    $a++;
                                    $c++;
                                    if ($GetCustomer['theme_park_code'] === 'SW') {
                                        $phone_number = $cusMobile;
                                    } else {
                                        $phone_number = '';
                                    }

                                    echo '<input type="hidden" name="country_code['.$country_index.']" value="">
                                                <input type="hidden" name="country_code_name['.$country_index.']" value="">';

                                    $country_index++;
                                    echo "<div class='block'>
                              <div class='row'>
                                <div class='col-md-12' id='div_" . $a . "'>
                                  <input type=hidden name='type[]' value='adult'>
                                  
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Adult Guest " . $c . "*</label>
                                    <input type='text' class='typeahead form-control typeahead" . $a . "' required name='guest[]'  placeholder='Name' >
                                  </div>
                                </div>
                                <div class='col-md-6'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Phone Number*</label>
                                    <input type='".$mobile_field_type."' class='typeahead form-control'  name='mobile[]' value='" . $phone_number . "'  placeholder='Phone Number' >
                                  </div>
                                </div>
                                <div class='col-md-6 ". $custom_class."'>
                                  <div class='form-group'>
                                    <label style='display: block;'>Ticket</label>
                                    <select id='selectPark" . $a . "' name='ticket[]' class='change form-control my adult_select'  ". $disabled ." 
                                    onchange='sel_park($(this))'><option value=''>Please Select Ticket ".$a." </option>";

                                    //$sqlGetTicket = "SELECT * FROM ticket";
                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                    $sqlGetTicket = $sql_ticket . "and type<>'child' order by expire_date asc";

                                    $resultGetTicket = mysqli_query($db, $sqlGetTicket);

                                    if (mysqli_num_rows($resultGetTicket) > 0) {
                                        $ticket_count = 1;
                                        while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {

                                            include('ticket_item.php');
                                            $ticket_count = $ticket_count + 1;
                                        }
                                    }
                                    echo "</select>
                                  </div>
                                </div>
                              </div>
                            </div>";
                                }
                            }

                            if ($DbCountKid >= $countKid && $GuestsUpdate['type'] == "kid") {

                                echo "<input type='hidden' name='country_code[".($country_index)."]' value='" . $GuestsUpdate['country_code'] . "'>";
                                echo "<input type='hidden' name='country_code_name[".($country_index)."]' value='" . $GuestsUpdate['country_code_name'] . "'>";
                                $country_index++;

                                echo "<div class='block'>
                            <div class='row'>
                              <div class='col-md-6'>
                                <input type=hidden name='type[]' value='" . $GuestsUpdate['type'] . "'>
                                <input type=hidden name='gid[]' value='" . $GuestsUpdate['id'] . "'>
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>" . ucfirst($GuestsUpdate['type']) . " Guest  " . $sCount . "*</label>
                                  <input type='text' class='typeahead form-control typeahead" . $e . "' required name='guest[]' value='" . $GuestsUpdate['guest_name'] . "'  placeholder='Name' >
                                </div>
                              </div>
                              <div class='col-md-6'>
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>Phone Number*</label>
                                  <input type='".$mobile_field_type."' class='typeahead form-control' required name='mobile[]' country-code-name='".$GuestsUpdate['country_code_name']."' country='".$GuestsUpdate['country_code']."' value='" . $GuestsUpdate['guest_mobile'] . "'  placeholder='Phone Number' >
                                </div>
                              </div>
                              <div class='col-md-6 ". $custom_class."'>
                                <div class='form-group'>
                                  <label style='display: block;'>Ticket</label>
                                  <select id='selectPark" . $e . "' name='ticket[]' class='change kid_select form-control my' ". $disabled ." onchange='sel_park($(this))'>
                                    <option value=''>Please Select Ticket 3 </option>";

                                if ($GuestsUpdate['type'] == "adult") {
                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                    $sqlTicket = $sql_ticket . "and type<>'child' and type<>'youth'";
                                } else {
                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'";
                                    $sqlTicket = $sql_ticket . "and type<>'adult' and type<>'youth'";
                                }
                                $sqlGetTicket = $sqlTicket . " order by expire_date asc";

                                $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                $ticket_count = 1;
                                if (mysqli_num_rows($resultGetTicket) > 0) {
                                    while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {

                                        include('ticket_item.php');
                                        $ticket_count = $ticket_count + 1;
                                    }
                                }

                                echo "</select>
                                </div>
                              </div>
                              <div class='col-md-6'></div>";
                                if($tpp_id==1){
                                    if ((int) $level > 1) {
                                        echo  "<div class='d-flex flex-wrap w-100 justify-content-start justify-content-md-end'>
                                                      <a href='javascript:void(0);'  onclick='show(".$GuestsUpdate['id'].")' class='btn btn-dark mr-2 mb-1' role='button' >Show Usage</a>
                                                      <!-- <label style='display: block;' for='fname'>Active/Inactive</label>-->
                                                      <a href='tel:".$GuestsUpdate['country_code'] . $GuestsUpdate['guest_mobile'] . "' class='btn btn-primary mr-2 mb-1' role='button' >Call</a>
                                                    <!--<label style='display: block;' for='fname'>Active/Inactive</label>-->
                                                    <a href='../Orders/individual_text_history.php?id=" . $GuestsUpdate['id'] . "' class='btn btn-primary mr-2 mb-1' role='button' >Text Usage</a>
                                                      <!--<label style='display: block;' for='fname'>Active/Inactive</label>-->
                                                      <a href='../Ajax/Inactive.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "' class='" . $clasname . " mr-2 mb-1' role='button'>" . $active . "</a>
                                                      <!--<label style='display: block;' for='fname'>LogIn/LogOut</label>-->
                                                      <a href='../Ajax/DisableUser.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "&mobile=" . $GuestsUpdate['guest_mobile'] . "' class='" . $Loginclasname . " mr-2 mb-1' role='button'>" . $active_log . "</a>
                                                      </div>";
                                    }

                                }
                                else{
                                    if ((int) $level > 1) {
                                        echo  "<div class='d-flex flex-wrap w-100 justify-content-start justify-content-md-end'>
                                                      <a href='javascript:void(0);'  onclick='show(".$GuestsUpdate['id'].")' class='btn btn-dark mr-2 mb-1' role='button' >Show Usage</a>
                                                      <!-- <label style='display: block;' for='fname'>Active/Inactive</label>-->
                                                      <a href='tel:".$GuestsUpdate['country_code'] . $GuestsUpdate['guest_mobile'] . "' class='btn btn-primary mr-2 mb-1' role='button' >Call</a>
                                                    <!--<label style='display: block;' for='fname'>Active/Inactive</label>-->
                                                      <!--<label style='display: block;' for='fname'>Active/Inactive</label>-->
                                                      <a href='../Ajax/Inactive.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "' class='" . $clasname . " mr-2 mb-1' role='button'>" . $active . "</a>
                                                      <!--<label style='display: block;' for='fname'>LogIn/LogOut</label>-->
                                                      <a href='../Ajax/DisableUser.php?id=" . $GuestsUpdate['id'] . "&orderId=" . $GuestsUpdate['order_id'] . "&mobile=" . $GuestsUpdate['guest_mobile'] . "' class='" . $Loginclasname . " mr-2 mb-1' role='button'>" . $active_log . "</a>
                                                      </div>";
                                    }
                                }


                                echo         "</div>
                          </div>
                          <script>$('#selectPark" . $e . "').val('" . $GuestsUpdate['ticket_id'] . "');</script>";

                                if ($DbCountKid > $TotalKidCount) {

                                    $AddNewRowKid = $DbCountKid - $TotalKidCount;

                                    if ($TotalKidCount == $countKid) {

                                        $checkAddRowKid = 0;

                                        while ($AddNewRowKid > $checkAddRowKid) {

                                            $checkAddRowKid++;
                                            $sCount++;

                                            echo "<div class='block'>
                                  <div class='row'>
                                    <div class='col-md-12'>
                                    <input type='hidden' name='country_code[$country_index]'>
                                    <input type='hidden' name='country_code_name[$country_index]'>
                                    
                                      <input type=hidden name='type[]' value='kid'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Kid Guest " . $sCount . "*</label>
                                        <input type='text' class='typeahead form-control typeahead" . $sCount . "'  required name='guest[]'  placeholder='Name' >
                                      </div>
                                    </div>
                                    <div class='col-md-6'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Phone Number*</label>
                                        <input type='".$mobile_field_type."' class='typeahead form-control' required name='mobile[]' value='" . $GuestsUpdate['guest_mobile'] . "'  placeholder='Phone Number'>
                                      </div>
                                    </div>
                                    <div class='col-md-6 ". $custom_class."'>
                                      <div class='form-group'>
                                        <label style='display: block;'>Ticket</label>
                                        <select id='selectPark" . $sCount . "'  name='ticket[]' class='change kid_select form-control my' ". $disabled ." onchange='sel_park($(this))'><option value=''>Please Select Ticket 4 </option>";
                                            $country_index++;
                                            //$sqlGetTicket = "SELECT * FROM ticket";

                                            //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'";
                                            $sqlGetTicket = $sql_ticket . "and type<>'adult' and type<>'youth' order by expire_date asc";
                                            $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                            $ticket_count = 1;
                                            if (mysqli_num_rows($resultGetTicket) > 0) {
                                                while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                                    include('ticket_item.php');
                                                    $ticket_count = $ticket_count + 1;
                                                }
                                            }

                                            echo "</select>
                                      </div>
                                    </div>
                                  </div>
                                </div>";
                                        }
                                    }
                                }
                            }
                            else{
                                if ($DbCountKid > $TotalKidCount) {

                                    $AddNewRowKid = $DbCountKid - $TotalKidCount;

                                    if ($TotalKidCount == $countKid) {

                                        $checkAddRowKid = 0;

                                        while ($AddNewRowKid > $checkAddRowKid) {

                                            $checkAddRowKid++;
                                            $sCount++;

                                            echo "<div class='block'>
                                  <div class='row'>
                                    <div class='col-md-12'>
                                    <input type='hidden' name='country_code[$country_index]'>
                                    <input type='hidden' name='country_code_name[$country_index]'>                                    
                                    
                                      <input type=hidden name='type[]' value='kid'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Kid Guest " . $sCount . "*</label>
                                        <input type='text' class='typeahead form-control typeahead" . $sCount . "'  required name='guest[]'  placeholder='Name' >
                                      </div>
                                    </div>
                                    <div class='col-md-6'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Phone Number*</label>
                                        <input type='".$mobile_field_type."' class='typeahead form-control' required name='mobile[]' value='" . $GuestsUpdate['guest_mobile'] . "'  placeholder='Phone Number'>
                                      </div>
                                    </div>
                                    <div class='col-md-6 ". $custom_class."'>
                                      <div class='form-group'>
                                        <label style='display: block;'>Ticket</label>
                                        <select id='selectPark" . $sCount . "'  name='ticket[]' class='change kid_select form-control my' ". $disabled ." onchange='sel_park($(this))'><option value=''>Please Select Ticket 4 </option>";
                                            $country_index++;
                                            //$sqlGetTicket = "SELECT * FROM ticket";

                                            //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'";
                                            $sqlGetTicket = $sql_ticket . "and type<>'adult' and type<>'youth' order by expire_date asc";
//                                            var_dump($sqlGetTicket);
                                            $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                            $ticket_count = 1;
                                            if (mysqli_num_rows($resultGetTicket) > 0) {
                                                while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                                    include('ticket_item.php');
                                                    $ticket_count = $ticket_count + 1;
                                                }
                                            }

                                            echo "</select>
                                      </div>
                                    </div>
                                  </div>
                                </div>";
                                        }
                                    }
                                }
                            }
                        }
                    }else {
                        $country_index=0;

                        if ($GetCustomer['adults'] > 0) {
                        ?>
                        <div class='block'>
                            <div class='row'>
                                <div class='col-md-12'>
                                    <input type=hidden name='type[]' value='adult'>
                                    <!-- <input type=hidden name='iscustomer[]' value=1> -->
                                    <div class='form-group'>
                                        <label style='display: block;' for='fname'>Adult Guest 1*</label>
                                        <input type='text' class='typeahead form-control typeahead1' required name='guest[]' value="<?= $CustomerName ?>">
                                    </div>
                                </div>

                                <input type="hidden" name="country_code[<?php echo $country_index ?>]"  value="">
                                <input type="hidden" name="country_code_name[<?php echo $country_index ?>]"  value="">
                                <?php $country_index++ ?>

                                <div class='col-md-6'>
                                    <div class='form-group'>
                                        <label style='display: block;' for='fname'>Phone Number*</label>
                                        <input type='<?php echo $mobile_field_type; ?>' class='typeahead form-control' required name='mobile[]' on-load-code="<?= $Customer_country_code ?>" value="<?= $cusMobile ?>" placeholder='Phone Number' >
                                    </div>
                                </div>
                                <div class='col-md-6 <?php echo $custom_class?>'>
                                    <div class='form-group'>
                                        <label style='display: block;'>Ticket</label>

                                        <select id='selectPark1' name='ticket[]' onchange="sel_park($(this))" class='change form-control my adult_select' <?php echo $disabled ?> >
                                            <option value=''>Please Select Ticket 1 </option>

                                            <?php

                                            //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                            $sqlGetTicket = $sql_ticket . "and type<>'child' order by expire_date asc";

                                            //$sqlGetTicket = "SELECT * FROM ticket";
                                            $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                            $ticket_count = 1;
                                            if (mysqli_num_rows($resultGetTicket) > 0) {
                                                while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                                    include('ticket_item.php');
                                                    $ticket_count = $ticket_count + 1;
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php

                        }
                        //$sqlGuest = "SELECT * FROM `guest` where order_id='$orderno'";
                        //$resultGuest = mysqli_query($db, $sqlGuest);

                        if ($GetCustomer['adults'] > 0) {
                            $a = 1;
                            $c = 1;

                            while ($GetCustomer['adults'] > $c) {
                                $a++;
                                $c++;
                                if ($GetCustomer['theme_park_code'] === 'SW') {
                                    $phone_number = $cusMobile;
                                } else {
                                    $phone_number = '';
                                }

                                echo '<input type="hidden" name="country_code['.$country_index.']" value="">
                <input type="hidden" name="country_code_name['.$country_index.']" value="">';

                                $country_index++;
                                echo "<div class='block'>
                              <div class='row'>
                                <div class='col-md-12' id='div_" . $a . "'>
                                  <input type=hidden name='type[]' value='adult'>
                                  
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Adult Guest " . $c . "*</label>
                                    <input type='text' class='typeahead form-control typeahead" . $a . "' required name='guest[]'  placeholder='Name' >
                                  </div>
                                </div>
                                <div class='col-md-6'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Phone Number*</label>
                                    <input type='".$mobile_field_type."' class='typeahead form-control'  name='mobile[]' value='" . $phone_number . "'  placeholder='Phone Number' >
                                  </div>
                                </div>
                                <div class='col-md-6 ". $custom_class."'>
                                  <div class='form-group'>
                                    <label style='display: block;'>Ticket</label>
                                    <select id='selectPark" . $a . "' name='ticket[]' class='change form-control my adult_select'  ". $disabled ." 
                                    onchange='sel_park($(this))'><option value=''>Please Select Ticket ".$a." </option>";

                                //$sqlGetTicket = "SELECT * FROM ticket";
                                //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                $sqlGetTicket = $sql_ticket . "and type<>'child' order by expire_date asc";

                                $resultGetTicket = mysqli_query($db, $sqlGetTicket);

                                if (mysqli_num_rows($resultGetTicket) > 0) {
                                    $ticket_count = 1;
                                    while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {

                                        include('ticket_item.php');
                                        $ticket_count = $ticket_count + 1;
                                    }
                                }
                                echo "</select>
                                  </div>
                                </div>
                              </div>
                            </div>";
                            }
                        }
                        if ($GetCustomer['kids'] > 0) {

                            //$a=1;
                            $d = 0;
                            while ($GetCustomer['kids'] > $d) {
                                $a++;
                                $d++;
                                echo '<input type="hidden" name="country_code['.$country_index.']" value="">
                <input type="hidden" name="country_code_name['.$country_index.']" value="">';
                                $country_index++;
                                echo "<div class='block'>
                              <div class='row'>
                                <div class='col-md-12'>
                                  <input type=hidden name='type[]' value='kid'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Kid Guest " . $d . "*</label>
                                    <input type='text' class='typeahead form-control typeahead" . $a . "' required name='guest[]'  placeholder='Name' >
                                  </div>
                                </div>
                                <div class='col-md-6'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Phone Number*</label>
                                    <input type='".$mobile_field_type."' class='typeahead form-control' required name='mobile[]' on-load-code='" .$Customer_country_code. "' value='" . $cusMobile . "'  placeholder='Phone Number' >
                                  </div>
                                </div>
                                <div class='col-md-6 ". $custom_class."'>
                                  <div class='form-group'>
                                    <label style='display: block;'>Ticket</label>
                                    <select id='selectPark" . $a . "' name='ticket[]' class='change form-control my kid_select' ". $disabled ." onchange='sel_park($(this))'>
                                    <option value=''>Please Select Ticket ".$d." </option>";

                                //$sqlGetTicket = "SELECT * FROM ticket";
                                //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'";
                                $sqlGetTicket = $sql_ticket . "and type<>'adult' order by expire_date asc";
                                $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                $ticket_count = 1;
                                if (mysqli_num_rows($resultGetTicket) > 0) {
                                    while ($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                        include('ticket_item.php');
                                        $ticket_count = $ticket_count + 1;
                                    }
                                }

                                echo "</select>
                                  </div>
                                </div>
                              </div>
                            </div>";
                            }
                        }
                    }
                    ?>
                    <div class="block">
                        <!-- <span class="add add-btn">Add More Guest</span> -->
                        <button type="submit" onclick="myFunction()" value="Submit" name="customerdata">Submit</button>
                    </div>
            </form>
        </div>

        <script></script>
        <?php

        // $sqlTk = "SELECT * FROM `assigntickets` where order_id='$orderno'";
        // $resultTk = mysqli_query($db, $sqlTk);

        // if (mysqli_num_rows($resultTk) > 0) {
        //   $b = 0;

        //   while ($GuestsTk = mysqli_fetch_assoc($resultTk)) {

        //     $b++;
        //     echo "<script>$('#selectPark1').val('" . $GuestsTk['ticket_id'] . "');</script>";
        //   }
        // }
        ?>
    </div>
</div>

<div class="modal"  id= "showticketusage" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ticket Usage</h5>
        <button type="button" class="close closemod" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-secondary closemod" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
<script>
    // $("#selectPark1").change(function() {
    //
    //     var ticket_name = $(".change").val();
    //     console.log("Ticket Name :::: ",ticket_name);
    //     var url = "../Ajax/GetTicketById.php?id=" + ticket_name;
    //     $.ajax({
    //         url: url,
    //         type: "GET",
    //
    //         success: function(res) {
    //             var data = JSON.parse(res);
    //             if (data['name_on_ticket'] === '') {
    //                 return;
    //             }
    //             $(".typeahead1").val(data['name_on_ticket']);
    //             //   var currentText = $('#selectPark1 option:selected').text();
    //             //   $('#selectPark1 option:selected').text(currentText+" "+ data['name_on_ticket'])
    //
    //             console.log("Ticket Name success rep ::::", res)
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             console.log(textStatus, errorThrown);
    //         }
    //     });
    // });

    function sel_park($this) {
        var ticket_name = $this.val();
        var url = "../Ajax/GetTicketById.php?id=" + ticket_name;
        // console.log("park id :::: ", id ," ticket type" , ticket_name)
        $.ajax({
            url: url,
            type: "GET",

            success: function(res) {
                var data = JSON.parse(res);
                if (data['name_on_ticket'] === '') {
                    return;
                }
                $this.closest('.row').find("input[name='guest[]']").val(data['name_on_ticket']);
                //   var currentText = $('#selectPark'+id+' option:selected').text();
                //   $('#selectPark'+id+' option:selected').text(currentText+" "+ data['name_on_ticket'])
                console.log("Select park resp :::: ", res)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
</script>

<script src="../build/js/intlTelInput.js"></script>

<script>
    var input = document.querySelectorAll('input[name="mobile[]"]');



    var flag = '';
    input.forEach((el, i) => {
        if(el.getAttribute('country-code-name')){
            flag= el.getAttribute('country-code-name');
        }
        else{

            if(el.getAttribute('on-load-code')){
                var countrycode =$('input[name="mobile[]"]').attr('on-load-code');
//   var onlycode = countrycode.split('+');
                //   var selected_country =  window.intlTelInputGlobals.getCountryData().find(function(country){
                //  return country.dialCode==test;
                // if(country.dialCode==onlycode[1]){

                // flag = country.iso2;
                // }
                flag = countrycode;
                //  })
            }
            else{
                flag ='us';
            }
        }

        console.log(flag);
        window.intlTelInput(el, {
            utilsScript: "../build/js/utils.js",
            /*separateDialCode: true,*/
            initialCountry: (flag)
        });

    });


</script>



<script>

</script>


<script>

    function myFunction(){
        $('.iti__selected-flag').each(function(i,flag){
            var a=$(flag)[0].title;
            console.log(a);
            const myArray = a.split(" ");
            var code=myArray[myArray.length - 1];
            $('input[name="country_code['+i+']"]').val(code);

        })

        $('.iti__country-list').each(function(index,element){
            var b = $(element).find('.iti__active').attr('data-country-code');
            $('input[name="country_code_name['+index+']"]').val(b);

        })
    }

</script>


<script type="text/javascript">
    function formatPhoneNumber(value) {
        if (!value) return value;
        const phoneNumber = value.replace(/[^\d]/g, '');
        const phoneNumberLength = phoneNumber.length;
        // console.log(phoneNumber.slice(6, 9));

        if (phoneNumberLength < 4) return phoneNumber;
        if (phoneNumberLength < 7) {
            return `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(3)}`;
        }
        return `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(
            3,
            6
        )}-${phoneNumber.slice(6, 20)}`;
    }

    function phoneNumberFormatter(inputField,e) {
        var evtobj = window.event ? event : e
        if (evtobj.ctrlKey){
            return;
        }
        const formattedInputValue = formatPhoneNumber(inputField.value);

        inputField.value = formattedInputValue;
    }



    $(document).ready(function() {

        function templateResult(item, container) {
            // replace the placeholder with the break-tag and put it into an jquery object
            const option = $(item.element);
            const color = option.data("color");
            return $('<span style="color: '+color+'">' + item.text.replace(/break/g, '<br/>') + '</span>');
        }

        function templateSelection(item, container) {
            // replace your placeholder with nothing, so your select shows the whole option text
            return item.text.replace('break', '');
        }

        $('.my').select2({
            templateResult: templateResult,
            templateSelection: templateSelection
        });

        // var adult_select = false;
        // $('.adult_select').change(function () {
        //     var current_select = this.value;
        //     if(!adult_select) {
        //         adult_select = true;
        //         $('.adult_select').not($(this)).each(function () {
        //             if ($('.adult_select option[value="' + current_select + '"]').next().val() != undefined) {
        //                 current_select = $('.adult_select option[value="' + current_select + '"]').next().val();
        //             } else {
        //                 current_select = $('.adult_select option:first').next().val();
        //             }
        //             $(this).val(current_select);
        //             $('.my').trigger('change.select2');
        //
        //         })
        //     }
        // })
        // var kid_select = false;
        // $('.kid_select').change(function () {
        //     var current_kid_select = this.value;
        //     if(!kid_select) {
        //         kid_select = true;
        //         $('.kid_select').not($(this)).each(function () {
        //             if ($('.kid_select option[value="' + current_kid_select + '"]').next().val() != undefined) {
        //                 current_kid_select = $('.kid_select option[value="' + current_kid_select + '"]').next().val();
        //             } else {
        //                 current_kid_select = $('.kid_select option:first').next().val();
        //             }
        //             $(this).val(current_kid_select);
        //             $('.my').trigger('change.select2');
        //
        //         })
        //     }
        // })


    });
    
    function show (id) {
      
        $.ajax({
            url:"showticketusage.php",    //the page containing php script
            type: "get",    //request type,
            dataType: 'json',
            data: {status: "success", id:id},
            success:function(result){
                $('#showticketusage .modal-body').html(result.history);
                $('#showticketusage').css('background','#000000a3');
                $('#showticketusage').show();
            }
        });
    }
    
    $( ".closemod" ).click(function() {
  $('#showticketusage').hide();
});
    $(document).ready(function() {
	
	$('.typeahead').keyup(function() 
	{
		var str = $(this).val();
       
		
		var spart = str.split(" ");
		for ( var i = 0; i < spart.length; i++ )
		{
			var j = spart[i].charAt(0).toUpperCase();
			spart[i] = j + spart[i].substr(1);
		}
      jQuery(this).val(spart.join(" "));
	
	});
});
</script>


</html>