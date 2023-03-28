<?php
include('../Config/Connection.php');
 // Login check 
   session_start();
      $login_check=$_SESSION['id'];
      if ($login_check!='1')
      {
       header("location: ../Login/login.php");
      }
    //Login End
    //url id get
    $ticketno=$_GET['id'];
    //end
                 // insert History
    if(isset($_POST['customer']))
      {
            $history_date=$_POST['history_date'];
            $ticketno=$_GET['id'];
            $history_time=$_POST['history_time'];
            $park=$_POST['park']; 
            $ticket_data=$_POST['ticket_data']; 
          $loopCount=count($_POST['ticket_id']);
         for ($i = 0; $i < $loopCount; $i++) 
        {
         $ticketId=$ticketno;
         $ticketdetails=$_POST['ticket_id'][$i];
          $history_insert = "INSERT INTO history (ticket_id,history_date,history_time,park,barcode,ticket_full_details)
                             VALUES ('$ticketno','$history_date','$history_time','$park','$ticketdetails','$ticket_data')";
         $result = mysqli_query($db,$history_insert);
       }
          
     }//End Insert
     //Include Header
       include('../includes/header.php');
       //End Header
?> 


      <div id="content-wrapper">
	  
	   <div class="container-fluid">
	  <div class="col-md-12">
	<h3>Add History</h3>
	   <hr>
	   </div>	
	 </div>
	  <?php
    $sql = "SELECT * FROM ticket";
   $result = mysqli_query($db, $sql);
  
    
   ?>
   
      <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
	   <div class="col-md-7">
       <form action="AddHistory.php" autocomplete='off' method="post">
       
      <label for="fname">Barcode</label> 
      <select class="form-control" name="ticket_id[]"  multiple >
         <!-- <option value="0">Please Select</option> -->
         <?php
         while($row = mysqli_fetch_assoc($result)) {
          $tickeId=$row['ticketshowid'];
            $expire=$row['expire_date'];
             if($row['type']=="adult")
            {
           $type="AD";
            }
            else if($row['type']=="child")
            {
                $type="CH";
            }
            else if($row['type']=='comp')
            {
                $type="COM";
            }
            else 
            {
                $type="YOU";
            }
              $ticketToShow=$type.' '.$tickeId.' '.$expire;
              //var_dump($ticketToShow);
         
         
         ?>
        
      <option value="<?=$row['ticketshowid']?>"><?=$ticketToShow?></option>
      
     <?php
   }
      
     ?>   
      </select>
    
  <div class="col-md-12">
       <div class="form-group">
    <label  for="fname">Date *</label>
      <input type="date" class="typeahead form-control" required name="history_date"  placeholder="Date *" >
        </div>
 </div>
  
<div class="form-group">
    <label  for="fname">Time *</label>
      <input type="text" class="typeahead form-control" required name="history_time" id="" aria-describedby="customer" placeholder="Time *" >
       <input type="hidden"  name="ticket_data" value='<?=$ticketToShow?>'>
       </div>
  
   <label style="display: block;" for="fname">Park *</label>
    <select name="park" class="typeahead form-control">
      <option value="">Please select park</option>
      <option value="Universal Studios">Universal Studios</option>
      <option value="Islands Of Adventure">Islands Of Adventure</option>
      <option value="Volcano Bay Water">Volcano Bay Water</option>
     
    </select>
  
 <div class="form-group" style="text-align:center;"></br>
 <button type="submit"  name="customer"class="btn btn-primary">Submit</button>
 </div>
</form>
</div>
   </div>
   <!-- <div class="container-fluid">

          <!-- Breadcrumbs-->
          <!--<ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="index.html">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">404 Error</li>
          </ol>

          <!-- Page Content -->
          <!--<h1 class="display-1">404</h1>
          <p class="lead">Page not found. You can
            <a href="javascript:history.back()">go back</a>
            to the previous page, or
            <a href="index.html">return home</a>.</p>

        </div> -->
        <!-- /.container-fluid -->

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

</html>
