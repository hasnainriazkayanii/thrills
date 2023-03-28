<?php
include('../Config/Connection.php');
 if(isset($_POST['customerdata'])){
  $ticket_type=$_POST['ticket_type'];
$sql="SELECT * FROM ticket where id='$ticket_type'";
    $result=mysqli_query($db,$sql);
    $user=mysqli_fetch_assoc($result);
    $ticket_code=$user['ticketshowid'];

    $customer_id=$_POST['idHidden'];
   // print_r($customer_id);die;
    $customer=$_POST['customer'];
    // $customer_id=explode("_",$customer);
  //print_r($_POST);exit;
    $date_of_vist=$_POST['date_of_vist'];
    $price=$_POST['cost'];
     $no_of_days=$_POST['noofdays'];
     $adults=$_POST['adults'];
    $kids=$_POST['kids'];
     $total=$_POST['total'];


        // $timestamp=time(); 
         $timestamp = time();
$datetimeFormat = 'Y-m-d';

$date = new \DateTime();
// If you must have use time zones
// $date = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
$date->setTimestamp($timestamp);
$create_date=$date->format($datetimeFormat);
        
       $bdt_user=$customer.'/'.$ticket_type.'/'.$no_of_days.'day'.'/'.$ticket_code.'/'.$create_date;  
        $ticket_order= $date_of_vist.'/' .$adults.'ad/'.$kids.'ch' .$no_of_days .' day'; 
       
$customer_update = "UPDATE ticket SET 
                          isengaged=1
                           WHERE id='$ticket_type'";
         mysqli_query($db,$customer_update);
$order_insert="INSERT INTO `order`(`order_id`,`ticket_type`, `customer_id`,`customer`, `date_of_visit`, `price`,`no_of_days`, `adults`, `kids`, `total`,`create_time`,`ticket_order`) VALUES ('$bdt_user','$ticket_type','$customer_id','$customer',' $date_of_vist','$price','$no_of_days','$adults','$kids','$total','$timestamp','$ticket_order')";
//var_dump($order_insert);
     //var_dump($order_insert);
    $result = mysqli_query($db,$order_insert);
    }


$sql = "SELECT * FROM customer";
$result = mysqli_query($db, $sql);
 if (mysqli_num_rows($result) > 0) {

while($row = mysqli_fetch_assoc($result)) {


$objet = new stdClass;

$objet->id=$row["id"];
$objet->label=$row["first_name"].' '.$row["Last_name"];

$Customer_name[]=$objet;

     /*$Customer_name[]=$row["first_name"].' '.$row["Last_name"].'_'.$row["id"];*/

}
$Customer_name= json_encode($Customer_name);
//print_r($Customer_name);exit;
}


include('../includes/header.php');

?>

