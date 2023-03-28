<?php
include('../Config/Connection.php');
 session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
$id=$_GET['id'];

  $sql="SELECT * FROM ethnicity where id='$id'";
  $result=mysqli_query($db,$sql);
 
  $user=mysqli_fetch_assoc($result);
//var_dump($user);die;
if(isset($_POST['customer']))
{
    $ethncity_name=$_POST['name'];
       $customer_update = "UPDATE ethnicity SET 
       ethncity_name='$ethncity_name'
       WHERE id='$id'";

   mysqli_query($db,$customer_update); 
    header( "Location: EthnicityDetails.php" );

}
include('../includes/header.php');
?>







      <div id="content-wrapper">
       <div class="container-fluid">
	   
	     <div class="col-md-12">
		<h3>Update Ethnicity</h3>
	  <hr>
	   </div>	
	   
	    <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
	   
       <form action="UpdateEthnicity.php?id=<?=$id?>" autocomplete='off' method="post">
        <div class="form-group">
    <label for="fname">Ethnicity*</label>
    <input type="text" class="form-control" required name="name" id="name" aria-describedby="fname" value='<?=$user['ethncity_name']?>' placeholder="Ethncity *">
  </div>
   <div class="form-group" style="text-align:center;">
 <button type="submit"  name="customer"class="btn btn-primary">Submit</button>
 </div>
</form>
</div></div>
</div>
       
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
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>



  </body>

</html>
