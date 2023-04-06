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
    $parent_id = $_POST['parentid'];
    $parkcode = $_POST['pcode'];
    $pactive = $_POST['pactive'];
   $create_date=time();
   $update_date=time();
   $park_insert = "INSERT INTO theme_parks (name,code,active,theme_park_parent_id)
    VALUES ('$park_name','$parkcode','$pactive','$parent_id')";
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
    $theme_parks_query = "SELECT * FROM  `theme_park_parents` where is_universal = 1 ORDER BY code ASC";
    $theme_parks = mysqli_query($db, $theme_parks_query);
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
    <label for="pcode">Park Code *</label>
    <select name="pcode" id="code" class="form-control" required>
        <option value="">Select Park Code</option>
        <?php

          while($theme_park = mysqli_fetch_assoc($theme_parks)) {
              $tp_name=$theme_park['name'];
              $tp_id = $theme_park['id'];
              $tp_code = $theme_park['code'];
              ?>

              <option data-id="<?=$tp_id?>" value="<?=$tp_code?>" ><?=$tp_code?></option>

              <?php
          }
        ?>
    </select>
    <input type="hidden" class="form-control" required name="parentid" id="theme_park_parent_id" aria-describedby="parentid" placeholder="Parent ID *">
  </div>
  
  <div class="form-group">
    <label for="pactive">Active*</label>
    <select name="pactive" id="active" class="form-control" required>
        <option value="1">Active</option>
        <option  value="0">Inactive</option>
    </select>
        
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

 
<script>
  $(function(){
    $('#code').on('change',function(){
      var selectedOption = $('#code option:selected');
      var selectedDataId = selectedOption.attr('data-id');
      $("#theme_park_parent_id").val(selectedDataId);
      console.log(selectedDataId);
    });
  });
</script>
  </body>

</html>
