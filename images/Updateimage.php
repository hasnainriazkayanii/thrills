<?php
include('../Config/Connection.php');
session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
 $base_url=  "http://" . $_SERVER['SERVER_NAME']; 
$id=$_GET['id'];

  $target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $image=$base_url."/app/appadmin/uploads/".basename( $_FILES["fileToUpload"]["name"]);
    
       $image_insert= "UPDATE image SET 
                          image$id='$image'
                         WHERE id=6";
  
    $result = mysqli_query($db,$image_insert);
   header( "Location: imageupload.php" );
       

}
 include('../includes/header.php');
?>
      <div id="content-wrapper">
       <div class="container-fluid">
	  
	  <div class="col-md-12">
		<h3>Edit Images</h3>
	  <hr>
	   </div>	
	 
	 
	   <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
	 <form action="Updateimage.php?id=<?=$id?>" method="post" enctype="multipart/form-data">
    
    <input type="file"   required name="fileToUpload" id="fileToUpload" >
    <input type="submit" value="Upload Image" name="submit">
</form>

</div >
</div>
</div>
       
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Cheap Thrills Admin</span>
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