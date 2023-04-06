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
$theme_parks_query = "SELECT * FROM  `theme_park_parents` where is_universal = 1 ORDER BY code ASC";
$theme_parks = mysqli_query($db, $theme_parks_query);
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
                        <input type="text" class="form-control" required name="name" id="name" aria-describedby="fname"
                            value='<?=$user['name']?>' placeholder="Park Name *">
                            <br>
                        <label for="name">Theme Park Code*</label>
                        <select name="code" id="code" class="form-control" required>
                            <option value="">Select Park Code</option>
                            <?php

                              while($theme_park = mysqli_fetch_assoc($theme_parks)) {
                                  $tp_name=$theme_park['name'];
                                  $tp_id = $theme_park['id'];
                                  $tp_code = $theme_park['code'];
                                  ?>
                                  <option <?=$user['code']==$tp_code?'selected':''?> data-id="<?=$tp_id?>" value="<?=$tp_code?>"><?=$tp_code?></option>

                            <?php  } ?>
                            
                        </select>
                        <input type="hidden" class="form-control" required name="pid" id="theme_park_parent_id"
                            aria-describedby="pid" value='<?=$user['theme_park_parent_id']?>' placeholder="Parent ID*">
                          <br>
                        <label for="name">Active*</label>
                        <select name="active" id="active" class="form-control" required>
                            <option <?=$user['active']==1?'Selected':''?> value="1">Active</option>
                            <option <?=$user['active']==0?'Selected':''?> value="0">Inactive</option>
                        </select>


                    </div>
                    <div class="form-group" style="text-align:center;">
                        <button type="submit" name="customer" class="btn btn-primary">Submit</button>




                    </div>
                </form>
            </div>
        </div>
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
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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