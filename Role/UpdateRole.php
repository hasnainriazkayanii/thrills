<?php
 include('../Config/Connection.php');
 //Check Login
      session_start();
       $login_check=$_SESSION['id'];
     if ($login_check!='1')
     {
       header("../Login/login.php");
     }


    //End Login
     $id=$_GET['id'];
    $sql="SELECT * FROM login_user where id='$id'";
    $result=mysqli_query($db,$sql);
    $user=mysqli_fetch_assoc($result);
    // parameter
    if(isset($_POST['adminsubmit']))
    {
      $name=$_POST['name'];
      $email=$_POST['email'];
      $password=$_POST['password'];
      $mobnumber=$_POST['mobnumber'];
      $type=$_POST['type'];
      $level=$_POST['level'];
      $admin_update = "UPDATE login_user SET 
      user_name='$name',
      email= '$email',
      password='$password',
      mob_no='$mobnumber',
      status= '$type',
      level='$level'
      WHERE id='$id'";
      $result = mysqli_query($db, $admin_update);
      
     header( "Location: RoleDetails.php" );
    
    }
    //End Insert
    //Include Header
 include('../includes/header.php');
 //End Header
?>
      <div id="content-wrapper">
       <div class="container-fluid">
	  
	  <div class="col-md-12">
		<h3> Update Users</h3>
	  <hr>
	   </div>	
	 
	 
	   <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
	   
      <form action="" autocomplete='off' method="post" onblur="return validation();" name="vfrom">
        <div class="form-group">
    <label for="fname">Name *</label>
    <input type="text" required class="form-control" name="name" id="name"   placeholder=" Name *" value="<?=$user['user_name']?>"
    <span id="username"  class="text-danger font-weight-bold"> </span>
  </div>
  <div class="form-group">
    <label for="lname">Email *</label>
    <input type="email" class="form-control" name="email"id="email" value="<?=$user['email']?>" required placeholder="Email *" >
  </div>
   <div class="form-group">
    <label for="Homecity">Password *</label>
    <input type="text" class="form-control" name="password" id="password"  required placeholder="Password *" value="<?=$user['password']?>">
    <span id="homecity" class="text-danger font-weight-bold"></span>
  </div> <div class="form-group">
   <div class="form-group">
    <label for="mobnumber">Mobile Number *</label>
    <input type="text" maxlength="10" onkeypress="return AllowNumbersOnly(event)" required class="form-control" name="mobnumber" id="mob"  placeholder="Mobile Number *" value="<?=$user['mob_no']?>"
    <span id="mobileNumber" class="text-danger font-weight-bold"></span>
  </div>
  <label for="type">Role Type *</label> 
   <select class="form-control" name="type" onchange="role(this);">
 
    <option <?php if($user['status']=='1'){echo "selected";}?> value="1">admin</option>
    <option <?php if($user['status']=='0'){echo "selected";}?>   value="0">user</option>
   </select><br>

  <label for="level">Access Level *</label> 
   <select class="form-control" name="level">
 
    <option <?php if($user['level']=='1'){echo "selected";}?> value="1">Sales</option>
    <option <?php if($user['level']=='2'){echo "selected";}?> value="2">Sales Manager</option>
    <option <?php if($user['level']=='9'){echo "selected";}?>   value="9">Admin</option>
   </select><br>
  
 
   <div class="form-group" style="text-align:center;">
  <button type="submit"  name="adminsubmit"class="btn btn-primary">Submit</button>
  </div>
</form>

</div >
</div>
</div>
       

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


    <script type="text/javascript">
 $.validate({
    lang: 'en'
  });
  
  
     function AllowNumbersOnly(e) {
    var code = (e.which) ? e.which : e.keyCode;
    if (code > 31 && (code < 48 || code > 57)) {
      e.preventDefault();
    }
  }

function role(element){
  console.log(element);
  // Takes the element of role select
  if(element.value == 0){ //check if its user
    $('#level').hide();
  }else{
    $('#level').show();
  }

}
  
</script>

  </body>

</html>
