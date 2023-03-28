<?php

 include('../Config/Connection.php');



 session_start();

      $login_check=$_SESSION['id'];

       //var_dump($data1);

    if ($login_check!='1') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       $_SESSION['intended_url'] = $full_url;
        header("location: ../Login/login.php");
    }

//echo ($_SESSION['intended_url']);
 

 

//$sql = "SELECT * FROM `order` ORDER BY id DESC";

//$result = mysqli_query($db, $sql);  

$tbl_name="`order` WHERE status=3 ORDER BY date_of_visit DESC";

/*$tbl_name="`order` ORDER BY date_of_visit DESC";*/

if(isset($_POST['activedata']))

{

 $check_order=$_POST['activedata'];

    if($check_order=='1'){

    $current_date=date("Y-m-d");

     $tbl_name="`order` WHERE status=3 ORDER BY date_of_visit desc";


    //$tbl_name="`order` WHERE date_of_visit >= '$current_date' ORDER BY date_of_visit DESC";

     

     $checkActive=1;

    

    } 

    else{

      $current_date=date("Y-m-d");

      $tbl_name="`order` WHERE status=3 ORDER BY date_of_visit desc";

         //$tbl_name="`order` ORDER BY date_of_visit desc";

    

    $checkActive=0;

   

    }

}

elseif(isset($_GET['active']))

{
if(isset($_GET['active']))

{

 $check_order=$_GET['active'];

    if($check_order=='1'){

    $current_date=date("Y-m-d");

     $tbl_name="`order` WHERE status=3 ORDER BY date_of_visit desc";
     
    //$tbl_name="`order` WHERE date_of_visit >= '$current_date' ORDER BY date_of_visit DESC";

     

     $checkActive=1;

    

    } 

    else{

      $current_date=date("Y-m-d");
      
       $tbl_name="`order` WHERE status=3 ORDER BY date_of_visit ASC,STR_TO_DATE(time, '%l:%i %p')" ;
       //$tbl_name.="order by time DESC";
        // $tbl_name="`order` ORDER BY date_of_visit desc";

    

    $checkActive=0;

   

    }

}

}

/*   $checkActive=0;

if(isset($_GET['active']))

{

  $checkActive=$_GET['active'];

}*/





//print_r($checkActive); 

if (isset($_GET['active']) && $_GET['active'] == 3)
{
  $tbl_name="`order` WHERE status=3 ORDER BY date_of_visit desc";
  $checkActive=3;
}



$targetpage = "../Orders/archived.php";



include('../includes/pagination.php');



include('../includes/header.php');

