<?php
  include('../Config/Connection.php');
   session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
 if(isset($_POST['status'])){
    $id = @$_GET['id'];
    $status_name = $_POST['status_name'];
    $status_id = $_POST['status_id'];
    $active = $_POST['active'];
    $status_update = "UPDATE status set status_name = '$status_name', status_ID = '$status_id', is_active='$active' where id = '$id'";

   if( $result = mysqli_query($db,$status_update));

    header( "Location: Status.php?success=1" );

    }
    else{
        if (@$_GET['id']){
            $id = @$_GET['id'];
            $fetch_status = "SELECT * from status where id = '$id'";
            $s_data = mysqli_query($db, $fetch_status);
            $r_data = mysqli_fetch_assoc($s_data);
        }
    }
    
    include('../includes/header.php');
?>


<div id="content-wrapper">
  <div class="container-fluid">
    <div class="col-md-12">
      <h3>Add Status</h3>
      <hr>
    </div>
  </div>

  <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
    <div class="col-md-7">
      <form action="UpdateStatus.php?id=<?php echo @$_GET['id']; ?>" autocomplete='off' method="post">
        <div class="form-group">
          <label for="name">Status Name *</label>
          <input type="text" class="form-control" required name="status_name" value="<?php echo @$r_data['status_name']; ?>" id="status_name" aria-describedby="referral"
            placeholder="Status Name ">
        </div>
        <div class="form-group">
          <label for="name">Status ID *</label>
          <input type="text" class="form-control" required name="status_id" id="tatus_id" value="<?php echo @$r_data['status_ID']; ?>" aria-describedby="referral"
            placeholder="Status ID ">
        </div>
        <div class="form-group">
          <label for="name">Active *</label>
          <select class="form-control" required name="active" id="active" aria-describedby="referral">
              <option value="1">Active</option>
              <option value="0">Not Active</option>
              </select>
        </div>
        
        <div class="form-group" style="text-align:center;">
          <button type="submit" name="status" class="btn btn-primary">Submit</button>

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


<script>
    
    var status_id = "<?php echo @$_GET['id']; ?>";
    if (status_id !=  ""){
        var active = "<?php echo @$r_data['is_active']; ?>";
        $('#active').val(active);
    }
    
</script>


</body>

</html>