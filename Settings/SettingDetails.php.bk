<?php

include('../Config/Connection.php');

session_start();
$login_check=$_SESSION['id'];


 //var_dump($data1);

if ($login_check!='1') {
 $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location:http://cheapthrillstix.com/app/appadmin/Login/login.php");
}


include('../includes/header.php');

?>

<style>



.btns-design a{text-decoration:none;color:#fff;}

@media only screen and (max-width: 678px) and (min-width: 0px)  {

	.btns-design{width:100%;color:#fff!important;     background-color: #007bff; margin: 10px 10px;;display:inline-block;}

}

@media only screen and (max-width: 3000px) and (min-width: 679px)  {

	.btns-design{width:23%;color:#fff!important;     background-color: #007bff; margin: 10px 10px;;display:inline-block;}

}

</style>







      <div id="content-wrapper">



        <div class="container-fluid">



          <!-- Breadcrumbs-->

          <!--<ol class="breadcrumb">

            <li class="breadcrumb-item">

              <a href="#">Dashboard</a>

            </li>

            <li class="breadcrumb-item active">Tables</li>

          </ol>-->



          <!-- DataTables Example -->

		<div class="row">

		  <div class="col-md-12">

		 <h2 style="text-align: center;">Settings</h2>

		  

		  </div>

		  </div>

		  <hr>

		  <br>

          <div class="card mb-3" style="border:none;margin-top: 7%;">

            

            <div class="card-body">

             

                <table>

                

                    <tr>

                        

                     <div class="col-md-3 text-center btns-design btn"><a href="../Ticket/TicketsDetails.php">Ticket Prices</a></div>

                       <div class="col-md-3 text-center btns-design btn"><a href="../Park/ParkDetails.php" >Parks</a></div>

                         

                      

                      

                      

                      <?php

         session_start();

      $status=$_SESSION['status'];

    

     if ($status=='1')

     {?>

      <div class="col-md-3 text-center btns-design btn" ><a href="../Role/RoleDetails.php">Users</a></div>

      <div class="col-md-3 text-center btns-design btn" ><a href="../Ethnicity/EthnicityDetails.php">Ethnicity</a></div>

       <?php  };?>

                  <div class="col-md-3 text-center btns-design btn" ><a href="sms_settings.php">SMS</a></div>

                  <div class="col-md-3 text-center btns-design btn" ><a href="text_messages.php">Text Messages</a></div>

                  <div class="col-md-3 text-center btns-design btn" ><a href="../Customers/import_customers.php">Import Customers</a></div>

                  <div class="col-md-3 text-center btns-design btn" ><a href="../Referrals/ReferralDetails.php">Referral Types</a></div>

                  <div class="col-md-3 text-center btns-design btn" ><a href="../Settings/Status.php">Update Status</a></div>
                  <div class="col-md-3 text-center btns-design btn" ><a href="../Park/ParkParentDetails.php">Parks Parent</a></div>
                  <div class="col-md-3 text-center btns-design btn" ><a href="../Settings/Purposetype.php">Purpose Of Trip</a></div>
                  <div class="col-md-3 text-center btns-design btn" ><a href="../Settings/Timeslots.php">Timeslots</a></div>
    

 </tr>

                  



                

                </table>

             

            </div>

           

          </div>



        



        </div>

        <!-- /.container-fluid -->

		<div class="col-md-12 text-center" style="display: flex;justify-content: center;"> <?php echo $pagination;?> </div>



        <!-- Sticky Footer -->

        <footer class="sticky-footer">

          <div class="container my-auto">

            <div class="copyright text-center my-auto">

              <!-- <span>Copyright © Your Website 2018</span> -->

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

