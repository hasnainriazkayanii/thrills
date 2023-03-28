<?php
 include('../Config/Connection.php');
session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
    
  $base_url=  "http://" . $_SERVER['SERVER_NAME']; 
 

 include('../includes/header.php');
?>
      <div id="content-wrapper">
       <div class="container-fluid">
	  
	  <div class="col-md-12">
		<h3>Add Images</h3>
	  <hr>
	   </div>	
	 
	 
	   <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
	 <form action="imageupload.php" method="post" enctype="multipart/form-data">
    
  
</form>
<?php 
 $sql="SELECT * FROM image";
    $result=mysqli_query($db,$sql);
    $user=mysqli_fetch_assoc($result);
   // print_r($user);
?>
<div class='row'>
   
<div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Image</th>
                      <th>Edit</th>
                      <th>enable</th>
                    </tr>
                  </thead>
                  <tr>
                      <?php
                        if($user['image1'])
             { 
             
             $imageAsarray=explode("/",$user['image1']);
             $image=end($imageAsarray);
            // print_r(end($imageAsarray));exit;
             
             
             
             
             ?>
                      <td><img src="../uploads/<?=$image?>" height='180px' width='220px'></td>
                      <?php } else{?> 
                         <td><img src="../uploads/noimage.png" height='180px' width='220px'></td>
                       <?php }?>
                      <td><a href=Updateimage.php?id=1 class='btn btn-info' role='button'> Edit</a></td>
                      <td><a onclick="return confirm('Are you sure you want to delete?')" href=ImageDelete.php?id=1 class='btn btn-info' role='button'> Delete</a></td>
                  </tr>
                  <tr>
                      <?php
                        if($user['image2'])
             {
             
              $imageAsarray2=explode("/",$user['image2']);
             $image2=end($imageAsarray2);
             
             
             ?>
                       <td><img src="../uploads/<?=$image2?>" height='180px' width='220px'></td>
                      <?php } else{?> 
                         <td><img src="../uploads/noimage.png" height='180px' width='220px'></td>
                      
                       <?php }?>
                      <td><a href=Updateimage.php?id=2 class='btn btn-info' role='button'> Edit</a></td>
                      <td><a onclick="return confirm('Are you sure you want to delete?')" href=ImageDelete.php?id=2 class='btn btn-info' role='button'> Delete</a></td>
                  </tr>
                  <tr>
                       <?php
                        if($user['image3'])
             {
             
             
              $imageAsarray3=explode("/",$user['image3']);
             $image3=end($imageAsarray3);
             
             
             ?>
                         <td><img src="../uploads/<?=$image3?>" height='180px' width='220px'></td>
                      <?php } else{?> 
                         <td><img src="../uploads/noimage.png" height='180px' width='220px'></td>
                      
                       <?php }?>
                      <td><a href=Updateimage.php?id=3 class='btn btn-info' role='button'> Edit</a></td>
                      <td><a onclick="return confirm('Are you sure you want to delete?')" href=ImageDelete.php?id=3 class='btn btn-info' role='button'> Delete</a></td>
                  </tr>
                  <tr>
                      <?php
                       if($user['image4'])
                       {
              $imageAsarray4=explode("/",$user['image4']);
             $image4=end($imageAsarray4);
             
             
             ?>
                         <td><img src="../uploads/<?=$image4?>" height='180px' width='220px'></td>
                      <?php } else{?> 
                         <td><img src="../uploads/noimage.png" height='180px' width='220px'></td>
                      
                       <?php }?>
                      <td><a href=Updateimage.php?id=4 class='btn btn-info' role='button'> Edit</a></td>
                      <td><a onclick="return confirm('Are you sure you want to delete?')" href=ImageDelete.php?id=4 class='btn btn-info' role='button'> Delete</a></td>
                  </tr>
                
                  
                  <tr>
                    <?php
                       if($user['image5'])
             { $imageAsarray5=explode("/",$user['image5']);
             $image5=end($imageAsarray5);
             
             
             ?>
                         <td><img src="../uploads/<?=$image5?>" height='180px' width='220px'></td>
                      <?php } else{?> 
                         <td><img src="../uploads/noimage.png" height='180px' width='220px'></td>
                      
                     
                       <?php }?>
                      <td><a href=Updateimage.php?id=5 class='btn btn-info' role='button'> Edit</a></td>
                      <td><a onclick="return confirm('Are you sure you want to delete?')" href=ImageDelete.php?id=5 class="btn btn-info" role='button'> Delete</a></td>
           
                  </tr>
            
                  <tbody>
                    
     



               
                  
                      </tbody>
                </table> 
                 </div>
            </div>

</div>
</div >
</div>
</div>
        
        <!-- /.container-fluid -->


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
