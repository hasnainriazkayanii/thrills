<?php

include('../Config/Connection.php');

//Login check

session_start();

$login_check = $_SESSION['id'];

if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}

$base_url =  "http://" . $_SERVER['SERVER_NAME'];

//End 



if (isset($_POST['submitticket'])) {
  $name_on_ticket = $_POST['name_on_ticket'];

  $ticketpass = $_POST['ticketpass'];

  $node = $_POST['node'];

  $trans_no = $_POST['trans_no'];

  $print_date = $_POST['print_date'];

  $batchnumber = $_POST['batchnumber'];

  $set = $_POST['set'];

  $entitlement = $_POST['entitlement'];

  $type = $_POST['type'];

  $gender = $_POST['gender'];

  $broker = $_POST['broker'];

  $purchase_place = $_POST['purchase_place'];

  $price = $_POST['price'];

  $theme_park_parent_id = $_POST['theme_park_parent_id'];

  $expire_date = $_POST['date'];

  $active = $_POST['active'];



  $ticketshowid = $_POST['ticketshowid'];



  /* $ticket_number=explode(" ",$ticketshowid);*/

  $ticket_number =  preg_split('/\s+/',  $ticketshowid);

  /*  print_r($ticket_number);die;*/



  $loopCount = count($ticket_number);

  for ($i = 0; $i < $loopCount; $i++) {

    $ticketshowid = $ticket_number[$i];

    /* var_dump($ticketshowid); die;*/

    if (!empty($ticketshowid)) {



      $ticket_insert = "INSERT INTO ticket(`name_on_ticket`,`ticket_type`,`type`,`gender`,purchased_place,cost,ticketshowid,expire_date,node,broker,batch_number,set_link,entitlement,trans_number,print_date,active,theme_park_parent_id)

              VALUES ('$name_on_ticket','$ticketpass','$type', '$gender','$purchase_place','$price','$ticketshowid','$expire_date','$node','$broker','$batchnumber','$set','$entitlement','$trans_no','$print_date','$active', $theme_park_parent_id)";

      $result = mysqli_query($db, $ticket_insert);
      $id  = mysqli_insert_id($db);
      if($id){
        $action_by = $_SESSION['user_id'];
        $timestamp_insert = "INSERT INTO timestamps (type,object_id,action,action_by)
        VALUES ('Ticket','$id','Created','$action_by')";
      
        $result = mysqli_query($db,$timestamp_insert);
      }

      if ($result == 'true') {

        echo "<script>alert('Added succesfully');

                      document.location.href='$base_url/cheapthrills/Ticket/DetailsTicket.php?active=0';</script>";
      }
    }
  }

  // header( "Location: DetailsTicket.php" );



}

include('../includes/header.php');

?>

<?php
$theme_park_parents_query = "SELECT * FROM theme_park_parents ORDER BY code DESC";
$theme_park_parents = mysqli_query($db, $theme_park_parents_query);
?>



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

<div id="content-wrapper">


  <div class="container" style="border:1px solid #eee;">

    <div class="col-md-12 small-view">
      <div class="col-md-11 new-header">

        <h3 class="new-fonts">Add Ticket</h3>

        <hr>

      </div>
    </div>

    <div class="col-md-12" style="display:table;">

      <form action="AddTicket.php" autocomplete='off' method="post">

        <div class="" style="display:table;">

          <div class="col-md-6" style="float:left;">

            <!--  <label for="fname">Barcode *</label>-->

            <!--<input type="text" class="form-control"  required name="ticketshowid" id="ticketshowid" aria-describedby="fname" placeholder="Barcode *" value="" >-->

            <div class="left-col">

              <label for="fname">Barcode </label>

            </div>

            <div class="right-col">

              <textarea rows="3" cols="84" required name="ticketshowid" id="ticketshowid" class="form-control"></textarea>

            </div>

          </div>



          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="fname">Name On Ticket </label>

            </div>

            <div class="right-col">

              <input type="text" class="form-control" name="name_on_ticket" id="name" aria-describedby="node" placeholder="Name On Ticket  *">

            </div>

          </div>






<!--
          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              Replace1

            </div>

            <div class="right-col">

              Replace1

            </div>

          </div>

-->

          <div class="col-md-6" style="float:left;">

           <div class="left-col">

              <label for="fname">Entitlement</label>

            </div>
            
           

            <div class="right-col">

              <input type="text" class="form-control" name="entitlement" id="entitlement" aria-describedby="entitlement" placeholder="Ticket Entitlement">

            </div>
            

          </div>

