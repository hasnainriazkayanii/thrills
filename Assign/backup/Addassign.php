<?php

include('../Config/Connection.php');

$orderno=$_GET['id'];
$sqlGuest = "SELECT * FROM `order` where id='$orderno'";
$resultGuest = mysqli_query($db, $sqlGuest);
$GetOrderId=mysqli_fetch_assoc($resultGuest);
$loginId=$GetOrderId['order_id'];

if(isset($_POST['customerdata'])) {
  //print_r($_POST);exit();

  $sqlGuest = "SELECT * FROM `order` where id='$orderno'";
  $resultGuest = mysqli_query($db, $sqlGuest);

  $GetOrderId=mysqli_fetch_assoc($resultGuest);

  $loginId=$GetOrderId['order_id'];

  $sqlGuest = "SELECT * FROM `guest` where order_id='$orderno'";

  $resultGuest = mysqli_query($db, $sqlGuest);



  if (mysqli_num_rows($resultGuest) > 0) {

    $loopCount=count($_POST['guest']);

    for ($i = 0; $i < $loopCount; $i++) {

      $orderId=$_POST['ordernumber'];
      $Name=$_POST['guest'][$i];
      $ticketId=$_POST['ticket'][$i];
      $mobile=$_POST['mobile'][$i];
      $type=$_POST['type'][$i];
      $gid=$_POST['gid'][$i];

      $sql44="SELECT * FROM `ticket` where ticketshowid ='$ticketId'";
      $result44=mysqli_query($db,$sql44);

      $user44=mysqli_fetch_assoc($result44);

      // print_r($user44['entitlement']);

      $entitlement=$user44['entitlement'];

      //print_r($gid);exit();
      
      if($gid) {

        $guest_update = "update guest set order_id='$orderId',guest_name='$Name',ticket_id='$ticketId',login_id='$loginId',guest_mobile='$mobile',type='$type',entitlement='$entitlement' where id=$gid";

        $Guestresult = mysqli_query($db,$guest_insert);
      } else {

        $guest_insert = "INSERT INTO guest (order_id,guest_name,ticket_id,login_id,guest_mobile,type,entitlement)
        VALUES ('$orderId','$Name','$ticketId','$loginId','$mobile','$type','$entitlement')";

        $Guestresult = mysqli_query($db,$guest_insert);
      }

      $resultDeleteGuest = mysqli_query($db, $guest_update);
      header( "Location: ../Orders/Orderdetails.php" );

    }

  } else {

    $loopCount=count($_POST['guest']);

    for ($i = 0; $i < $loopCount; $i++) {
      
      $orderId=$_POST['ordernumber'];
      $Name=$_POST['guest'][$i];
      $ticketId=$_POST['ticket'][$i];
      $mobile=$_POST['mobile'][$i];
      $type=$_POST['type'][$i];

      $sql44="SELECT * FROM `ticket` where ticketshowid ='$ticketId'";

      $result44=mysqli_query($db,$sql44);

      $user44=mysqli_fetch_assoc($result44);

      // print_r($user44['entitlement']);

      $entitlement=$user44['entitlement'];

      $guest_insert = "INSERT INTO guest (order_id,guest_name,ticket_id,login_id,guest_mobile,type,entitlement)

      VALUES ('$orderId','$Name','$ticketId','$loginId','$mobile','$type','$entitlement')";

      $Guestresult = mysqli_query($db,$guest_insert);

       $sql44="SELECT * FROM `ticket` where ticketshowid ='$ticketId'";

      $result44=mysqli_query($db,$sql44);

      $user44=mysqli_fetch_assoc($result44);

      $order_update66 = "UPDATE `order` SET assign=1 WHERE id='$orderno'";

      $data22=mysqli_query($db,$order_update66);
    } 

    header( "Location: ../Orders/Orderdetails.php" );
  }

}
include('../includes/header.php');
?>



<style>

  

  .my-form{width: 100%;

    height: 38px;

    border-radius: 5px;

    border: 1px solid #33333338;}



   .tt-query, / UPDATE: newer versions use tt-input instead of tt-query /

  .tt-hint {

  width: 100%;

  height: 30px;

  padding: 8px 12px;

  font-size: 24px;

  line-height: 30px;

  border: 2px solid #ccc;

  border-radius: 8px;

  outline: none;

  }



  .tt-query { / UPDATE: newer versions use tt-input instead of tt-query /

  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);

  }



  .tt-hint {

  color: #999;

  }



  .tt-menu { / UPDATE: newer versions use tt-menu instead of tt-dropdown-menu /

  width: 100%;

  margin-top: 12px;

  padding: 8px 0;

  background-color: #fff;

  border: 1px solid #ccc;

  border: 1px solid rgba(0, 0, 0, 0.2);

  border-radius: 8px;

  box-shadow: 0 5px 10px rgba(0,0,0,.2);

  }



  .tt-suggestion {

  padding: 3px 20px;

  font-size: 18px;

  line-height: 24px;

  }



  .tt-suggestion.tt-is-under-cursor { / UPDATE: newer versions use .tt-suggestion.tt-cursor /

  color: #fff;

  background-color: #0097cf;



  }



  .tt-suggestion p {

  margin: 0;

  }

