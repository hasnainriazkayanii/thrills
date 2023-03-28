<?php

include('../Config/Connection.php');

session_start();

      $login_check=$_SESSION['id'];

       //var_dump($data1);

    if ($login_check!='1') {

       

        header("location: ../Login/login.php");

    }

$sql = "SELECT * FROM guest";

$result = mysqli_query($db, $sql);



include('../includes/header.php');

?>









      <div id="content-wrapper">



        <div class="container-fluid">



     <!--     

      <div class="row">

      <div class="col-md-12">

      <div class="col-md-8" style="float:left;"><h3>Guest Details</h3></div>

      <div class="col-md-4 text-right" style="float:left;" ><a href="../Customers/AddCustomer.php" class="btn btn-primary">Add Customer</a></div>

      </div>

      </div>

      <hr>

      <br>-->

          <div class="card mb-3">

            <div class="card-header">

              <i class="fas fa-table"></i>

             Guest Details</div>

            <div class="card-body">

              <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                  <thead>

                    <tr>

                      <th>Guest_Name</th>

                      <th>Guest Mobile </th>

                      <th>Date</th>

                    </tr>

                  </thead>

                  

                  <tbody>

                    <?php

       if (mysqli_num_rows($result) > 0) {



while($row = mysqli_fetch_assoc($result)) {

echo "<tr>

<td>".$row["guest_name"]."</td>

<td>".$row["guest_mobile"]."</td>

<td>".$row["created_on"]."</td>

</tr>";



}

}

                ?>

                  

                      </tbody>

                </table>

              </div>

            </div>

            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>

          </div>



        



        </div>

        <!-- /.container-fluid -->



        <!-- Sticky Footer -->

        <footer class="sticky-footer">

          <div class="container my-auto">

            <div class="copyright text-center my-auto">

              <span>Copyright © Your Website 2018</span>

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

