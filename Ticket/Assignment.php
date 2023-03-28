<?php

 include('../Config/Connection.php');

  session_start();

      $login_check=$_SESSION['id'];

       //var_dump($data1);

    if ($login_check!='1') {
      $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
        header("location: ../Login/login.php");

    }

     $ticketshowid=$_GET['id'];









 $ethnicityname = "SELECT guest.`guest_name`,guest.`guest_mobile`,guest.`login_id`,guest.`ticket_id`,`order`.`option`,`order`.`assign` FROM `order` INNER JOIN guest ON `guest`.order_id=`order`.id AND guest.`ticket_id`='$ticketshowid' AND `order`.`assign`='1'";

/*var_dump($ethnicityname); die;*/

   $ethnicitsult = mysqli_query($db,$ethnicityname);

include('../includes/header.php');

?>







      <div id="content-wrapper">



        <div class="container-fluid">



         <div class="row">

      <div class="col-md-12">

      <div class="col-md-8" style="float:left;"><h3>Assignment</h3></div>

    <!--   <div class="col-md-4 text-right" style="float:left;" ><a href="../Park/AddParktype.php" class="btn btn-primary">Add Park</a></div> -->

      </div>

      </div>

      <hr>

      <br>

          <!-- Breadcrumbs-->

         <!--  <ol class="breadcrumb">

            <li class="breadcrumb-item">

              <a href="#">Dashboard</a>

            </li>

            <li class="breadcrumb-item active">Tables</li>

          </ol> -->



          <!-- DataTables Example -->

          <div class="card mb-3">

            <div class="card-header">

              <i class="fas fa-table"></i>

              Assignment</div>

            <div class="card-body">

              <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                  <thead>

                    

                    <tr>
                      <tr>
                        Order Date
                      </tr>

                        <th>Guest Name</th>

                     <!-- <th>Guest Mobile</th> -->

                       <th>ticket Id</th>

                     <th>Login Id</th>

                    <th>Assign</th>

                  

                    <!-- <th>Batch Number</th>

                     <th>Link</th>

                     <th>Trans No</th>-->

                    

                    </tr>

                  </thead>

                  

                    <tbody>

             <?php

                    if (mysqli_num_rows($ethnicitsult) > 0)

                    {

                      while($row = mysqli_fetch_assoc($ethnicitsult)) 

                    {

                        /*  $originalDate=$row["expire_date"];

                       $newDate = date("m/d/Y", strtotime($originalDate));*/

                     echo "<tr>
                     <td> xx/xx/xxxx </td>

                      <td>".$row["guest_name"]."</td>

                       <td>".$row["ticket_id"]."</td>

                         <td>".$row["login_id"]."</td>

                         <td>".$row["assign"]."</td>



                     

                      

                 </tr>";

                      }

                    }

                ?>

                  

                      </tbody>

                </table>

              </div>

            </div>

           <!--  <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->

          </div>



          <!-- <p class="small text-center text-muted my-5">

            <em>More table examples coming soon...</em>

          </p> -->



        </div>

        <!-- /.container-fluid -->

  <!-- <div class="col-md-12 text-center" style="display: flex;justify-content: center;"> <?php //echo $pagination;?> </div> -->

        <!-- Sticky Footer -->

        <footer class="sticky-footer">

          <div class="container my-auto">

            <div class="copyright text-center my-auto">

              <span>Copyright © Universal Orlando Resort 2018</span>

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

          <div class="modal-footer">

            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

            <a class="btn btn-primary" href="login.html">Logout</a>

          </div>

        </div>

      </div>

    </div>





  </body>



</html>

