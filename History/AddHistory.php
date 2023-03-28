<?php

include('../Config/Connection.php');


session_start();

$login_check = $_SESSION['id'];

//print_r($_POST);exit();

//Login End

//url id get

$id = $_GET['id'];



// print_r($_POST);exit;

if (isset($_POST['ticket_id'])) {

  $loopCount = count($_POST['ticket_id']);

  $loopCount2 = count($_POST['park']);

  //print_r($loopCount);

  for ($i = 0; $i < $loopCount; $i++) {



    $ticketdetails = $_POST['ticket_id'][$i];







    for ($j = 0; $j < $loopCount2; $j++) {





      $history_date = $_POST['date'];



      $park = $_POST['park'][$j];

      //print_r($park);

      $history_time = $_POST['time'][$j];
      $time_period = $_POST['time_period'][$j];

      if ($history_time != "" && $time_period != "") {
          $history_time = date("H:i", strtotime($history_time . " " . $time_period));
      }


      if (isset($_POST['transfer_method'][$j])) {

        $method_transfer = $_POST['transfer_method'][$j];
      } else {

        $method_transfer = 'no';
      }



      $history_insert = "INSERT INTO history (history_date,park,history_time,barcode,method_transfer)

      VALUES ('$history_date','$park','$history_time','$ticketdetails','$method_transfer')";

//       var_dump($history_insert);

      $result = mysqli_query($db, $history_insert);
    }

    //header( "Location: Guestdetails.php" );



  }
}





include('../includes/header.php');



?>

<?php
$theme_park_parents_query = "SELECT * FROM theme_park_parents ORDER BY code DESC";
$theme_park_parents = mysqli_query($db, $theme_park_parents_query);
?>







<head>

  <title>Bootstrap Example</title>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">



  <style>
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



    .nav-link {

      background-color: #212529;

      color: #ecb78b;



    }

    .nav-link active {

      background-color: #ffffff;

      color: black;



    }



    .nav-tabs .nav-link {

      border: 1px solid transparent;

      border-top-left-radius: .0rem !important;

      border-top-right-radius: .0rem !important;

    }

    .nav-tabs .nav-item {

      margin-left: 5px;

      margin-bottom: -1px;

    }

    .block {

      display: block;

      border: 1px solid #ccc;

      padding: 10px;

    }

    .edit-butns {

      margin-top: -10px;

      padding: 6px 15px 5px 15px;

      background-color: #212529;

      color: #ecb78b;

    }

    @media only screen and (max-width: 3500px) and (min-width:740px) {

      .custm-space {

        margin-top: 5px !important;

      }

      .new-form {

        margin-top: -30px !important;

      }

      .new-header1 {

        float: left !important;

        width: 10% !important;

        top: 10px;

      }



      .new-header {



        float: left !important;

        width: 90% !important;

      }

      hr {

        margin-top: 3rem !important;

        margin-bottom: 1rem;

        border: 0;

        border-top: 1px solid rgba(0, 0, 0, .1);

      }

      .small-view {

        margin-top: 10px !important;

      }

    }

    @media only screen and (max-width: 678px) and (min-width:0px) {

      .left-col {
        margin-top: 10px;

        float: left !important;

        width: 50% !important;

      }

      .right-col {
        margin-top: 10px;

        float: left !important;

        width: 50% !important;

      }

      .new-fonts {

        font-size: 20px !important;

      }

      .small-view {

        display: table !important;

        padding: -2px !important;

      }

      .new-header {

        margin-top: 10px !important;

        width: 77% !important;

        float: left !important;

      }

      .new-header1 {

        margin-top: 15px !important;

        width: 20% !important;

        float: left !important;

      }

      .nav-1 {

        width: 28% !important;

      }

      .nav-2 {

        width: 30% !important;

      }

      .nav-3 {

        width: 33% !important;

      }

      .nav-tabs .nav-link {

        font-size: 10px !important;

        border: 1px solid transparent;

        border-top-left-radius: .25rem;

        border-top-right-radius: .25rem;

      }

      .nav-tabs .nav-item.show .nav-link,
      .nav-tabs .nav-link.active {

        font-size: 10px !important;

        color: #495057;

        background-color: #fff;

        border-color: #dee2e6 #dee2e6 #fff;

      }

    }

    .navbar-dark .navbar-nav .nav-link {

      background-color: transparent !important;

      color: rgba(255, 255, 255, .5);

    }

    .left-col {
      margin-top: 10px;

      float: left !important;

      width: 32% !important;

    }

    .right-col {
      margin-top: 10px;

      float: left !important;

      width: 67% !important;

    }



    #wrapper #content-wrapper {

      overflow-x: hidden;

      width: 100%;

      padding-top: 0rem !important;

      padding-bottom: 80px;

    }
  </style>

