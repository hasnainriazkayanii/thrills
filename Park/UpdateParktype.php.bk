<?php
include('../Config/Connection.php');
 session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
$id=$_GET['id'];

  $sql="SELECT * FROM  theme_parks where id='$id'";
  $result=mysqli_query($db,$sql);
 
  $user=mysqli_fetch_assoc($result);
  
//var_dump($user);die;
if(isset($_POST['customer']))
{
    $name=$_POST['name'];
    $active=$_POST['active'];
    $theme_park_parent_id=$_POST['pid'];
    $code=$_POST['code'];
       $record_update = "UPDATE theme_parks SET 
       name='$name',
       active='$active',
       code='$code',
       theme_park_parent_id='$theme_park_parent_id'
       WHERE id='$id'";
        mysqli_query($db,$record_update); 
        $action_by = $_SESSION['user_id'];
        $timestamp_insert = "INSERT INTO timestamps (type,object_id,action,action_by)
        VALUES ('Park','$id','Updated','$action_by')";
        $result = mysqli_query($db,$timestamp_insert);
    header( "Location: ParkDetails.php" );

}
include('../includes/header.php');
?>







      <div id="content-wrapper">
       <div class="container-fluid">
	   
	     <div class="col-md-12">
		<h3>Update Park</h3>
	  <hr>
	   </div>	
	   
	    <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
	       
	       
	       
	   
       <form action="UpdateParktype.php?id=<?=$id?>" autocomplete='off' method="post">
        <div class="form-group">
    <label for="name">Park Name *</label>
    <input type="text" class="form-control" required name="name" id="name" aria-describedby="fname" value='<?=$user['name']?>' placeholder="Park Name *">
    
      <label for="name">Theme Park Code*</label>
        <input type="text" class="form-control" required name="code" id="code" aria-describedby="code" value='<?=$user['code']?>' placeholder="Park Code *">
        
        <label for="name">Parent ID*</label>
            <input type="text" class="form-control" required name="pid" id="theme_park_parent_id" aria-describedby="pid" value='<?=$user['theme_park_parent_id']?>' placeholder="Parent ID*">
            
            <label for="name">Active (1=Yes 0=No)*</label>
                <input type="text" class="form-control" required name="active" id="active" aria-describedby="active" value='<?=$user['active']?>' placeholder="Active *">
                
                
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