?>







      <div id="content-wrapper">



        <div class="container-fluid">

          <?php if(isset($_SESSION['error_msg'])): ?>
          <div id=messagediv class="alert alert-danger">
            <strong><?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']) ?></strong>  
          </div>
          <?php endif; ?>

          <?php if(isset($_SESSION['success_msg'])): ?>
          <div id=messagediv class="alert alert-success">
            <strong><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']) ?></strong>  
          </div>
          <?php endif; ?>

             <?php


       if(($_GET['sucess'])=="0")

       {?>

       <div id=messagediv class="alert alert-success">

    <strong>Added succesfully.</strong>  

    </div>

     <?php }; ?>

   <?php

       

       if(($_GET['sucess'])=="1")

       {?>

       <div id=messagediv class="alert alert-success">

    <strong>Updated succesfully.</strong>  

    </div>

     <?php }; ?>


          <!-- Breadcrumbs-->

          <!--<ol class="breadcrumb">

            <li class="breadcrumb-item">

              <a href="#">Dashboard</a>

            </li>

            <li class="breadcrumb-item active">Tables</li>

          </ol>-->



          <!-- DataTables Example -->

      <div class="row">

      <div class="col-md-12">

      <div class="col-md-8" style="float:left;"><h3>Order Details (Archived)</h3></div>

      <!--<div class="col-md-4 text-right" style="float:left;" ><a href="../Orders/Addorders.php" class="btn btn-primary">Add Order</a></div>-->

      </div>

      </div>

      <hr>

      <br>

          <!-- DataTables Example -->

          <div class="card mb-3">

            <div class="card-header">

              <i class="fas fa-table"></i>

              Order Details</div>

        <form action="archived.php?active=<?=$_GET['active']?>"  method="post">

        <div class="input-group">

           <div class="input-group-append mar-10">

          <input type="text" class="form-control" name='search_name' placeholder="Search for" aria-label="Search" required >

          

            <input style="padding: 8px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" type="submit" class="btn btn-primary" name="submit"  value="Search">

             <a style="padding: 7px 6px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" href="archived.php?active=3" class="btn btn-primary" name="show_all">Show All</a> 

               

             </div>

             

    

          

        </div>

      </form>


    

             <?php

         

          if(isset($_POST['submit'])=='true'

        ){

       $name=$_POST['search_name'];

   

   if(isset($_GET['active']))

    {



if($_GET['active']==1)

      {

      $current_date=date("Y/m/d");

    $sql12 ="SELECT * FROM `order` WHERE customer LIKE '%".$name."%' AND status=3 ORDER BY date_of_visit DESC";



    }

    else

    {

    

     $current_date=date("Y/m/d");

   $sql12 ="SELECT * FROM `order` WHERE customer LIKE '%".$name."%' AND status=3 ORDER BY date_of_visit DESC";

   /* $sql12 ="SELECT * FROM `order` WHERE customer LIKE '%".$name."%' ORDER BY date_of_visit DESC";*/

   /* var_dump($sql12);die;*/

    }

    }

    else

    {

      $current_date=date("Y/m/d");

    $sql12 ="SELECT * FROM `order` WHERE customer LIKE '%".$name."%' AND status=3 ORDER BY date_of_visit DESC";

    }


        $q12=mysqli_query($db,$sql12);

       ?>

        

 <div class="card-body">

              <div class="table-responsive">

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                   <thead>

                       <?php $status=$_SESSION['status']; ?>

                    <tr id="th-order">

                      <th class="th-20">Date</th>

                      <th class="th-21">Order Info</th>

                      <th class="th-22">Order ID</th>

                      <th class="th-23">Send Confirmation Text</th>
                       

                   <!--   <th>Order</th>-->

                       <th class="th-23">Assign Tickets</th>

                         <?php if($status=='1')

                      {?>

                        <th class="th-24">Mark Status</th>

                        <th class="th-25">Edit</th>
                       

                          <?php };?>

                     

                    </tr>

                  </thead>

                  

                  <tbody>





                <?php   

                if (mysqli_num_rows($q12) > 0) {

                while($row12 = mysqli_fetch_assoc($q12)) {

                  // Check if screenshot has been taken
                  $sql = "SELECT * FROM `screenshot` WHERE login_id='{$row12['order_id']}'";
                  $result = mysqli_query($db, $sql);
                  $screenshot = mysqli_fetch_assoc($result);
                  if ($screenshot)
                    $screenshot_taken = '<p>Took Screenshot</p>';
                  else
                    $screenshot_taken = "";
                    

             $customer_id1 =$row12["customer_id"];

          $sql34="SELECT * FROM `customer` where id='$customer_id1'";

    // var_dump($sql33);

     $result34=mysqli_query($db,$sql34);

     $user34=mysqli_fetch_assoc($result34);

      $homecity1=$user34['homecity'];

     $ethnicity1 = $user34['ethnicity'];

   /*  $homecity1=$user34['ethnicity'];*/

     $Phone_number1 =$user34['Phone_number'];

                    

     $originalDate1=$row12["create_time"];

    $newDate1 = date('h:i A', $row12["create_time"]);

      $ticket_order= $row12['adults'].'ad/'.$row12['kids'].'ch'; 

        $ticket_type11=$row12["ticket_type"];

      $ticket_type33= (explode(" ",$ticket_type11));

        $ticket_type2=array_pop( $ticket_type33);

      $ticket_type34= implode(" ",$ticket_type33);

    if($row12['assign']==1)

    {

        $showAssign="Assigned";

    }

    else

    {

         $showAssign="Assign Ticket";

    }

    if($row12['date_of_visit']=="1970-01-01")

    

    { 

        

         $newdata='N/A';

       

       

    }else{

       

       $newdata=date("F d",strtotime($row12['date_of_visit']));

    } 

    $current_date = date("Y-m-d");
    //if (strtotime($row['date_of_visit']) >= strtotime($current_date))
      $text_confirmation_button = "<a href=\"http://cheapthrillstix.com/app/appadmin/Orders/text_order_details.php?order_id={$row12['order_id']}\" class=\"btn btn-success\" role\"button\">Send Confirmation Text</a>";
    //else
      //$text_confirmation_button = "";


      // For Status update button
      if($row12['status']==0){
        $update_status = "<a href=\"../Orders/update_status.php?mark=1&order_id={$row12['order_id']}\" class=\"btn btn-success\" role\"button\">Arrived</a>";
      }elseif($row12['status']==1){
        $update_status = "<a href=\"../Orders/update_status.php?mark=2&order_id={$row12['order_id']}\" class=\"btn btn-warning\" role\"button\">At The Park</a>";
      }elseif($row12['status']==2){
        $update_status = "<a href=\"../Orders/update_status.php?mark=3&order_id={$row12['order_id']}\" class=\"btn btn-danger\" role\"button\">Closed</a>";
      }else{
        $update_status = "<span class='btn btn-secondary'>Archived</span>";
      }







echo "<tr>

<td>".$newdata."</br>".$row12['time']."</td>





<td class='td-1'><p class='name-1'>".ucwords($row12["customer"])."</p>"."<p class='name-2'>". $homecity1. "  ($ethnicity1)"."</p>"."<p class='name-3'>"."$Phone_number1"."</p>"."<p class='name-3'>"  .$ticket_order. $ticket_type34."</p>"."<p class='name-4'>".'$'.$row12["total"]."</p>".$screenshot_taken."</td>



<td>".$row12["order_id"]."</td>

<td>$text_confirmation_button</td>

<td><a href='../Assign/Addassign.php?id=".$row12['id']."' class='btn btn-info' role='button'> ".$showAssign."</a></td>";



  if($status=='1'){

    

echo "

<td>$update_status</td>

<td><a href='Updateorders.php?id=".$row12['id']."' class='btn btn-info' role='button'> EDIT</a></td>





</tr>";

}



}

}

}

                

                else{     ?>



                      







             </tbody>

             </table>

    

            <div class="card-body">

              <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                  <thead>

                       <?php $status=$_SESSION['status']; ?>

                    <tr id="th-order">

                      <th class="th-20">Date</th>

                      <th class="th-21">Order Info</th>

                      <th class="th-22">Order ID</th>
                      <th class="th-23">Send Confirmation Text</th>
                      <th class="th-24">Update Usage</th>

                      <th class="th-25">Text Usage</th>
                  

                   <!--   <th>Order</th>-->

                       <th class="th-26">Assign Tickets</th>

                         <?php if($status=='1')

                      {?>


                        <th class="th-27">Mark Status</th>

                        <th class="th-28">Edit</th>

                          <?php };?>

                  

                    </tr>

                  </thead>
                  

                  <tbody>

                      

                    <?php




       if (mysqli_num_rows($result_page) > 0) {



while($row = mysqli_fetch_assoc($result_page)) {

  

     $customer_id =$row["customer_id"];

    // var_dump($customer_id);

      //$arr= implode(",",array_unique($customer_id));

    // Check if screenshot has been taken
    $sql = "SELECT * FROM `screenshot` WHERE login_id='{$row['order_id']}'";
    $result = mysqli_query($db, $sql);
    $screenshot = mysqli_fetch_assoc($result);
    if ($screenshot)
      $screenshot_taken = '<p>Took Screenshot</p>';
    else
      $screenshot_taken = "";
     $sql33="SELECT * FROM `customer` where id='$customer_id'";
    // var_dump($sql33);
     $result33=mysqli_query($db,$sql33);

     $user33=mysqli_fetch_assoc($result33);

      $homecity=$user33['homecity'];

     $ethnicity = $user33['ethnicity'];

     $Phone_number =$user33['Phone_number'];

    

    /*$originalDate=$row["create_time"];

    $newDate = date('h:i A', $row["create_time"]);*/

      $ticket_order= $row['adults'].'ad/'.$row['kids'].'ch'; 

       $ticket_type1=$row["ticket_type"];

      $ticket_type3= (explode(" ",$ticket_type1));

        $ticket_type2=array_pop( $ticket_type3);

      $ticket_type= implode(" ",$ticket_type3);

     

    /*  print_r(date(" F d",strtotime($row["date_of_visit"])));

    print_r($newDate);*/

    //$newDate = date("h:i A", strtotime($originalDate));

    if($row['assign']==1)

    {

        $showAssign="Assigned";

    }

    else

    {

         $showAssign="Assign Ticket";

    }

    if($row['date_of_visit']=="1970-01-01")

    

    { 

        

         $newdata1='N/A';

       

       

    }else{

       

       $newdata1=date("F d",strtotime($row['date_of_visit']));

    } 

    if ($showAssign=="Assigned")
    {
      $text_history_button = "<a href=\"text_history.php?id=".$row['id']."\" class='btn btn-info' role='button'>Text Usage</a>";
      //$update_history_button = "<a href='order_history.php?id=".$row["id"]."' class='btn btn-warning'>Update Usage</a>";
      $update_history_button = "<button data-id=\"{$row['id']}\" type=\"button\" class=\"btn btn-warning update-usage-btn\" data-toggle=\"modal\" data-target=\"#update-usage-modal\">Update Usage</button>";
    }
    else
    {
      $text_history_button = "";
      $update_history_button = "";
    }

    $current_date = date("Y-m-d");
    //if (strtotime($row['date_of_visit']) >= strtotime($current_date))
      $text_confirmation_button = "<a href=\"http://cheapthrillstix.com/app/appadmin/Orders/text_order_details.php?order_id={$row['order_id']}\" class=\"btn btn-success\" role\"button\">Send Confirmation Text</a>";
    //else
      //$text_confirmation_button = "";




      

      // For Status update button
      if($row['status']==0){
        $update_status = "<a href=\"../Orders/update_status.php?mark=1&order_id={$row['order_id']}\" class=\"btn btn-success\" role\"button\">Arrived</a>";
      }elseif($row['status']==1){
        $update_status = "<a href=\"../Orders/update_status.php?mark=2&order_id={$row['order_id']}\" class=\"btn btn-warning\" role\"button\">At The Park</a>";
      }elseif($row['status']==2){
        $update_status = "<a href=\"../Orders/update_status.php?mark=3&order_id={$row['order_id']}\" class=\"btn btn-danger\" role\"button\">Closed</a>";
      }else{
        $update_status = "<span class='btn btn-secondary'>Archived</span>";
      }


  
echo "<tr>
<td>$newdata1</br>".$row['time']."</td>
<td class='td-1'><p class='name-1'>".ucwords($row["customer"])."</p>"."<p class='name-2'>". $homecity. "($ethnicity)"."</p>"."<p class='name-3'>"."$Phone_number"."</p>"."<p class='name-3'>"  .$ticket_order.$ticket_type."</p>"."<p class='name-4'>".'$'.$row["total"]."</p>".$screenshot_taken."</td>
<td>".$row["order_id"]."</td>
<td>$text_confirmation_button</td>
<td>
      ".$update_history_button."
</td>


<td>".$text_history_button."</td>

<td><a href='../Assign/Addassign.php?id=".$row['id']."' class='btn btn-info' role='button'> ".$showAssign."</a>
</td>



<td>$update_status</td>


<td><a href='Updateorders.php?id=".$row['id']."' class='btn btn-info' role='button'> EDIT</a></td>



</tr>";





}

}



           ?>

                  <!--href=UpdateParktype.php?id=".$row["id"]."-->

                      </tbody>

                </table>

                  </div>

              </div>

            </div>

           <!--  <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->

          </div>





        <!-- /.container-fluid -->

    <div class="col-md-12 text-center" style="display:flex;justify-content:center;"><?php echo $pagination;?></div>

    

      

        <!-- Sticky Footer -->

        <footer class="sticky-footer">

          <div class="container my-auto">

            <div class="copyright text-center my-auto">

              <!-- <span>Copyright © Universal Orlando Resort 2018</span> -->

            </div>

          </div>

        </footer>



      </div>

     <?php }?>

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



    <!-- Bootstrap core JavaScript-->

   



  </body>