<!--

          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="fname">Trans No</label>

            </div>

            <div class="right-col">

              <input type="text" class="form-control" name="trans_no" id="trans_no" aria-describedby="trans_no" placeholder="Trans No *">

            </div>
            

          </div>

-->



          <div class="col-md-6" style="float:left;">

           
           <div class="left-col">

              <label for="type">Age Type</label>

            </div>

            <div class="right-col">

                <select class="form-control" name="type">

                <option value="adult">Adult</option>

                <option value="child">Child</option>

                <option value="youth">Youth</option>

                <option value="comp">Comp</option>

              </select>

            </div>
            

          </div>
<!--
          <div class="col-md-6" style="float:left;">

           
           <div class="left-col">

              <label for="fname">Batch Number</label>

            </div>

            <div class="right-col">

              <input type="text" class="form-control" placeholder='Batch Number *' name="batchnumber" id="batchnumber" aria-describedby="batchnumber">

            </div>
            

          </div>

-->

          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="type">Ticket/Pass</label>

            </div>

            <div class="right-col">

              <select class="form-control" name="ticketpass">



                <option value=" 1">Regular Ticket</option>

                <option value="2">Annual Pass</option>

              </select>

            </div>

          </div>

<!--

          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              INSERT

            </div>

            <div class="right-col">

              INSERT

            </div>
            

          </div>

-->




<!--
          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              INSERT2

            </div>

            <div class="right-col">

INSERT2

            </div>

          </div>
-->

          <div class="col-md-6" style="float:left;">
            <div class="left-col">
                <label for="fname">Set Link</label>
            </div>
            <div class="right-col">
                <input type="text" class="form-control" name="set" id="set" aria-describedby="set" placeholder="Set Link *">
                </div>
                
            
            <!--
            <div class="left-col">

              <label for="gender">Gender</label>

            </div>

            <div class="right-col">

              <select class="form-control" name="gender">

                <option value="Male">Male</option>

                <option value="Female">Female</option>

              </select>

            </div>
            -->

          </div>



          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="fname">Broker</label>

            </div>

            <div class="right-col">

              <input type="text" class="form-control" name="broker" id="ticketshow" aria-describedby="broker" placeholder="Broker*" value="">

            </div>

          </div>


<!--
          <div class="col-md-6" style="float:left;">

            
            <div class="left-col">

              <label for="place">Purchase place</label>

            </div>

            <div class="right-col">

              <select class="form-control" name="purchase_place">

                <option value="Attraction Tickets Direct">Attraction Tickets Direct</option>

                <option value="Travel Republic">Travel Republic</option>

                <option value="Expedia">Expedia</option>

                <option value="At The Park">At The Park</option>

              </select>

            </div>
            

          </div>
-->


          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="fname">Purchase Price</label>

            </div>

            <div class="right-col">

              <input type="text" class="form-control" placeholder='Price *' name="price" id="cost" aria-describedby="price">

            </div>

          </div>





          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="fname">Expiration Date</label>

            </div>

            <div class="right-col">

              <input type="date" class="form-control" required name="date" id="date" aria-describedby="date" placeholder="Expire Date *">

            </div>

          </div>

          <!-- theme park -->
          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="place">Parent Theme Park*</label>

            </div>

            <div class="right-col">

              <select class="form-control" name="theme_park_parent_id">

                <?php

                while ($theme_park_parent = mysqli_fetch_assoc($theme_park_parents)) {
                  $tpp_name = $theme_park_parent['name'];
                  $tpp_id = $theme_park_parent['id'];
                  $tpp_code = $theme_park_parent['code'];
                ?>

                  <option value="<?= $tpp_id ?>"><?= $tpp_name ?></option>

                <?php
                }
                ?>
              </select>
            </div>
          </div>



          <div class="col-md-6" style="float:left;">

            <div class="left-col">

              <label for="place">Active</label>

            </div>

            <div class="right-col">

              <select class="form-control" name="active">

                <option value="True">true</option>

                <option value="False">false</option>



              </select>

            </div>

          </div>

        </div>

        <br>



        <div class="col-md-12 text-center small-btn">

          <div class="form-group" style="text-align:center;">

            <button type="submit" name="submitticket" class="btn btn-primary">Submit</button>

          </div>

        </div>



      </form>

    </div>

  </div>



  <!-- /.container-fluid -->



  <!-- Sticky Footer -->

  <footer class="sticky-footer">

    <div class="container my-auto">

      <div class="copyright text-center my-auto">

        

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









  </body>



  </html>