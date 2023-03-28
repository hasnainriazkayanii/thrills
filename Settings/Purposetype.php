<?php
 include('../Config/Connection.php');

  session_start();
  $login_check=$_SESSION['id'];
  $level = $_SESSION['level'] ?? 1;
  $status=$_SESSION['status']; 

  if($login_check!='1') { 
    header("location: ../Login/login.php");

  }

$sql = "SELECT * FROM purpose_type";
$result = mysqli_query($db, $sql);
$datas =mysqli_fetch_all($result, MYSQLI_ASSOC);

include('../includes/header.php');
?>

  

<div id="content-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8" style="float:left;">
          <h3>Add Purpose type</h3>
        </div>
      </div>
    </div>
    <hr>
    <br>

    <div class="card mb-3">
      <div class="card-header">
        <i class="fas fa-table"></i>
        type</div>
      <div class="card-body">
        <div class="table-responsive">
            <form action="Purposetypeadd.php" method="post">
          <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th>type</th>
                <th>Details</th>
                <th>Delete</th>
              </tr>
            </thead>

            <tbody class="addmoreslots">

              <?php
       if (count($datas) > 0) {

          foreach($datas as $data ) { ?>
              <tr>
               <td> <input class="form-control" type='text' name="purpose[]" value="<?php echo $data['purpose']?>"></td>
               <td>
                      <label class="switch">
                          <select class="form-control"  name="details[]"   >
                            <option  <?php if($data['details']==1) echo 'selected' ?> value="1">on</option>
                            
                            <option <?php if($data['details']==0) echo 'selected' ?> value="0">off</option>
                          
                          </select>
                    </label>
                      
                </td>
               <td><button class="delete btn btn-danger">Delete</button></td>

              </tr>
              <?php    
}
}
                ?>

            </tbody>
            <tfooter>
                
                <tr><td><a href="javascript:void(0)" class="slot btn btn-dark" typ="button">Add More</a></td></tr>
                
            </tfooter>
          </table>
          <input type="submit" name="submit" class="btn btn-success">
          </form>
        </div>
      </div>

    </div>

  </div>
  <!-- /.container-fluid -->
  <!-- Sticky Footer -->
  <!-- <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Universal Orlando Resort 2018</span>
            </div>
          </div>
        </footer> -->

</div>


</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>




</body>

<script>
    
    $('.slot').click(function(){
        //Check maximum number of input fields

            var html='<tr><td> <input class="form-control" type="text" name="purpose[]"></td><td><label class="switch"><select class="form-control"  name="details[]"   ><option value=1>on</option><option value=0>off</option></select></label></td><td><button class="delete btn btn-danger">Delete</button></td></tr>';
            $('.addmoreslots').append(html); //Add field html
       
    });
    
    $('body').delegate('.delete','click',function(){
        //Check maximum number of input fields

          
            $(this).parent('td').parent('tr').remove(); //Add field html
       
    });
    
</script>

</html>