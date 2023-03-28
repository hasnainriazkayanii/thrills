<?php
  include('../Config/Connection.php');
   session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
 if(isset($_POST['referral'])){

    $name = $_POST['name'] ?? '';
    $referral_insert = "INSERT INTO referral_types (name) VALUES ('$name')";

   if( $result = mysqli_query($db,$referral_insert));

    header( "Location: ReferralDetails.php" );

    }
    
    include('../includes/header.php');
?>


<div id="content-wrapper">
  <div class="container-fluid">
    <div class="col-md-12">
      <h3>Add Referral Types</h3>
      <hr>
    </div>
  </div>

  <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
    <div class="col-md-7">
      <form action="AddReferraltype.php" autocomplete='off' method="post">
        <div class="form-group">
          <label for="name">Referral Name *</label>
          <input type="text" class="form-control" required name="name" id="name" aria-describedby="referral"
            placeholder="Referral Name ">
        </div>
        <div class="form-group" style="text-align:center;">
          <button type="submit" name="referral" class="btn btn-primary">Submit</button>

        </div>
      </form>
    </div>
  </div>

</div>


</div>


<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

</body>

</html>