</html>

<?php

$checkActive=$_GET['active'];



 //if($checkActive==0)

//{$checkActive=0;}else{$checkActive=1;}?>

<script>

    $('#orderid').change( function(){

      sValue=$("#orderid").val();

     // alert(sValue);



    $('#ssform').submit();

    Curl=window.location.origin;

    //alert(Curl);

//window.location.href=Curl+"/app/OrlandoAdmin/Orders/archived.php?active="+sValue;
window.location.href=Curl+"/app/appadmin/Orders/archived.php?active="+sValue;


});

$('#orderid').val(<?=$checkActive?>);

  

   $('#dataTable').dataTable({

"order": [[8 , 'desc' ]]

});

function Hidemessage() {

 document.getElementById("messagediv").style.display = "none";

}

setInterval(Hidemessage, 5000);

</script>


 <!-- The Update Usage Modal -->
  <div class="modal fade" id="update-usage-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Select Transfer Method</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <form id="update-usage-form" action="order_history.php" method="get">
          <!-- Modal body -->
          <div class="modal-body">
            <select id="transfer-method-select" name="transfer_method" class="form-control">
              <option value="">Select Transfer Method</option>
              <option value="main">Main</option>
              <option value="train">Train</option>
            </select>
          </div>
          
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Submit</button>
          </div>
        </form>
        
      </div>
    </div>
  </div>


  <script type="text/javascript">
    (function () {
      const btn = document.getElementsByClassName('update-usage-btn');
      const updateUsageForm = document.getElementById('update-usage-form');

      for (let i = 0; i < btn.length; ++i)
      {
        btn[i].addEventListener('click', function () {
          let id = JSON.parse(this.dataset.id);
          updateUsageForm.innerHTML = '<input type="hidden" name="id" value="'+id+'"' + updateUsageForm.innerHTML;
        });
      }
    })();
  </script>