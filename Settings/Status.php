<?php
 include('../Config/Connection.php');

  session_start();
  $login_check=$_SESSION['id'];
  $level = $_SESSION['level'] ?? 1;
  $status=$_SESSION['status']; 

  if($login_check!='1') { 
    header("location: ../Login/login.php");

  }

$sql = "SELECT * FROM status";
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
          <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
            <thead>

              <tr>
                <th>Name</th>
                <th>Active</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>

            <tbody>

              <?php
       if (count($data) > 0) {

          foreach($data as $row) { ?>
              <tr>
                <td><?php echo $row["status_name"]; ?></td>
                <td><?php if ($row['is_active'] == 1) { echo "Active"; } else {echo "Not Active";} ?></td>

                <td><a href=../Settings/UpdateStatus.php?id=<?php echo $row["id"]; ?> class='btn btn-info' role='button'>
                    Edit</a></td>

                <td><a onclick="return confirm('Are you sure you want to delete?')"
                    href=../Settings/StatusDelete.php?id=<?php echo $row['id'];?> class='btn btn-danger' role='button'> Delete</a>
                </td>

              </tr>
              <?php    
}
}
                ?>

            </tbody>
          </table>
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

</html>