<?php

include('../Config/Connection.php');

//Login  chack 

session_start();

$login_check = $_SESSION['id'];

if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}

// Login End

//Select History

// $sql = "SELECT DISTINCT park,theme_park_parents.name as pname,barcode,ticket.set_link as setlink ,ticket.name_on_ticket as tname,ticket.active as st FROM history LEFT JOIN theme_parks ON history.park = theme_parks.name LEFT JOIN theme_park_parents ON theme_parks.theme_park_parent_id = theme_park_parents.id LEFT JOIN ticket ON theme_park_parents.id = ticket.theme_park_parent_id where ticket.active='true' order by theme_park_parents.name DESC 
// ";
$current_date = date("Y-m-d");
$sql = "SELECT theme_park_parents.name as pname, ticket.ticketshowid as barcode,ticket.set_link as setlink ,ticket.name_on_ticket as tname,ticket.active as st FROM ticket LEFT JOIN theme_park_parents ON theme_park_parents.id = ticket.theme_park_parent_id where ticket.expire_date >='$current_date' AND ticket.active='true' and ticket.type IN ('adult','youth') AND ticket.ticketshowid IN (SELECT DISTINCT barcode FROM `history`) order by field(pname,'Universal') asc, ticket.expire_date asc
          ";
          
$result1 = mysqli_query($db, $sql);






include('../includes/header.php');



?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>






<div id="content-wrapper">



  <div class="container-fluid">

    <div class="col-md-12">

      <h3> Usage Details</h3>

      <hr>

    </div>

  </div>

  <div class="row">

    <div class="col-md-12">

      <div class="col-md-8" style="float:left;">
        <h3></h3>
      </div>
      
<?php if($level >= 8): //start if ====1 ?>

      <div class="col-md-4 text-right" style="float:left;"><a href="../History/AddHistory.php" class="btn btn-primary">Add Usage</a></div>

    </div>
    
    <?php endif; // end if =====1?>

  </div>

  <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">

    <div class="col-md-7">

      <form action="History.php" method="post">

        <label for="fname">Barcode</label>

        <select required class="form-control my" name="ticket_id" id="items">

          <option value="0">Please Select</option>

          <?php
          //print_r (mysqli_fetch_assoc($result1));         

          while ($row = mysqli_fetch_assoc($result1)) { ?>

            <option value="<?= $row['barcode'] ?>"><?= $row['setlink'] ?><?php if ($row['tname'] != "") {
                                                                                            echo "break";
                                                                                          }
                                                                                          ?><?= $row['tname'] ?>break<?= $row['barcode'] ?></option>

          <?php

          }



          ?>

        </select><br>





        <div class="form-group" style="text-align:center;">

          <button type="submit" name="customer" class="btn btn-primary">Submit</button>



        </div>

      </form>



      <div class='row'>



        <!--<div class='col-sm-12'>  </div>-->



        <?php

        if (isset($_POST['customer'])) {

          $ticketdetails = $_POST['ticket_id'];

          $sql_group = "SELECT * FROM history WHERE barcode='$ticketdetails' group by history_date";



          $result_group = mysqli_query($db, $sql_group);



          $sql_unite = "SELECT history.*, ticket.*,                                    

                  theme_park_parents.code as theme_park_code 

                        FROM history

                        join ticket on history.barcode=ticket.ticketshowid 

                        LEFT JOIN theme_park_parents on ticket.theme_park_parent_id=theme_park_parents.id

                        WHERE history.barcode = '$ticketdetails'

                        GROUP BY history.barcode";



          $result_unit = mysqli_query($db, $sql_unite);

          if ($result_unit) {

            while ($row_unit = mysqli_fetch_assoc($result_unit)) {

              echo "<div class='col-sm-12'>";

              echo "<p style='font-weight: bold; font-size: 20px; margin-bottom: 0px;'>" . $row_unit['theme_park_code'] . " " . substr($ticketdetails, -4) . "</p>";



              echo "<p style='font-weight: bold; font-size: 20px; margin-bottom: 0px;'>" . $row_unit['entitlement'] . " - " . ucfirst($row_unit['type']) . "</p>";

              echo "<hr>";

              echo "</div>";
            }
          }



          while ($row_group = mysqli_fetch_assoc($result_group)) {

            $sql_ticket = "SELECT * FROM history WHERE barcode='$ticketdetails' and history_date='" . $row_group['history_date'] . "' ORDER BY history_time ASC";

            $result_ticket = mysqli_query($db, $sql_ticket);

            if ($result_ticket) {





              //$a=0;

              $cnt = 0;

              echo "<div class='col-sm-12'>";

              echo "<p style='font-weight: bold; font-size: 15px; margin-bottom: 0px;'>" . date("l F d", strtotime($row_group['history_date'])) . "</p>";

              echo "</div>";





              while ($user = mysqli_fetch_assoc($result_ticket)) {

                //var_dump($user);die;

                if ($cnt == 0) {

                  echo "<div class='col-sm-12'>";

                  echo "<p style='margin-bottom: 0px;'>" . $user['park'] . " " . date("g:i A", strtotime($user['history_time'])) . "</p>";

                  echo "</div>";
                } else {

                  echo "<div class='col-sm-12'>";

                  if ($user['method_transfer'] != "no") {

                    echo "<p style='margin-bottom: 0px;'>Then " . $user['method_transfer'] . " to " . $user['park'] . " at " . date("g:i A", strtotime($user['history_time'])) . "</p>";
                  }



                  echo "</div>";
                }



                $cnt++;
              }



              echo "<div class='col-sm-12'><hr></div>";
            }
          }
        }

        ?>

      </div>

    </div>

  </div>

</div>



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

      <!-- <div class="modal-footer">

            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

            <a class="btn btn-primary" href="login.html">Logout</a>

          </div>

        </div> -->

    </div>

  </div>

  <script>
    // $(function () {
    //       $("#items").change(function () {
    //           var selectedText = $(this).find("option:selected").text();
    //           selectedText=$(this).find("option:selected").text().replace("break","");
    //           $(this).find("option:selected").text()=selectedText;
    //           var selectedValue = $(this).val();
    //           //alert("Selected Text: " + selectedText + " Value: " + selectedValue);
    //       });
    //   });
    $(document).ready(function() {

      function templateResult(item, container) {
        // replace the placeholder with the break-tag and put it into an jquery object
        return $('<span>' + item.text.replace(/break/g, '<br/>') + '</span>');
      }

      function templateSelection(item, container) {
        // replace your placeholder with nothing, so your select shows the whole option text
        return item.text.replace('break', '');
      }

      $('.my').select2({
        templateResult: templateResult,
        templateSelection: templateSelection
      });

    });
  </script>





  </body>



  </html>