</head>

<?php
$current_date = date("Y-m-d");
if (@$_GET['order_id']){
    $order_id = @$_GET['order_id'];
    $query_guest = "SELECT GROUP_CONCAT(CONCAT('''', g.ticket_id, '''' )) as guest_ticket
 from guest g where g.order_id = '$order_id'";
    $result_guest = mysqli_query($db,$query_guest);
    $row_guest = mysqli_fetch_assoc($result_guest);
    $guest_ticket = $row_guest['guest_ticket'];
    $sql = "SELECT ticket.* , theme_park_parents.* FROM ticket join theme_park_parents on ticket.theme_park_parent_id=theme_park_parents.id WHERE ticket.ticketshowid in ($guest_ticket)";
}
else{
    $sql = "SELECT ticket.* , theme_park_parents.* FROM ticket join theme_park_parents on ticket.theme_park_parent_id=theme_park_parents.id WHERE ticket.expire_date >='$current_date' AND ticket.theme_park_parent_id=1";
}
$result = mysqli_query($db, $sql);

?>

<div id="content-wrapper">



  <div class="container" style="border:1px solid #eee;">



    <div class="col-md-12 small-view">

      <div class="col-md-11 new-header">

        <h3 class="new-fonts">Ticket Details</h3>

      </div>





    </div>

    <hr>



    <body>

      <br>





      <!-- Nav tabs -->



      <ul class="nav nav-tabs new-form" role="tablist">

        <li class="nav-item nav-1">

          <a class="nav-link active" data-toggle="tab" href="#home">Add Usage</a>

        </li>

        <li class="nav-item  nav-2">

          <a class="nav-link " data-toggle="tab" href="#menu1">Assignment</a>
        </li>
        <li class="nav-item  nav-3">
          <a class="nav-link " data-toggle="tab" href="#menu2">Edit</a>
        </li>
      </ul>



      <!-- Tab panes -->

      <div class="tab-content ">

        <div id="home" class="container tab-pane active"><br>

          <form name="Addorders1" action="" method="POST">

            <div class="optionBox">

              <div class="block">

                <div class="row">

                  <div class="col-md-12">

                    <label for="fname">Barcode</label>

                    <select class="form-control" name="ticket_id[]" multiple>

                      <?php

                      while ($row = mysqli_fetch_assoc($result)) {

                        $tickeId = $row['ticketshowid'];

                        $originalDate = $row["expire_date"];

                        $newDate = date("m-d-Y", strtotime($originalDate));

                        // $expire=$row['expire_date'];

                        if ($row['type'] == "adult") {

                          $type = "AD";
                        } else if ($row['type'] == "child") {

                          $type = "CH";
                        } else if ($row['type'] == 'comp') {

                          $type = "COM";
                        } else {

                          $type = "YOU";
                        }

                        $ticketToShow = $row['name'] . ' (' . $row['set_link'] . ') ' . $type . ' ' . $tickeId . ' ' . $newDate;

                        //var_dump($ticketToShow);
                        $selected = "";
                        if (@$_GET['order_id']){
                            $selected = "selected";
                        }



                      ?>

                        <option <?= $selected; ?> value="<?= $row['ticketshowid'] ?>">

                          <?= $ticketToShow ?>

                        </option>



                      <?php

                      }



                      ?>

                    </select>

                  </div>

                  <div class="col-md-4 custm-space">

                    <div class="form-group">

                      <label style="display: block;" for="fname">Date*</label>

                      <input type="date" class="typeahead form-control" name="date" id="date" placeholder="Date">



                    </div>

                  </div>



                  <div class="col-md-4 custm-space">



                    <label style="display: block;" for="fname">Park *</label>

                    <select name="park[]" class="typeahead form-control">

                      <option value="">Please select park</option>

                      <option value="Universal Studios">Universal Studios</option>

                      <option value="Islands Of Adventure">Islands Of Adventure</option>

                      <option value="Volcano Bay Water Park">Volcano Bay</option>
                      <option value="">---------------</option>
                      <option value="SeaWorld">SeaWorld</option>
                      <option value="Busch Gardens">Busch Gardens</option>
                      <option value="Aquatica">Aquatica</option>


                    </select>

                  </div>

                  <input type="hidden" name="transfer_method[]" value="">

                  <div class="col-md-2 custm-space">

                    <div class="form-group">

                      <label style="display: block;" for="fname">Time.*</label>
                      <select name="time[]" class="typeahead form-control">
                          <option value="">Select</option>
                        <option value="8:00">8</option>
                        <option value="8:30">8:30</option>
                        <option value="9:00">9</option>
                        <option value="9:30">9:30</option>
                        <option value="10:00">10</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12 Noon</option>
                        <option value="12:30">12:30</option>
                        <option value="1:00">1</option>
                        <option value="1:30">1:30</option>
                        <option value="2:00">2</option>
                        <option value="2:30">2:30</option>
                        <option value="3:00">3</option>
                        <option value="3:30">3:30</option>
                        <option value="4:00">4</option>
                        <option value="4:30">4:30</option>
                        <option value="5:00">5</option>
                        <option value="5:30">5:30</option>
                        <option value="6:00">6</option>
                        <option value="6:30">6:30</option>
                        <option value="7:00">7</option>
                        <option value="7:30">7:30</option>
                      </select>


                      <!-- <input class="checkin"  name="time[]" width="100%" /> -->





                    </div>

                  </div>

                  <div class="col-md-2 custm-space">
                    <div class="form-group pt-sm-4" style="display: flex; align-items: center; height: 100%;">
                      <div class="mr-2">
                        <input type="radio" name="time_period[0]" id="am-0" value="AM"><label for="am-0">AM</label>
                      </div>
                      <div>
                        <input type="radio" name="time_period[0]" id="pm-0" value="PM"><label for="pm-0">PM</label>
                      </div>
                    </div>
                  </div>

                </div>

              </div>



              <div class="block">

                <label style="display: block;" for="fname">Want to add more?</label>

                <input type="radio" class="add add-btn" name="status" value="1"> Yes<br>

                <input type="radio" id="status_no" name="status" value="0"> No<br>

                <div class="form-group">

                  <button type="submit" value="submit" name="submit" id="save_button" style="display: none;">Submit</button>

                </div>





              </div>

            </div>

          </form>

          <script>
            countu = 0;

            $('.add').click(function() {

              countu++;

              $('.block:last').before(
                '<div class="block"><div class="row"><div class="col-md-6"><label style="display: block;" for="fname">Park *</label><select name="park[]" class="typeahead form-control"><option value="">Please select park</option><option value="Universal Studios">Universal Studios</option><option value="Islands Of Adventure">Islands Of Adventure</option><option value="Volcano Bay Water">Volcano Bay Water</option></select></div><div class="col-md-4"><div class="form-group"><label style="display: block;" for="fname">Time.*</label><select name="time[]" class="typeahead form-control"><option value="8:00">8</option><option value="8:30">8:30</option><option value="9:00">9</option><option value="9:30">9:30</option><option value="10:00">10</option><option value="10:30">10:30</option><option value="11:00">11</option><option value="11:30">11:30</option><option value="12:00">12 Noon</option><option value="12:30">12:30</option><option value="1:00">1</option><option value="1:30">1:30</option><option value="2:00">2</option><option value="2:30">2:30</option><option value="3:00">3</option><option value="3:30">3:30</option><option value="4:00">4</option><option value="4:30">4:30</option><option value="5:00">5</option><option value="5:30">5:30</option><option value="6:00">6</option><option value="6:30">6:30</option><option value="7:00">7</option><option value="7:30">7:30</option></select></div></div><div class="col-md-2 custm-space"><div class="form-group pt-sm-4" style="display: flex; align-items: center; height: 100%;"><div class="mr-2"><input type="radio" name="time_period[' +
                countu + ']" id="am-' + countu + '" value="AM"><label for="am-' + countu +
                '">AM</label></div><div><input type="radio" name="time_period[' + countu + ']" id="pm-' + countu +
                '" value="PM"><label for="pm-' + countu +
                '">PM</label></div></div></div><div class="col-md-12"><label for="type">Method of Transfer *</label><select class="form-control"name="transfer_method[]"><option  value="main">Main</option><option  value="train">Train</option></select></div></div><br><button type="button" class="btn btn-primary remove red"style="background-color:#212529;color:#ecb78b">Remove History</button></div>'
              );



              $('#timed' + countu).timepicker({

                uiLibrary: 'bootstrap4'

              });



            });

            $('.optionBox').on('click', '.remove', function() {

              $(this).parent().remove();

            });

            $('#status_no').click(function() {

              $('#save_button').css("display", "block");

            });

            $('.add').click(function() {

              $('#save_button').css("display", "none");

            });
          </script>



        </div>



        <div id="menu1" class="container tab-pane fade"><br>

          <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

              <thead>

                <?php

                $id = $_GET['id'];

                $sql13 = "SELECT * FROM ticket where id='$id'";

                $result13 = mysqli_query($db, $sql13);

                $user13 = mysqli_fetch_assoc($result13);

                $ticketshowid = $user13['ticketshowid'];



                $ethnicityname = "SELECT guest.`guest_name`,guest.`guest_mobile`,guest.`login_id`,guest.`ticket_id`,`order`.`option`,`order`.`assign`,`date_of_visit` FROM `order` INNER JOIN guest ON `guest`.order_id=`order`.id AND guest.`ticket_id`='$ticketshowid' AND `order`.`assign`='1'";



                $ethnicitsult = mysqli_query($db, $ethnicityname);

                ?>

                <tr>
                  <th>Order Date</th>

                  <th>Customer Name</th>

                  <!-- <th>Guest Mobile</th> -->

                  <th>Ticket Id</th>

                  <th>Login Id</th>

                  <th>Assign</th>



                  <!-- <th>Batch Number</th>

                     <th>Link</th>

                     <th>Trans No</th>-->



                </tr>

              </thead>



              <tbody>

              <tbody>

                <?php

                if (mysqli_num_rows($ethnicitsult) > 0) {

                  while ($row14 = mysqli_fetch_assoc($ethnicitsult)) {

                    /*  $originalDate=$row["expire_date"];

                       $newDate = date("m/d/Y", strtotime($originalDate));*/

                    echo "<tr>
                     <td>" . $row14["date_of_visit"] . "</td>

                      <td>" . $row14["guest_name"] . "</td>

                       <td>" . $row14["ticket_id"] . "</td>

                         <td>" . $row14["login_id"] . "</td>

                         <td>" . $row14["assign"] . "</td>



                     

                      

                 </tr>";
                  }
                }

                ?>





              </tbody>

            </table>

          </div>

        </div>





        <div id="menu2" class="container tab-pane fade"><br>



          <div class="container-fluid">

            <?php $status = $_SESSION['status']; ?>



            <div class="container-fluid" style="display:flex;justify-content:center;">

              <div class="col-md-12">

                <?php

                $id = $_GET['id'];

                $sql = "SELECT * FROM  ticket where id='$id'";

                $result = mysqli_query($db, $sql);

                $user = mysqli_fetch_assoc($result);



                ?>

                <form action="../Ticket/UpdateTicket.php?id=<?= $id ?>" autocomplete='off' method="post">

                  <div class="row small-pos" style="display:table;">

                    <!-- Barcode input field-->

                    <div class="col-md-6" style="float:left;">

                      <div class="left-col">

                        <label for="barcode">Barcode</label>

                      </div>

                      <div class="right-col">

                        <input type="text" class="form-control" required onblur='check_ticket_id_exists();' maxlength="20" onkeypress="return AllowNumbersOnly(event)" name="ticketshowid" id="ticketshowid" aria-describedby="fname" placeholder="Barcode *" value="<?= $user['ticketshowid'] ?>">

                        <span class="emailmessage"></span>



                      </div>

                    </div>


                    <!-- Parent Theme park input field -->

                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">



                        <label for="place">Parent Theme park*</label>

                      </div>

                      <div class="right-col">

                        <select class="form-control" name="theme_park_parent_id">

                          <?php

                          while ($theme_park_parent = mysqli_fetch_assoc($theme_park_parents)) {
                            $tpp_name = $theme_park_parent['name'];
                            $tpp_id = $theme_park_parent['id'];
                            $tpp_code = $theme_park_parent['code'];
                          ?>

                            <option value="<?= $tpp_id ?>" <?php if ($tpp_id == $user['theme_park_parent_id']) echo 'selected'; ?>><?= $tpp_name ?>
                            </option>

                          <?php
                          }
                          ?>



                        </select>



                      </div>



                    </div>

                    <!-- Type input Field -->

                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">



                        <label for="place">Type</label>

                      </div>

                      <div class="right-col">

                        <select class="form-control" name="type">



                          <option <?php if ($user['type'] == 'adult') {
                                    echo "selected";
                                  } ?> value="adult">Adult</option>

                          <option <?php if ($user['type'] == 'child') {
                                    echo "selected";
                                  } ?> value="child">Child</option>

                          <option <?php if ($user['type'] == 'youth') {
                                    echo "selected";
                                  } ?> value="youth">Youth</option>

                          <option <?php if ($user['type'] == 'comp') {
                                    echo "selected";
                                  } ?> value="comp">Comp</option>

                        </select>



                      </div>

                    </div>



                    <!-- Ticket Print input field -->

                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">



                        <label for="fname">Entitlement</label>

                      </div>

                      <div class="right-col">

                        <input type="text" class="form-control" name="entitlement" id="entitlement" value='<?= $user['entitlement'] ?>' aria-describedby="entitlement" placeholder="Entitlement *">
                      </div>

                    </div>


                    <!-- Set Link  input field-->

                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">



                        <label for="fname">Set Link</label>

                      </div>

                      <div class="right-col">

                        <input type="text" class="form-control" name="set" id="set" value='<?= $user['set_link'] ?>' aria-describedby="set" placeholder="Set Link *">

                      </div>



                    </div>



                    <!-- Ticket Type input field -->

                    <div class="col-md-6" style="float:left;">

                      <div class="left-col">



                        <label for="fname">Ticket Type</label>

                      </div>

                      <div class="right-col">

                        <select class="form-control" name="ticketpass">



                          <option <?php if ($user['ticket_type'] == '1') {
                                    echo "selected";
                                  } ?> value="1">Regular Ticket</option>

                          <option <?php if ($user['ticket_type'] == '2') {
                                    echo "selected";
                                  } ?> value="2">Annual pass</option>

                        </select>



                      </div>

                    </div>


                    <!-- Name On Ticket input field -->
                    <div class="col-md-6" style="float:left;">

                      <div class="left-col">

                        <label for="fname">Name On Ticket </label>

                      </div>

                      <div class="right-col">

                        <input type="text" class="form-control" name="name_on_ticket" id="name" aria-describedby="node" placeholder="Name On Ticket  *" value='<?= $user['name_on_ticket'] ?>'>

                      </div>

                    </div>


                    <!-- Gender input field -->

                    <div class="col-md-6" style="float:left;">

                      <div class="left-col">

                        <label for="gender">Gender</label>

                      </div>

                      <div class="right-col">

                        <select class="form-control" name="gender">

                          <option <?php if ($user['gender'] == 'Male') {
                                    echo "selected";
                                  } ?> value="Male">Male</option>

                          <option <?php if ($user['gender'] == 'Female') {
                                    echo "selected";
                                  } ?> value="Female">Female</option>

                        </select>

                      </div>

                    </div>


                    <!-- Broker input field -->

                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">



                        <label for="place">Broker</label>

                      </div>

                      <div class="right-col">

                        <input type="text" class="form-control" name="broker" value='<?= $user['broker'] ?>' id="ticketshow" aria-describedby="broker" placeholder="Broker*">



                      </div>

                    </div>


                    <!-- Purchase Price -->

                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">



                        <label for="fname">Purchase Price</label>

                      </div>

                      <div class="right-col">

                        <input type="text" class="form-control" placeholder='Price *' value='<?= $user['cost'] ?>' name="price" id="cost" aria-describedby="price">



                      </div>

                    </div>

                    <!-- Expiration Date input field -->

                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">

                        <label for="fname">Expiration Date</label>

                      </div>

                      <div class="right-col">

                        <input type="date" required class="form-control" name="date" id="date" value='<?= $user['expire_date'] ?>' aria-describedby="date" placeholder="Expire Date *">
                        <!-- theme park -->
                      </div>

                    </div>


                    <!-- Active input field -->
                    <div class="col-md-6 " style="float:left;">

                      <div class="left-col">



                        <label for="place">Active</label>

                      </div>

                      <div class="right-col">

                        <select class="form-control" name="active">

                          <option <?php if ($user['active'] == 'True') {
                                    echo "selected";
                                  } ?> value="True">true</option>

                          <option <?php if ($user['active'] == 'False') {
                                    echo "selected";
                                  } ?> value="False">false</option>



                        </select>



                      </div>



                    </div>

                  </div>

                  <br>



                  <div class="text-center" style="margin-bottom: 10px;">

                    <button type="submit" name="updateticket" class="btn btn-primary ">Submit</button>

                    <a onClick="return confirm('Are you sure you want to delete?')" href="../Ticket/DeleteTicke.php?id=<?= $id ?>" name="updateticket" class="btn btn-danger" style="margin-left: 5px;">Delete</a>



                  </div>





                </form>



              </div>







            </div>

            <!-- /.content-wrapper -->



          </div>



        </div>



      </div>





    </body>











    <!--------------- tabs start--------------------->



    <!-------------------tabs content end-------------------->

    <!---div class="container" style="display:flex;justify-content:center;margin-top:4%; ">

     <div class="col-md-8">

   <form name="Addorders1" action="" method="POST">

     <div class="optionBox">

    <div class="block">

  <div class="row">

   <div class="col-md-12">

     <label for="fname">Barcode</label> 

   <select class="form-control" name="ticket_id[]"  multiple >

  <?php

  while ($row = mysqli_fetch_assoc($result)) {

    $tickeId = $row['ticketshowid'];

    $originalDate = $row["expire_date"];

    $newDate = date("m-d-Y", strtotime($originalDate));

    // $expire=$row['expire_date'];

    if ($row['type'] == "adult") {

      $type = "AD";
    } else if ($row['type'] == "child") {

      $type = "CH";
    } else if ($row['type'] == 'comp') {

      $type = "COM";
    } else {

      $type = "YOU";
    }

    $ticketToShow = $type . ' ' . $tickeId . ' ' . $newDate;

    //var_dump($ticketToShow);





  ?>

    <option value="<?= $row['ticketshowid'] ?>"><?= $ticketToShow ?></option>

      

     <?php

    }



      ?>   

  </select>  

  <div class="form-group">

    <label style="display: block;" for="fname">Date*</label>

      <input type="date" class="typeahead form-control" required name="date" id="date"  placeholder="Date" >

        

  </div>

 </div>

 

  <div class="col-md-6">

  

   <label style="display: block;" for="fname">Park *</label>

    <select name="park[]" class="typeahead form-control">

      <option value="">Please select park</option>

      <option value="Universal Studios">Universal Studios</option>

      <option value="Islands Of Adventure">Islands Of Adventure</option>

      <option value="Volcano Bay Water">Volcano Bay Water</option>

     

    </select>

 </div>

   

   <div class="col-md-6">

  <div class="form-group">

    <label style="display: block;" for="fname">Time.*</label>

  