</style>

<style>

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

  .add-btn{    background-color: #212529;

      padding: 5px 17px;

      color: #fff;

      text-decoration: none;}

  .red{    background-color: red;

      padding: 5px 10px;

      color: #fff;

      margin-bottom: 10px;

      text-decoration: none;}

</style>
  <div id="content-wrapper">
    <div class="container-fluid">

      <div class="col-md-12">

        <h3>Assign Tickets</h3>
        <hr>
      </div> 

      <div class="row">
        <!--<div class="col-md-6">
        <h4>Order Id</h4>
        </div>  -->
        <div class="col-md-12">
          <h4 style=" text-align: center"><?=$loginId?></h4>
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
                <input type="hidden" class="typeahead form-control" required name="ordernumber" id="order" value="<?=$orderno?>"  placeholder="Order No." >
              </div>
            </div>     
            <?php
              $sqlCustomer = "SELECT *, theme_parks.name as theme_park_name, theme_parks.is_universal as theme_park_universal, theme_parks.code as theme_park_code FROM `order` 
                                join customer on `order`.customer_id=`customer`.id 
                                LEFT JOIN theme_parks on `order`.theme_park_id=`theme_parks`.id where `order`.id='$orderno'";
              $resultCustomer = mysqli_query($db, $sqlCustomer);

              $GetCustomer=mysqli_fetch_assoc($resultCustomer);
              //print_r("$GetCustomer");exit;
              if(!$GetCustomer)
              {
                echo"Customer with this id not find in our database";exit;
              }

              // theme park data for certain order
              $tp_id = $GetCustomer['theme_park_id'];
              $tp_universal = $GetCustomer['theme_park_univeral'];
              $tp_code = $GetCustomer['theme_park_code'];
              $sql_ticket = "SELECT ticket.*,  theme_parks.is_universal as tp_universal, theme_parks.code as tp_code
                                FROM ticket LEFT JOIN theme_parks ON ticket.theme_park_id=theme_parks.id
                                where theme_park_id='$tp_id' and ticket.active='true' ";

              $CustomerName=$GetCustomer['customer'];
              $cusMobile=$GetCustomer['Phone_number'];

              $sqlUpdateCheck = "SELECT * FROM `guest` where order_id='$orderno' ORDER BY `guest`.`type` ASC";

              $resultGuestUpdate = mysqli_query($db, $sqlUpdateCheck);

              if (mysqli_num_rows($resultGuestUpdate) > 0) {

                $sqlKidCount="SELECT * FROM guest where order_id='$orderno' and type='kid'";
                $resultKidCount=mysqli_query($db,$sqlKidCount);
                $TotalKidCount=mysqli_num_rows($resultKidCount);
                $sqlAdultCount="SELECT * FROM guest where order_id='$orderno' and type='adult'";
                $resultAdultCount=mysqli_query($db,$sqlAdultCount);
                $TotalAdultCount=mysqli_num_rows($resultAdultCount);

                $countAdult=0;
                $countKid=0;
                $e=0;
                $DbCountAdult=$GetCustomer['adults'];
                $DbCountKid=$GetCustomer['kids'];
                //print_r($DbCountKid);exit;

                while($GuestsUpdate = mysqli_fetch_assoc($resultGuestUpdate)) {

                  if($GuestsUpdate['inactive']==0) {
                    $active="Active";
                    $clasname="btn btn-success";
                  } else {
                    $active="Inactive";
                    $clasname="btn btn-danger";
                  }
                  if($GuestsUpdate['isdisabled']==0) {
                    $active_log="LogIn";
                    $Loginclasname="btn btn-success";
                  } else {
                    $active_log="LogOut";
                    $Loginclasname="btn btn-danger";
                  }
                  if($GuestsUpdate['type']=="adult") {
                    $countAdult++;
                    $sCount=$countAdult;
                  } else {
                    $countKid++;
                    $sCount=$countKid;
                  }

                  $e++;

                  if($DbCountAdult>=$countAdult && $GuestsUpdate['type']=="adult") {

                    echo "<div class='block'>
                            <div class='row'>
                              <div class='col-md-6'>
                                <input type=hidden name='type[]' value='".$GuestsUpdate['type']."'>
                                <input type=hidden name='gid[]' value='".$GuestsUpdate['id']."'>
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>".ucfirst($GuestsUpdate['type'])." Guest  ".$sCount."*</label>
                                  <input type='text' class='typeahead form-control' required name='guest[]' value='".$GuestsUpdate['guest_name']."'  placeholder='Name' >
                                </div>
                              </div>
                              <div class='col-md-6'>
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>Phone Number*</label>
                                  <input type='text' class='typeahead form-control' required name='mobile[]' value='".$GuestsUpdate['guest_mobile']."'  placeholder='Phone Number' >
                                </div>
                              </div>
                              <div class='col-md-6'>
                                <div class='form-group'>
                                  <label style='display: block;'>Ticket *</label>
                                  <select id='selectPark".$e."' name='ticket[]' class='change form-control' required  >
                                    <option value=''>Please select.... </option>"; 

                                    if($GuestsUpdate['type']=="adult") {
                                      //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'"; 
                                      $sqlGetTicket = $sql_ticket . "and type<>'child'";
                                    } else {
                                      //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'"; 
                                      $sqlGetTicket = $sql_ticket . "and type<>'adult' and type<>'youth'";
                                    }

                                    $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                    if (mysqli_num_rows($resultGetTicket) > 0) {
                                      while($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                        include('ticket_item.php');
                                      }
                                    }
                            echo "</select>
                                </div>
                              </div>
                              <div class='col-md-3'>
                                <div class='form-group'>
                                  <!-- <label style='display: block;' for='fname'>Active/Inactive</label>-->
                                  <a href='../Ajax/Inactive.php?id=".$GuestsUpdate['id']."&orderId=".$GuestsUpdate['order_id']."' class='".$clasname."' role='button'>".$active."</a>
                                </div>
                              </div>
                              <div class='col-md-3'>
                                <div class='form-group'>
                                  <!-- <label style='display: block;' for='fname'>LogIn/LogOut</label>-->
                                  <a href='../Ajax/DisableUser.php?id=".$GuestsUpdate['id']."&orderId=".$GuestsUpdate['order_id']."&mobile=".$GuestsUpdate['guest_mobile']."' class='".$Loginclasname."' role='button'>".$active_log."</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <script>$('#selectPark".$e."').val('".$GuestsUpdate['ticket_id']."');</script>";

                    if($DbCountAdult > $TotalAdultCount) {

                      $AddNewRow=$DbCountAdult-$TotalAdultCount;
                      //print_r($AddNewRow);exit;
                      if($TotalAdultCount==$countAdult) {

                        $checkAddRow=0;

                        while($AddNewRow>$checkAddRow) {

                          $checkAddRow++;
                          $sCount++;

                          echo "<div class='block'>
                                  <div class='row'>
                                    <div class='col-md-12'>
                                      <input type=hidden name='type[]' value='adult'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Adult Guest ".$sCount."*</label>
                                        <input type='text' class='typeahead form-control' required name='guest[]'  placeholder='Name' >
                                      </div>
                                    </div>

                                    <div class='col-md-6'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Phone Number*</label>
                                        <input type='text' class='typeahead form-control' required name='mobile[]'  placeholder='Phone Number' >
                                      </div>
                                    </div>
                                    <div class='col-md-6'>
                                      <div class='form-group'>
                                        <label style='display: block;'>Ticket *</label>
                                        <select required name='ticket[]' class='change form-control' required><option value=''>Please select.... </option>";

                                      //$sqlGetTicket = "SELECT * FROM ticket";
                                      //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                      $sqlGetTicket = $sql_ticket . "and type<>'child'";
                                      $resultGetTicket = mysqli_query($db, $sqlGetTicket);

                                      if (mysqli_num_rows($resultGetTicket) > 0) {

                                        while($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                          include('ticket_item.php');
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
                  elseif($DbCountKid>=$countKid && $GuestsUpdate['type']=="kid") {

                    echo "<div class='block'>
                            <div class='row'>
                              <div class='col-md-6'>
                                <input type=hidden name='type[]' value='".$GuestsUpdate['type']."'>
                                <input type=hidden name='gid[]' value='".$GuestsUpdate['id']."'>
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>".ucfirst($GuestsUpdate['type'])." Guest  ".$sCount."*</label>
                                  <input type='text' class='typeahead form-control' required name='guest[]' value='".$GuestsUpdate['guest_name']."'  placeholder='Name' >
                                </div>
                              </div>
                              <div class='col-md-6'>
                                <div class='form-group'>
                                  <label style='display: block;' for='fname'>Phone Number*</label>
                                  <input type='text' class='typeahead form-control' required name='mobile[]' value='".$GuestsUpdate['guest_mobile']."'  placeholder='Phone Number' >
                                </div>
                              </div>
                              <div class='col-md-6'>
                                <div class='form-group'>
                                  <label style='display: block;'>Ticket *</label>
                                  <select id='selectPark".$e."' name='ticket[]' class='change form-control' required>
                                    <option value=''>Please select.... </option>";

                                  if($GuestsUpdate['type']=="adult")
                                  {
                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'"; 
                                    $sqlGetTicket = $sql_ticket . "and type<>'child'";
                                  }
                                  else
                                  {
                                    //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'"; 
                                    $sqlGetTicket = $sql_ticket . "and type<>'adult' and type<>'youth'";
                                  }
                                  $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                                  if (mysqli_num_rows($resultGetTicket) > 0) {
                                    while($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                      include('ticket_item.php');
                                    }
                                  }

                            echo "</select>
                                </div>
                              </div>
                              <div class='col-md-3'>
                                <div class='form-group'>
                                  <!--<label style='display: block;' for='fname'>Active/Inactive</label>-->
                                  <a href='../Ajax/Inactive.php?id=".$GuestsUpdate['id']."&orderId=".$GuestsUpdate['order_id']."' class='".$clasname."' role='button'>".$active."</a>
                                </div>
                              </div>
                              <div class='col-md-3'>
                                <div class='form-group'>
                                  <!--<label style='display: block;' for='fname'>LogIn/LogOut</label>-->
                                  <a href='../Ajax/DisableUser.php?id=".$GuestsUpdate['id']."&orderId=".$GuestsUpdate['order_id']."&mobile=".$GuestsUpdate['guest_mobile']."' class='".$Loginclasname."' role='button'>".$active_log."</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <script>$('#selectPark".$e."').val('".$GuestsUpdate['ticket_id']."');</script>";
                    
                    if($DbCountKid > $TotalKidCount) {
                      $AddNewRowKid=$DbCountKid-$TotalKidCount;
                      //print_r($AddNewRow);exit;
      
                      if($TotalKidCount==$countKid) {

                        $checkAddRowKid=0;

                        while($AddNewRowKid>$checkAddRowKid) {

                          $checkAddRowKid++;
                          $sCount++;

                          echo "<div class='block'>
                                  <div class='row'>
                                    <div class='col-md-12'>
                                      <input type=hidden name='type[]' value='kid'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Kid Guest ".$sCount."*</label>
                                        <input type='text' class='typeahead form-control' required name='guest[]'  placeholder='Name' >
                                      </div>
                                    </div>
                                    <div class='col-md-6'>
                                      <div class='form-group'>
                                        <label style='display: block;' for='fname'>Phone Number*</label>
                                        <input type='text' class='typeahead form-control' required name='mobile[]' value='".$GuestsUpdate['guest_mobile']."'  placeholder='Phone Number' >
                                      </div>
                                    </div>
                                    <div class='col-md-6'>
                                      <div class='form-group'>
                                        <label style='display: block;'>Ticket *</label>
                                        <select  name='ticket[]' class='change form-control' required><option value=''>Please select.... </option>";
                                      
                                        //$sqlGetTicket = "SELECT * FROM ticket";

                                      //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'";
                                      $sqlGetTicket = $sql_ticket . "and type<>'adult' and type<>'youth'";
                                      $resultGetTicket = mysqli_query($db, $sqlGetTicket);

                                      if (mysqli_num_rows($resultGetTicket) > 0) {
                                        while($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                          include('ticket_item.php');
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
              }
              else {
            ?>
                <div class='block'>
                  <div class='row'>
                    <div class='col-md-12'>
                      <input type=hidden name='type[]' value='adult'>
                      <!-- <input type=hidden name='iscustomer[]' value=1> -->
                      <div class='form-group'>
                        <label style='display: block;' for='fname'>Adult Guest 1*</label>
                        <input type='text' class='typeahead form-control' required name='guest[]' value="<?=$CustomerName?>" >
                      </div>
                    </div>
                    <div class='col-md-6'>
                      <div class='form-group'>
                        <label style='display: block;' for='fname'>Phone Number*</label>
                        <input type='text' class='typeahead form-control' required name='mobile[]' value="<?=$cusMobile?>"  placeholder='Phone Number' >
                      </div>
                    </div>
                    <div class='col-md-6'>
                      <div class='form-group'>
                        <label style='display: block;'>Ticket *</label>
                        <select id='selectPark1' name='ticket[]' class='change form-control' required>
                          <option value=''>Please select.... </option>
                          
                          <?php

                          //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                          $sqlGetTicket = $sql_ticket . "and type<>'child'";
                          //$sqlGetTicket = "SELECT * FROM ticket";
                          $resultGetTicket = mysqli_query($db, $sqlGetTicket);
                          if (mysqli_num_rows($resultGetTicket) > 0) {
                            while($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                              include('ticket_item.php');
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <?php
                  //$sqlGuest = "SELECT * FROM `guest` where order_id='$orderno'";
                  //$resultGuest = mysqli_query($db, $sqlGuest);

                  if ($GetCustomer['adults']>0) {
                    $a=1;
                    $c=1;

                    while($GetCustomer['adults'] > $c) {
                      $a++;
                      $c++;

                      echo "<div class='block'>
                              <div class='row'>
                                <div class='col-md-12'>
                                  <input type=hidden name='type[]' value='adult'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Adult Guest ".$c."*</label>
                                    <input type='text' class='typeahead form-control' required name='guest[]'  placeholder='Name' >
                                  </div>
                                </div>
                                <div class='col-md-6'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Phone Number*</label>
                                    <input type='text' class='typeahead form-control' required name='mobile[]'  placeholder='Phone Number' >
                                  </div>
                                </div>
                                <div class='col-md-6'>
                                  <div class='form-group'>
                                    <label style='display: block;'>Ticket *</label>
                                    <select id='selectPark".$a."' name='ticket[]' class='change form-control' required><option value=''>Please select.... </option>";
                                  
                                    //$sqlGetTicket = "SELECT * FROM ticket";
                                  //$sqlGetTicket = "SELECT * FROM ticket where type<>'child'";
                                  $sqlGetTicket = $sql_ticket . "and type<>'child'";
                                  $resultGetTicket = mysqli_query($db, $sqlGetTicket);
        
                                  if (mysqli_num_rows($resultGetTicket) > 0) {
                                    while($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {

                                      include('ticket_item.php');
                                    }
                                  }
                              echo "</select>
                                  </div>
                                </div>
                              </div>
                            </div>";
                    }

                  }

                  if ($GetCustomer['kids']>0) {

                    //$a=1;
                    $d=0;
                    while($GetCustomer['kids'] > $d) {
                      $a++;
                      $d++;
                      echo "<div class='block'>
                              <div class='row'>
                                <div class='col-md-12'>
                                  <input type=hidden name='type[]' value='kid'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Kid Guest ".$d."*</label>
                                    <input type='text' class='typeahead form-control' required name='guest[]'  placeholder='Name' >
                                  </div>
                                </div>
                                <div class='col-md-6'>
                                  <div class='form-group'>
                                    <label style='display: block;' for='fname'>Phone Number*</label>
                                    <input type='text' class='typeahead form-control' required name='mobile[]' value='".$cusMobile."'  placeholder='Phone Number' >
                                  </div>
                                </div>
                                <div class='col-md-6'>
                                  <div class='form-group'>
                                    <label style='display: block;'>Ticket *</label>
                                    <select id='selectPark".$a."' name='ticket[]' class='change form-control' required><option value=''>Please select.... </option>";

                                  //$sqlGetTicket = "SELECT * FROM ticket";
                                  //$sqlGetTicket = "SELECT * FROM ticket where type<>'adult' and type<>'youth'";
                                  $sqlGetTicket = $sql_ticket . "and type<>'adult' and type<>'youth'";
                                  $resultGetTicket = mysqli_query($db, $sqlGetTicket);

                                  if (mysqli_num_rows($resultGetTicket) > 0) {
                                    while($rowTicket = mysqli_fetch_assoc($resultGetTicket)) {
                                      include('ticket_item.php');
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
              <input type="submit" value="Submit" name="customerdata">
            </div>
        </form>
      </div>

      <script></script>
      <?php

        $sqlTk = "SELECT * FROM `assigntickets` where order_id='$orderno'";
        $resultTk = mysqli_query($db, $sqlTk);

        if (mysqli_num_rows($resultTk) > 0) {   
          $b=0;

          while($GuestsTk = mysqli_fetch_assoc($resultTk)) {

            $b++;
            echo"<script>$('#selectPark1').val('".$GuestsTk['ticket_id']."');</script>";
          }
        }
      ?>
    </div>
  </div>
</body>
</html>

