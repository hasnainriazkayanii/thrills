<?php
include('../Config/Connection.php');

$ticketno=$_GET['id'];





 if(isset($_POST['customerdata'])){

 //print_r($_POST);exit();

$sqlHis = "SELECT * FROM `history` where ticket_id='$ticketno'";
$resultHis = mysqli_query($db, $sqlHis);

if (mysqli_num_rows($resultHis) > 0) {

  $sqlDeleteGuest="DELETE FROM `history` WHERE `ticket_id`='$ticketno'";

    $resultDeleteGuest = mysqli_query($db, $sqlDeleteGuest);
  }


$loopCount=count($_POST['history_date']);

for ($i = 0; $i < $loopCount; $i++) {
    
$ticketId=$ticketno;

$history_date=$_POST['history_date'][$i];

$history_time=$_POST['history_time'][$i];

$park=$_POST['park'][$i];


   $history_insert = "INSERT INTO history (ticket_id,history_date,history_time,park)
    VALUES ('$ticketId','$history_date','$history_time','$park')";
    $result = mysqli_query($db,$history_insert);
  

} 

//echo "done";exit();
 header( "Location: History.php" );


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
    <h3>Add History</h3>
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
      <input type="hidden" class="typeahead form-control" required name="ticketnumber" id="order" value="<?=$orderno?>"  placeholder="Order No." >
        
  </div>
 </div>
<?php

$sqlHis = "SELECT * FROM `history` where ticket_id='$ticketno'";
$resultHis = mysqli_query($db, $sqlHis);

if (mysqli_num_rows($resultHis) > 0) {
$a=0;
while($His = mysqli_fetch_assoc($resultHis)) {

$a++;
    echo "
    <div class='block'>
  <div class='row'>
  <div class='col-md-12'>
       <div class='form-group'>
    <label style='display: block;' for='fname'>Date *</label>
      <input type='date' class='typeahead form-control' value='".$His['history_date']."' required name='history_date[]'  placeholder='Date *' >
        </div>
 </div>
 <div class='col-md-6'>
  <div class='form-group'>
    <label style='display: block;' for='time'>Time *</label>
      <input type='text' class='typeahead form-control' value='".$His['history_time']."' required name='history_time[]' id=''  placeholder='Time *' >
       </div>
 </div>
 <div class='col-md-6'>
  <div class='form-group'>
    <label style='display: block;' for='park'>Park *</label>
    <select name='park[]' id='selectPark".$a."' class='typeahead form-control'>
      <option value=''>Please select park</option>
      <option value='Universal Studios'>Universal Studios</option>
      <option value='Islands Of Adventure'>Islands Of Adventure</option>
      <option value='Volcano Bay Water'>Volcano Bay Water</option>
     
    </select>
  
       </div>
 </div>
</div>
    </div>
<script>$('#selectPark".$a."').val('".$His['park']."');</script>";
}
}

else
{

?>


<!-- <script>$('#selectPark".$a." option[value='".$His['history_time']."']').attr('selected',true);</script> -->



    <div class="block">
  <div class="row">
  <div class="col-md-12">
       <div class="form-group">
    <label style="display: block;" for="fname">Date *</label>
      <input type="date" class="typeahead form-control" required name="history_date[]"  placeholder="Date *" >
        </div>
 </div>
 <div class="col-md-6">
  <div class="form-group">
    <label style="display: block;" for="fname">Time *</label>
      <input type="text" class="typeahead form-control" required name="history_time[]" id="" aria-describedby="customer" placeholder="Time *" >
       </div>
 </div>
 <div class="col-md-6">
  <div class="form-group">
    <label style="display: block;" for="fname">Park *</label>
    <select name="park[]" class="typeahead form-control">
      <option value="">Please select park</option>
      <option value="Universal Studios">Universal Studios</option>
      <option value="Islands Of Adventure">Islands Of Adventure</option>
      <option value="Volcano Bay Water">Volcano Bay Water</option>
     
    </select>
   <!--  <label style="display: block;" for="fname">Park *</label>
      <input type="text" class="typeahead form-control" required name="park[]" aria-describedby="customer" placeholder="Park *" > -->
       </div>
 </div>
</div>
    </div>



 <?php } ?>







  
    <div class="block">
        <span class="add add-btn">Add More History</span>
        <input type="submit" value="Submit" name="customerdata">
    </div>
    </form>
</div>
<script>
$('.add').click(function() {
    $('.block:last').before('<div class="block"><div class="row"><div class="col-md-12"><div class="form-group"><label style="display: block;" for="fname">Date *</label><input type="date" class="typeahead form-control" required name="history_date[]" id="" placeholder="Date *" > </div></div><div class="col-md-6"><div class="form-group"> <label style="display: block;" for="fname">Time *</label><input type="text" class="typeahead form-control" required name="history_time[]" id="" placeholder="Time *" >   </div></div><div class="col-md-6"><div class="form-group"><label style="display: block;" for="fname">Park *</label><select name="park[]" class="typeahead form-control"><option value="">Please select park</option><option value="Universal Studios">Universal Studios</option><option value="Islands Of Adventure">Islands Of Adventure</option><option value="Volcano Bay Water">Volcano Bay Water</option></select></div></div></div><span class="remove red">Remove History</span></div>');
});
$('.optionBox').on('click','.remove',function() {
  $(this).parent().remove();
});
</script>
</div>
   </div>
   
  </body>

</html>
