<?php
  include('../Config/Connection.php');
   session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
 if(isset($_POST['customer'])){
    $ethncity_name=$_POST['name'];
   $create_date=time();
   $update_date=time();
   $park_insert = "INSERT INTO ethnicity (ethncity_name,create_date,update_date)
    VALUES ('$ethncity_name','$create_date','$update_date')";
   if( $result = mysqli_query($db,$park_insert));
    header( "Location: EthnicityDetails.php" );
  
    }
    
    include('../includes/header.php');
?>


      <div id="content-wrapper">
	  
	   <div class="container-fluid">
	  <div class="col-md-12">
		<h3>Add Ethnicity</h3>
	   <hr>
	   </div>	
	 </div>
	 
       <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
       <form action="" autocomplete='off' method="post">
        <div class="form-group">
    <label for="fname">Ethnicity *</label>
    <input type="text" class="form-control" required name="name" id="name" aria-describedby="fname" placeholder="Ethnicity *">
  </div>
   <div class="form-group" style="text-align:center;">
 <button type="submit"  name="customer"class="btn btn-primary">Submit</button>
 
 </div>
</form>
</div>
</div>
        <!-- <div class="container-fluid">

          <!-- Breadcrumbs-->
          <!--<ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="index.html">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">404 Error</li>
          </ol>

          <!-- Page Content -->
          <!--<h1 class="display-1">404</h1>
          <p class="lead">Page not found. You can
            <a href="javascript:history.back()">go back</a>
            to the previous page, or
            <a href="index.html">return home</a>.</p>

        </div> -->
        <!-- /.container-fluid -->

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
          <!-- <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div> -->
      </div>
    </div>

 

  </body>

</html>
