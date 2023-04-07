<?php
  include('../Config/Connection.php');
   session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
 if(isset($_POST['customer'])){
    $park_name=$_POST['name'];
   $create_date=time();
   $update_date=time();
   $park_insert = "INSERT INTO theme_parks (name)
    VALUES ('$park_name')";
   if( $result = mysqli_query($db,$park_insert));
    $id  = mysqli_insert_id($db);
    if($id){
        $action_by = $_SESSION['user_id'];
        $timestamp_insert = "INSERT INTO timestamps (type,object_id,action,action_by)
        VALUES ('Park','$id','Created','$action_by')";
        $result = mysqli_query($db,$timestamp_insert);
    }

    header( "Location: ParkDetails.php" );
    }
    
    include('../includes/header.php');
?>


      <div id="content-wrapper">
	  
	   <div class="container-fluid">
	  <div class="col-md-12">
		<h3>Add Park</h3>
	   <hr>
	   </div>	
	 </div>
	 
       <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
       <form action="AddParktype.php" autocomplete='off' method="post">
        <div class="form-group">
    <label for="pname">Park Name *</label>
    <input type="text" class="form-control" required name="name" id="name" aria-describedby="fname" placeholder="Park Name *">
  </div>
  
  <div class="form-group">
    <label for="parentid">Parent ID *</label>
    <input type="text" class="form-control" required name="parentid" id="theme_park_parent_id" aria-describedby="parentid" placeholder="Parent ID *">
  </div>
  
  <div class="form-group">
    <label for="pcode">Park Code *</label>
    <input type="text" class="form-control" required name="pcode" id="code" aria-describedby="pcode" placeholder="Park Code*">
  </div>
  
  <div class="form-group">
    <label for="pactive">Active*</label>
    <input type="text" class="form-control" required name="pactive" id="active" aria-describedby="pactive" placeholder="Active*">
  </div>
  
  
   <div class="form-group" style="text-align:center;">
 <button type="submit"  name="customer"class="btn btn-primary">Submit</button>
 
 </div>
</form>
</div>
</div>


        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span></span>
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
