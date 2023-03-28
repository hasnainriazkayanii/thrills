<?php
 include('../Config/Connection.php');

  session_start();
  $login_check=$_SESSION['id'];
  $level = $_SESSION['level'] ?? 1;
  $status=$_SESSION['status']; 

  if($login_check!='1') { 
    header("location: ../Login/login.php");

  }

$sql = "SELECT * FROM time_slots order by time ASC";
$result = mysqli_query($db, $sql);
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

include('../includes/header.php');
?>

  

<div id="content-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-8" style="float:left;">
          <h3>Status</h3>
        </div>
        <div class="col-md-4 text-right" style="float:left;"><a href="../Settings/AddStatus.php"
            class="btn btn-primary">Add Status</a></div>
      </div>
    </div>
    <hr>
    <br>

    <div class="card mb-3">
      <div class="card-header">
        <i class="fas fa-table"></i>
        Status</div>
      <div class="card-body">
        <div class="table-responsive">
            <form action="Timeslotsadd.php" method="post">
          <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th>time</th>
                <th>slots</th>
                <th>Delete</th>
              </tr>
            </thead>

            <tbody class="addmoreslots">

              <?php
       if (count($data) > 0) {

          foreach($data as $row) { ?>
              <tr>
               <td> <input class="form-control" type='time' name="time[]" value="<?php echo $row['time']?>"></td>
               <td> <input class="form-control" type='int' name="slots[]"  value="<?php echo $row['slots']?>" ></td>
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


            var html='<tr><td> <input class="form-control" type="time" name="time[]"></td><td> <input class="form-control" type="int" name="slots[]" ></td><td><button class="delete btn btn-danger">Delete</button></td></tr>';
            $('.addmoreslots').append(html); //Add field html
       
    });
    
    $('body').delegate('.delete','click',function(){
        //Check maximum number of input fields

          
            $(this).parent('td').parent('tr').remove(); //Add field html
       
    });
    
</script>

</html>