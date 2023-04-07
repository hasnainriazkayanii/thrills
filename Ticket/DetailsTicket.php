<?php

include('../Config/Connection.php');

session_start();

$login_check = $_SESSION['id'];

//var_dump($login_check);


include('../includes/header.php');

if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}

$current_date = date("Y-m-d");


$targetpage = "https://dbaseconnect.com/cheapthrills/Ticket/DetailsTicket.php";

//include('../includes/pagination.php');






?>

<head>

  <style>
    #th-ticket .th-16 {

      width: 12% !important;

    }



    @media only screen and (max-width: 678px) and (min-width:0px) {

      .new-fonts {

        font-size: 15px !important;

      }



      .new-header {

        margin-top: 10px !important;

        width: 50% !important;

        float: left !important;

      }

      .new-header1 {

        width: 50% !important;

        float: left !important;
      }
    }
  </style>

</head>

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
    <div class="row">

      <div class="col-md-12">

        <div class="col-md-8 new-header" style="float:left;">
          <h3 class="new-fonts">Ticket Details</h3>
        </div>

        <div class="col-md-4 text-right new-header" style="float:left;">
          <a href="../History/History.php" class="btn btn-warning mx-1">Usage<a>
              <a href="../Ticket/AddTicket.php" class="btn btn-primary">Add Ticket<a>
        </div>

      </div>

    </div>

    <hr>

    <br>

    <!-- DataTables Example -->

    <div class="card mb-3">

      <div class="card-header">

        <i class="fas fa-table"></i>

        Ticket Details
      </div>



      <form action="DetailsTicket.php?active=<?= $_GET['active'] ?>" method="post">

        <div class="input-group mar-10">



          <input type="text" class="form-control" name='search_name' placeholder="Search for" aria-label="Search" required>

          <div class="input-group-append ">

            <input style="padding: 8px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" type="submit" class="btn btn-primary" name="submit" value="Search">

            <a style="padding: 7px 6px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" href="DetailsTicket.php?active=3" class="btn btn-primary">Show All</a>



          </div>

        </div>

      </form>



      <form class="mar-10" id="ssform" name="orderdata" method="post" action="">

        <div class="input-group">

          <div class="input-group-append">

            <select class="form-control" id="orderid" name="activedata">

              <!-- <option value="">Please select</option>-->

              <option id="active" value="0">Active</option>

              <option id="Past" value="1">Inactive</option>



            </select>

          </div>

        </div>

      </form>



      <?php



      if (isset($_POST['submit']) == 'true') {

        $name = $_POST['search_name'];

        /*if (isset($_GET['active'])) {

          if ($_GET['active'] == 1) {

            $current_date = date("Y-m-d");

            //$sql12 ="SELECT * FROM `ticket` WHERE expire_date <= '$current_date' AND ticketshowid like '%".$name."%' OR  print_date like '%".$name."%'";
            $sql12 = "SELECT * FROM `ticket` WHERE ticketshowid like '%" . $name . "%' OR  print_date like '%" . $name . "%' OR type like '%" . $name . "%' OR set_link like '%" . $name . "%' AND expire_date <= '$current_date'";

            // var_dump($sql12); die;
            
          } else {

            $current_date = date("Y-m-d");

            //$sql12 ="SELECT * FROM `ticket` WHERE expire_date >= '$current_date' AND ticketshowid like '%".$name."%'";
            $sql12 = "SELECT * FROM `ticket` WHERE ticketshowid like '%" . $name . "%' OR type like '%" . $name . "%' OR set_link like '%" . $name . "%' AND expire_date >= '$current_date'";


          }
        }*/

            $current_date = date("Y-m-d");

            //$sql12 ="SELECT * FROM `ticket` WHERE expire_date <= '$current_date' AND ticketshowid like '%".$name."%' OR  print_date like '%".$name."%'";
            $sql12 = "SELECT * FROM `ticket` WHERE ticketshowid like '%" . $name . "%' OR  print_date like '%" . $name . "%' OR type like '%" . $name . "%' OR set_link like '%" . $name . "%'";

        /* $sql12 =" SELECT * FROM ticket WHERE ticketshowid like '%".$name."%' OR  print_date like '%".$name."%'";*/

        $q12 = mysqli_query($db, $sql12);

      ?>



        <div class="card-body">

          <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

              <thead>

                <?php $status = $_SESSION['status']; ?>

                <tr id="th-ticket">

                  <th class="th-11">Link</th>

                  <th class="th-12">Ticket Number</th>

                  <th class="th-13">Type</th>

                  <th class="th-14">Entitlement</th>

                  <th class="th-15">Expiration Date </th>

                  <th class="th-15">Status</th>

                  <!--  <th>Expire Date</th>-->

                  <!--<th>Broker</th>

                     <th>Batch Number</th>

                  

                     <th>Trans No</th>-->

                  <th class="th-16">Ticket Details</th>



                </tr>

              </thead>



              <tbody>

                <?php

                if (mysqli_num_rows($q12) > 0) {

                  while ($row12 = mysqli_fetch_assoc($q12)) {

                    $originalDate = $row12["expire_date"];

                    $newDate = date("m/d/Y", strtotime($originalDate));

                    $current_date = date("Y-m-d");

                    if (strtotime($newDate) < strtotime($current_date))
                      $status = "inactive";
                    else
                      $status = "active";

                    echo "<tr>

                      <td>" . $row12["set_link"] . "</td>

                      <td>" . $row12["ticketshowid"] . "</td>

                       <td>" . ucwords($row12["type"]) . "</td>

                         <td>" . $row12["entitlement"] . "</td>

                      <td>" . $newDate . "</td>

                      <td>" . $status . "</td>

                     

                      <td style='vertical-align: middle;'><a href='../History/AddHistory.php?id=" . $row12['id'] . "&tab=edit' class='btn btn-info' role='button'> Ticket Details</a></td>

                    

                     

                 </tr>";
                  }
                }
              } else {

                ?>



              </tbody>

            </table>



            <div class="card-body">

              <div class="table-responsive">

               <?php

               $sql3="SELECT * FROM `theme_park_parents`";
               $result_park = mysqli_query($db, $sql3);
               while($row_park=mysqli_fetch_assoc($result_park)){
               $theme_park_id = $row_park['id'];
               $theme_park_name = $row_park['name'];

               $tbl_name = "SELECT * from ticket where theme_park_parent_id = '$theme_park_id'";

               if (isset($_POST['activedata'])) {

                   $check_order = $_POST['activedata'];

                   if ($check_order == '1') {
                       $current_date = date("Y-m-d");
                       $tbl_name .= " and expire_date <= '$current_date'";

                       $checkActive = 1;
                   } else {

                       $current_date = date("Y-m-d");

                       //$tbl_name="`order` WHERE date_of_visit < '$current_date' ORDER BY date_of_visit DESC";

                       $tbl_name .= " and expire_date >= '$current_date'";

                       $checkActive = 0;
                   }
               }
               else{
                   if (isset($_GET['active'])) {

                       $check_order = $_GET['active'];

                       if ($check_order == '1') {

                           $current_date = date("Y-m-d");

                           $tbl_name .= " and expire_date <= '$current_date'";

                           $checkActive = 1;
                       } else {

                           $current_date = date("Y-m-d");

                           $tbl_name .= " and expire_date >= '$current_date'";

                           $checkActive = 0;
                       }
                   }
               }

               if (isset($_GET['active']) && $_GET['active'] == 3) {
                   $checkActive = 3;
               }

               $result_page = mysqli_query($db,$tbl_name);

               if (mysqli_num_rows($result_page) > 0) {
               echo "<h5>$theme_park_name</h5>";
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                  <thead>

                    <?php $status = $_SESSION['status']; ?>

                    <tr id="th-ticket">

                      <th class="th-11">Link</th>

                      <th class="th-12">Ticket Number</th>

                      <th class="th-13">Type</th>

                      <th class="th-14">Entitlement</th>

                      <th class="th-15">Expiration Date </th>

                      <th class="th-16">Status</th>


                      <!-- <th>Batch Number</th>

                     <th>Link</th>

                     <th>Trans No</th>-->

                      <th class="th-16">Ticket Details</th>





                    </tr>

                  </thead>



                  <tbody>

                    <?php


                      while ($row = mysqli_fetch_assoc($result_page)) {

                        $originalDate = $row["expire_date"];

                        $newDate = date("m/d/Y", strtotime($originalDate));

                        $current_date = date("Y/m/d");

                        if (strtotime($newDate) < strtotime($current_date))
                          $status = "inactive";
                        else
                          $status = "active";

                        echo "<tr>

                      <td>" . $row["set_link"] . "</td>

                      <td>" . $row["ticketshowid"] . "</td>

                       <td>" . ucwords($row["type"]) . "</td>

                         <td>" . $row["entitlement"] . "</td>

                      <td>" . $newDate . "</td>

                      <td>" . $status . "</td>

                      <td style='vertical-align: middle;'><a href='../History/AddHistory.php?id=" . $row['id'] . "&tab=edit' class='btn btn-info' role='button'> Ticket Details</a></td>

                     

                      

                 </tr>";
                      }



                    ?>



                  </tbody>


                </table>
               <?php } } ?>

              </div>

            </div>

          </div>

        </div>







        <!-- /.container-fluid -->

        <div class="col-md-12 text-center" style="display: flex;justify-content: center;"> <?php echo $pagination; ?>



        </div>



        <footer class="sticky-footer">

          <div class="container my-auto">

            <div class="copyright text-center my-auto">



            </div>

          </div>

        </footer>



    </div>

  <?php } ?>

  <!-- /.content-wrapper -->





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

        <div class="modal-footer">

          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

          <a class="btn btn-primary" href="login.html">Logout</a>

        </div>

      </div>

    </div>

  </div>

  </body>

  </html>

  <?php

  $checkActive = $_GET['active']; ?>

  <script>
    $('#orderid').change(function() {

      sValue = $("#orderid").val();

      // alert(sValue);





      $('#ssform').submit();

      Curl = window.location.origin;

      //alert(Curl);

      //window.location.href=Curl+"/app/OrlandoAdmin/Ticket/DetailsTicket.php?active="+sValue;
      window.location.href = Curl + "/cheapthrills/Ticket/DetailsTicket.php?active=" + sValue;





    });

    $('#orderid').val(<?= $checkActive ?>);
  </script>