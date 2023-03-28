<?php
 include('../Config/Connection.php');
  session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
/*$sql = " SELECT * FROM `ethnicity`  ";
$result = mysqli_query($db, $sql);*/
$tbl_name="ethnicity";
$targetpage = "../Ethnicity/EthnicityDetails.php";
include('../includes/pagination.php');
include('../includes/header.php');

?>

      <div id="content-wrapper">

        <div class="container-fluid">

				 <div class="row">
		  <div class="col-md-12">
		  <div class="col-md-8" style="float:left;"><h3>Ethnicity Details</h3></div>
		  <div class="col-md-4 text-right" style="float:left;" ><a href="../Ethnicity/AddEthnicity.php" class="btn btn-primary">Add Ethnicity</a></div>
		  </div>
		  </div>
		  <hr>
		  <br>
          <!-- Breadcrumbs-->
         <!--  <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Tables</li>
          </ol> -->

          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
             Ethnicity Details</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                     <?php $status=$_SESSION['status']; ?> 
                    <tr>
                      <th> Name</th>
                        <?php if($status=='1')
                      {?>
                       <th>Edit</th>
                      <th>Delete</th>
                      <?php };?>
                    </tr>
                  </thead>
                  
                  <tbody>
                    <?php
       if (mysqli_num_rows($result_page) > 0) {

while($row = mysqli_fetch_assoc($result_page)) {
?>
     
 <tr>
  <td><b><?php echo(ucwords($row["ethncity_name"]));?></b></td>
 <!-- <td><?php echo $row["ticket_type"];?></td> -->

  <?php if($status=='1'){?>
      <td><a href=UpdateEthnicity.php?id=<?php echo $row["id"]; ?> class='btn btn-info' role='button'> Edit</a></td>

 <td><a  onClick="return confirm('Are you sure you want to delete?')" href=EthnicityDelete.php?id=<?php echo $row["id"];?> class='btn btn-info' role='button'> Delete</a></td>

</tr>
 <?php 
      
  }

}
}
                ?>
                  
                      </tbody>
                </table>
              </div>
            </div>
           <!--  <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
          </div>

          <!-- <p class="small text-center text-muted my-5">
            <em>More table examples coming soon...</em>
          </p> -->

        </div>
        <!-- /.container-fluid -->
	<div class="col-md-12 text-center" style="display: flex;justify-content: center;"> <?php echo $pagination;?> </div>
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


  </body>

</html>
