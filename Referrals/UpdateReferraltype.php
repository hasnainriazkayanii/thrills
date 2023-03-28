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
    $id = $_POST['id'] ?? 0;
    
    $referral_update = "UPDATE referral_types SET name='$name' WHERE id='$id' ";

   if( $result = mysqli_query($db,$referral_update));
    header( "Location: ReferralDetails.php?success=1" );

    }
    
    
    if(isset($_GET['id'])){
      $id = $_GET['id'] ?? 0;

      $sql_data = "SELECT * FROM referral_types WHERE id='$id'";
      $s_data = mysqli_query($db, $sql_data);
      
      $r_data = mysqli_fetch_assoc($s_data);
    }
      
    include('../includes/header.php');
?>


<div id="content-wrapper">
  <div class="container-fluid">
    <div class="col-md-12">
      <h3>Update Referral Types</h3>
      <hr>
    </div>
  </div>
  
  
  <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
    <div class="col-md-7">
      <form action="UpdateReferraltype.php" autocomplete='off' method="post">

        <input type="hidden" class="form-control" required name="id" id="rid" value=" <?php echo $r_data['id'] ?? 0 ?>">

        <div class="form-group">
          <label for="name">Referral Name *</label>
          <input type="text" class="form-control" required name="name" id="name" value="<?php echo $r_data['name'] ?? '' ?>" aria-describedby="referral" placeholder="Referral Name ">
        </div>
        <div class="form-group" style="text-align:center;">
          <button type="submit" name="referral" class="btn btn-primary">Update</button>

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