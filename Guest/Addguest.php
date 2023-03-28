<?php
include('../Config/Connection.php');

session_start();
      $login_check=$_SESSION['id'];
       //var_dump($data1);
    if ($login_check!='1') {
       
        header("location: ../Login/login.php");
    }

$orderno=$_GET['id'];





 if(isset($_POST['customerdata'])){

 //print_r($_POST);exit();

$sqlGuest = "SELECT * FROM `guest` where order_id='$orderno'";
$resultGuest = mysqli_query($db, $sqlGuest);

if (mysqli_num_rows($resultGuest) > 0) {

  $sqlDeleteGuest="DELETE FROM `guest` WHERE `order_id`='$orderno'";

    $resultDeleteGuest = mysqli_query($db, $sqlDeleteGuest);
  }


$loopCount=count($_POST['guest']);

for ($i = 0; $i < $loopCount; $i++) {
    
$orderId=$_POST['ordernumber'];

$guestName=$_POST['guest'][$i];

$mobile=$_POST['mobile'][$i];


   $guest_insert = "INSERT INTO guest (order_id,guest_name,guest_mobile)
    VALUES ('$orderId','$guestName','$mobile')";
    $result = mysqli_query($db,$guest_insert);
  

} 

//echo "done";exit();
 header( "Location: Guestdetails.php" );


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
    <h3>Add Guest</h3>
     <hr>
     </div> 
   </div>

      <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">
     <div class="col-md-8">
   <form name="customerdata" action="" method="POST">
     <div class="optionBox">
         <div class="col-md-12">
  <div class="form-group">
  <!--  <label style="display: block;" for="fname">Order No.*</label>-->
      <input type="hidden" class="typeahead form-control" required name="ordernumber" id="order" value="<?=$orderno?>"  placeholder="Order No." >
        
  </div>
 </div>
<?php

$sqlGuest = "SELECT * FROM `guest` where order_id='$orderno'";
$resultGuest = mysqli_query($db, $sqlGuest);

if (mysqli_num_rows($resultGuest) > 0) {

while($Guests = mysqli_fetch_assoc($resultGuest)) {


    echo "<div class='block'><div class='row'><div class='col-md-6'>
       
  <div class='form-group'>
    <label style='display: block;' for='fname'>Guest Name*</label>
      <input type='text' class='typeahead form-control' required name='guest[]' value='".$Guests['guest_name']."'  placeholder='Guest Name' >
      
  </div>
 </div>
 
  <div class='col-md-6'>
  <div class='form-group'>
    <label style='display: block;' for='fname'>Phone No.*</label>
      <input type='text' class='typeahead form-control' required name='mobile[]' value='".$Guests['guest_mobile']."' id='mobile' placeholder='Phone No.' >
  </div>
 </div></div><span class='remove red'>Remove Guest</span></div>";

}
}

else
{

?>






    <div class="block">
  <div class="row">
      

      




      
   <div class="col-md-6">
       
  <div class="form-group">
    <label style="display: block;" for="fname">Guest Name*</label>
      <input type="text" class="typeahead form-control" required name="guest[]"  placeholder="Guest Name" >
        
  </div>
 </div>
 
  <div class="col-md-6">
  <div class="form-group">
    <label style="display: block;" for="fname">Phone No.*</label>
      <input type="text" class="typeahead form-control" required name="mobile[]" id="mobile" aria-describedby="customer" placeholder="Phone No." >
       
  </div>
 </div>

  

 </div>
    </div>



 <?php } ?>







  
    <div class="block">
        <span class="add add-btn">Add More Guest</span>
        <input type="submit" value="Submit" name="customerdata">
    </div>
    </form>
</div>
<script>
$('.add').click(function() {
    $('.block:last').before('<div class="block"><div class="row"><div class="col-md-6"><div class="form-group"><label style="display: block;" for="fname">Guest Name*</label><input type="text" class="typeahead form-control" required name="guest[]" id="" placeholder="Guest name *" > </div></div><div class="col-md-6"><div class="form-group"> <label style="display: block;" for="fname">Phone No.*</label><input type="text" class="typeahead form-control" required name="mobile[]" id="" placeholder="Phone No." >   <input type="hidden" name="idHidden" id="idhide"></div></div></div><span class="remove red">Remove Guest</span></div>');
});
$('.optionBox').on('click','.remove',function() {
  $(this).parent().remove();
});
</script>
</div>
   </div>
   
  </body>

</html>
