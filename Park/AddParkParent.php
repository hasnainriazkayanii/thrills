<?php
  include('../Config/Connection.php');
   session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }
    
    
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];
               $sql = "Select * From theme_park_parents WHERE id = '$id'";
         $result=mysqli_query($db,$sql);
         $park=mysqli_fetch_assoc($result);
 
 
        if(isset($_POST['edit'])){
            $name=$_POST['name'];
            $active=$_POST['active'];
            $code=$_POST['code'];
            $park_img = $park['park_logo'];
            // var_dump($park_img);die;
            if($_FILES['editparkimg']['size'] !== 0){        

                $park_img = $_FILES['editparkimg']['name'];
                $ticket_attachment_DBpath = ",image = 'images/ticket_attachments/$park_img'";
            
                if(move_uploaded_file($_FILES['editparkimg']['tmp_name'], "../images/parks/".$park_img)){
                    unlink('../images/parks/'.$park['park_logo']);
                }
                
            }
            
               $record_update = "UPDATE theme_park_parents SET 
               name='$name',
               is_universal='$active',
               code='$code',
               park_logo='$park_img'
               WHERE id='$id'";
        
           if(mysqli_query($db,$record_update)){

               $_SESSION['park_msg'] = "Park Updated successfully.";
               header( "Location: ParkParentDetails.php" );
               
           }else{
               $_SESSION['park_msg'] = "Error".mysqli_error($db);
            header( "Location: ParkParentDetails.php" );

           }


        }
 
 
    }


 if(isset($_POST['customer'])){
    $park_name=$_POST['name'];
    $park_code=$_POST['pcode'];
    $active=$_POST['pactive'];
    
    if($_FILES['parkimg']['size'] == 0){
        $_SESSION['park_msg'] = "Please upload Image";
            header( "Location: ParkParentDetails.php" );
    }
    else{
        // var_dump($_FILES['parkimg']['name']);
    
        $park_img = $_FILES['parkimg']['name'];
        $ticket_attachment_DBpath = ",image = 'images/ticket_attachments/$park_img'";
    
        move_uploaded_file($_FILES['parkimg']['tmp_name'], "../images/parks/".$park_img);
    }

    // die('out');
   $create_date=time();
   $update_date=time();
   $park_insert = "INSERT INTO theme_park_parents (name,code,park_logo,is_universal)
    VALUES ('$park_name','$park_code','$park_img','$active')";
   if( $result = mysqli_query($db,$park_insert));
           $_SESSION['park_msg'] = "Park Added Successfully";
    header( "Location: ParkParentDetails.php" );
    }
    
    include('../includes/header.php');
?>


      <div id="content-wrapper">
	  
	   <div class="container-fluid">
	  <div class="col-md-12">
		<h3>Add Parent Park</h3>
	   <hr>
	   </div>	
	 </div>
	 
       <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
	       <?php if(isset($id) && !is_null($id)){?>
	              <form action="AddParkParent.php?id=<?=$id?>" autocomplete='off' method="post" enctype="multipart/form-data">
        <div class="form-group">
    <label for="name">Park Name *</label>
    <input type="text" class="form-control" required name="name" id="name" aria-describedby="fname" value='<?=$park['name']?>' placeholder="Park Name *">
    
      <label for="name">Theme Park Code*</label>
        <input type="text" class="form-control" required name="code" id="code" aria-describedby="code" value='<?=$park['code']?>' placeholder="Park Code *">
        
            <label for="name">Active*</label>
              <select name="active" id="active" class="form-control" required>
                <option <?=$park['is_universal']==1?'Selected':''?> value="1">Active</option>
                <option  <?=$park['is_universal']==0?'Selected':''?> value="0">Inactive</option>
              </select>
        
                
                
  <div class="form-group">
      <label for="plogo">Park Logo*</label>
      <?php if(isset($park['park_logo']) ) {
$img = $park['park_logo'];
 echo "<img width='140' height='70' src='../images/parks/$img'/>" ;}?>
    
    <input id="plogo" type="file" class="form-control" name="editparkimg" aria-describedby="ParkLogo">
  </div>
  
  </div>
   <div class="form-group" style="text-align:center;">
 <button type="submit"  name="edit"class="btn btn-primary">Submit</button>
 
 
 
 
 </div>
</form>

	       <?php } else{?>
	       
       <form action="AddParkParent.php" autocomplete='off' method="post" enctype="multipart/form-data">
        <div class="form-group">
    <label for="pname">Park Name *</label>
    <input type="text" class="form-control" required name="name" id="name" aria-describedby="fname" placeholder="Park Name *">
  </div>
  
  <div class="form-group">
    <label for="pcode">Park Code *</label>
    <input type="text" class="form-control" required name="pcode" id="code" aria-describedby="pcode" placeholder="Park Code*">
  </div>
  
  <div class="form-group">
    <label for="pactive">Active*</label>
    <select name="pactive" id="pactive" class="form-control" required>
                <option  value="1">Active</option>
                <option   value="0">Inactive</option>
              </select>
  </div>
  
  
  <div class="form-group">
    <label for="pactive">Park Logo*</label>
    <input type="file" class="form-control" required name="parkimg" aria-describedby="ParkLogo">
  </div>
  
   <div class="form-group" style="text-align:center;">
 <button type="submit"  name="customer"class="btn btn-primary">Submit</button>
 
 </div>
</form>
	       
	       <?php }?>
</div>
</div>


        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span></span>
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

</html>