<style>
  
  .my-form{width: 100%;
    height: 38px;
    border-radius: 5px;
    border: 1px solid #33333338;}

   .tt-query, / UPDATE: newer versions use tt-input instead of tt-query /
.tt-hint {
width: 100%;
height: 30px;
padding: 8px 12px;
font-size: 24px;
line-height: 30px;
border: 2px solid #ccc;
border-radius: 8px;
outline: none;
}

.tt-query { / UPDATE: newer versions use tt-input instead of tt-query /
box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
color: #999;
}

.tt-menu { / UPDATE: newer versions use tt-menu instead of tt-dropdown-menu /
width: 100%;
margin-top: 12px;
padding: 8px 0;
background-color: #fff;
border: 1px solid #ccc;
border: 1px solid rgba(0, 0, 0, 0.2);
border-radius: 8px;
box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
padding: 3px 20px;
font-size: 18px;
line-height: 24px;
}

.tt-suggestion.tt-is-under-cursor { / UPDATE: newer versions use .tt-suggestion.tt-cursor /
color: #fff;
background-color: #0097cf;

}

.tt-suggestion p {
margin: 0;
}
</style>
  <style>
.block {
        display: block;
    border: 1px solid #ccc;
    padding: 20px;
}
input {
    width: 50%;
    display: inline-block;
}
span {
    display: inline-block;
    cursor: pointer;
  
}
.add-btn{    background-color: #212529;
    padding: 5px 17px;
    color: #fff;
    text-decoration: none;}
.red{    background-color: red;
    padding: 5px 10px;
    color: #fff;
    margin-bottom: 10px;
    text-decoration: none;}
</style>
      <div id="content-wrapper">
    
     <div class="container-fluid">
    <div class="col-md-12">
    <h3>Add Order</h3>
     <hr>
     </div> 
   </div>
    <?php
    $sql = "SELECT * FROM ticket where isengaged=0";
   $result = mysqli_query($db, $sql);
  
    
   ?>
   <?php
    $parktype = "SELECT * FROM parktype";
   $parkresult = mysqli_query($db,  $parktype);
  
    
   ?>
      <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
     <div class="col-md-8">
	 
     <div class="optionBox">
    <div class="block">
  <div class="row">
   <div class="col-md-12">
	<div class="form-group">
		<label style="display: block;" for="fname">Guest Name*</label>
			<input type="text" class="typeahead form-control" required name="customer" id="costomer" aria-describedby="customer" placeholder="Guest Name" >
				<input type="hidden" name="idHidden" id="idhide">
	</div>
 </div>
 
  <div class="col-md-6">
	<div class="form-group">
		<label style="display: block;" for="fname">Phone No.*</label>
			<input type="text" class="typeahead form-control" required name="customer" id="costomer" aria-describedby="customer" placeholder="Phone No." >
				<input type="hidden" name="idHidden" id="idhide">
	</div>
 </div>
   
   <div class="col-md-6">
	<div class="form-group">
		<label style="display: block;" for="fname">Order No.*</label>
			<input type="text" class="typeahead form-control" required name="customer" id="costomer" aria-describedby="customer" placeholder="Order No." >
				<input type="hidden" name="idHidden" id="idhide">
	</div>
 </div>
 </div>
  	</div>
  
    <div class="block">
        <span class="add add-btn">Add Option</span>
    </div>
</div>
<script>
$('.add').click(function() {
    $('.block:last').before('<div class="block"><div class="row"><div class="col-md-12"><div class="form-group"><label style="display: block;" for="fname">Guest Name*</label><input type="text" class="typeahead form-control" required name="Guest Name" id="costomer" aria-describedby="customer" placeholder="Customer *" >	<input type="hidden" name="idHidden" id="idhide"></div></div><div class="col-md-6"><div class="form-group">	<label style="display: block;" for="fname">Phone No.*</label><input type="text" class="typeahead form-control" required name="customer" id="costomer" aria-describedby="customer" placeholder="Phone No." >		<input type="hidden" name="idHidden" id="idhide"></div></div><div class="col-md-6"><div class="form-group"><label style="display: block;" for="fname">Order No.*</label><input type="text" class="typeahead form-control" required name="customer" id="costomer" aria-describedby="customer" placeholder="Order No." >		<input type="hidden" name="idHidden" id="idhide"></div></div></div><span class="remove red">Remove Guest</span></div>');
});
$('.optionBox').on('click','.remove',function() {
 	$(this).parent().remove();
});
</script>
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


    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    


      <script src="../js/sb-admin.min.js"></script>
  </script>




<script>

var substringMatcher = function(strs) {
return function findMatches(q, cb) {
var matches, substringRegex;

matches = [];

substrRegex = new RegExp(q, 'i');
$.each(strs, function(i, str) {
if (substrRegex.test(str)) {
matches.push(str);
}
});

cb(matches);
};
};



$('.typeahead').typeahead({


source: function(query, process) {
 //alert("me");

objects = [];
map = {};
var data = <?=$Customer_name?> // Or get your JSON dynamically and load it into this variable
$.each(data, function(i, object) {
map[object.label] = object;
objects.push(object.label);
});
process(objects);
},
updater: function(item) {
$('hiddenInputElement').val(map[item].id);
$('#idhide').val(map[item].id);
console.log(map[item].id);
return item;
}
});


$('#changeTicket').empty();


  function allavticket(did) {

//alert(id);

     $.ajax({
            type: 'GET',
            url: '../Ajax/GetAllavailableTickets.php?GetDate='+did,
             async:true,
            success: function (data) {


              console.log(data);

               $('#changeTicket').empty();
        $('#changeTicket').append(data);

/*              if(data=="No")
              {
alert('Please select one ticket type ');

$('#cost').val("");
$('#numberofdays').val("");
              }
              else
              {


let dataAll = JSON.parse(data);
//console.log(dataAll.id);

$('#cost').val(dataAll.cost);
$('#numberofdays').val(dataAll.numberofdays);
             
}*/
}
})
}










  function ticket(id) {
     $.ajax({
            type: 'GET',
            url: '../Ajax/Getorder.php?id='+id,
             async:true,
            success: function (data) {

              if(data=="No")
              {
alert('Please select one ticket type ');

$('#cost').val("");
$('#numberofdays').val("");
              }
              else
              {


let dataAll = JSON.parse(data);
//console.log(dataAll.id);

$('#cost').val(dataAll.cost);
$('#numberofdays').val(dataAll.numberofdays);
             
}
}
})
}
</script>
<script>
  function total_value()
  {
    var num1 = document.getElementById('adults').value;
    //console.log(num1);
    var num2 = document.getElementById('random').value;

    if(num1 && num2)
    {
    //console.log(num2);
    var data2 = parseInt(num1)+parseInt(num2);
  var price = $("#cost").val();

  var totalPrice= data2*price;
    $("#total").val(totalPrice);
    //console.log(data2);
  }
  else if(num2)
  {
var data2 = parseInt(num2);
 var price = $("#cost").val();

  var totalPrice= data2*price;
    $("#total").val(totalPrice);
  }
  else
  {
 var data2 = parseInt(num1);
 var price = $("#cost").val();

  var totalPrice= data2*price;
    $("#total").val(totalPrice);
  }

  }
    function AllowNumbersOnly(e) {
    var code = (e.which) ? e.which : e.keyCode;
    if (code > 31 && (code < 48 || code > 57)) {
      e.preventDefault();
    }
  }
  
</script>>

  </body>

</html>
