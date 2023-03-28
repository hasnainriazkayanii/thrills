<?php
  include('../Config/Connection.php');
   session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
  
  $base_url=  "http://" . $_SERVER['SERVER_NAME'];
  if(isset($_POST['customer']))
  {
   
        $title =mysqli_real_escape_string($db, $_REQUEST['title']);
        $urldata = $_REQUEST['urldata'];
        if ($_FILES["fileToUpload"]["name"] != NULL)
        {
           
        $filename = time().'.'.basename($_FILES['fileToUpload']['name']); 
         if ($_FILES["fileToUpload"]["size"] > 51100) {
     echo "<script>alert('Sorry, your file is too large');</script>";
        $uploadOk = 0;
        header( "Location: AddMenu.php?error=1" );
       EXIT;
      
       }
         $target_file = "../iocnuploads/".$filename;
       
         $var =  move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);
      
         $fileToUpload = "$base_url/app/OrlandoAdmin/iocnuploads/".$filename; 
        //$document[$i] =str_replace(",","_",$document[$i]);
        
        }
        else
        {
          $fileToUpload = '';
        }
       
        $created_on = time();
        $updated_on = time();
        
     $image_insert="INSERT INTO `menuoptions`(`icon`,`title`,`url`, `created_on`, `updated_on`)VALUES('$fileToUpload','$title','$urldata','$created_on','$updated_on')";
    
    $result = mysqli_query($db,$image_insert);
    
   header( "Location: MenuDetails.php" );
  
    
    }
 

  
    
    include('../includes/header.php');
?>


      <div id="content-wrapper">
	  <div class="container-fluid">
	  <div class="col-md-12">
		<h3>Add Menu Option's</h3>
	   <hr>
	   </div>	
	 </div>
       <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
       <form action="AddMenu.php" name='customer' enctype='multipart/form-data' autocomplete='off' method="post">
        <div class="form-group">
            
    <label for="fname">Icon * (max 100x100 )</label><br> 
     <input type="file"   name="fileToUpload"  width="100%" height='100%' id="fileToUpload">
     <!-- <strong><?php //echo $message ?></strong>-->
  </div>
   
   <div class="form-group">
    <label for="fname">Title *</label>
    <input type="text" class="form-control"  required name="title" id="title" aria-describedby="fname" placeholder="Title*">
  </div>
   <div class="form-group">
    <label for="fname">Url *</label>
    <input type="text" class="form-control" required name="urldata" id="urldata"  placeholder="Url *">
  </div>

  <div class="form-group" style="text-align:center;">
 <button type="submit"  name="customer" class="btn btn-primary">Submit</button>
 </div>
</form>
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
          <!-- <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div> -->
      </div>
    </div>
  </body>
<?php
  $mes=$_GET['error'];
    if($mes==1)
    {
         echo "<script>alert('Sorry, your file is too large');</script>";
    }
    else{
          header( "Location: MenuDetails.php" );
    }
?>
</html>
 