<input class="checkin"  name="time[]" width="100%" />



        

  </div>

 </div>

 </div>

    </div>

  

    <div class="block">

        <label style="display: block;" for="fname">Want to add more?</label>

       <input type="radio" class="add add-btn" name="status" value="1" >Yes<br>

  <input type="radio" id="status_no" name="status" value="0"> No<br>

   <div class="form-group">

     <button type="submit" value="submit"  name="submit" id="save_button" style="display: none;">Submit</button>

  </div>





    </div>

     </form>

</div>

<script>

countu=0;

$('.add').click(function() {

    countu++;

    $('.block:last').before('<div class="block"><div class="row"><div class="col-md-6"><label style="display: block;" for="fname">Park *</label><select name="park[]" class="typeahead form-control"><option value="">Please select park</option><option value="Universal Studios">Universal Studios</option><option value="Islands Of Adventure">Islands Of Adventure</option><option value="Volcano Bay Water">Volcano Bay Water</option></select></div><div class="col-md-6"><div class="form-group"><label style="display: block;" for="fname">Time.*</label><input class="checkin"  name="time[]" id=timed'+countu+' width="100%" /></div></div><div class="col-md-12"><label for="type">Method of Transfer *</label><select class="form-control"name="transfer_method[]"><option  value="main">Main</option><option  value="train">Train</option></select></div></div><br><span class="remove red">Remove History</span></div>');



    $('#timed'+countu).timepicker({

            uiLibrary: 'bootstrap4'

        });

 

});

$('.optionBox').on('click','.remove',function() {

  $(this).parent().remove();

});

$('#status_no').click(function() {

            $('#save_button').css("display", "block");

      });

$('.add').click(function() {

            $('#save_button').css("display", "none");

      });

</script>

 

</div>

   </div--->





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



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>

<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />



<script>
  $('.checkin').timepicker({

    uiLibrary: 'bootstrap4'

  });

</script>





</body>



</html>