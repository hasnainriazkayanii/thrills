<?php

include('../Config/Connection.php');

session_start();

$login_check = $_SESSION['id'];

//var_dump($data1);

if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}


$id="";
if(isset($_GET['id'])){
$id = $_GET['id'];

$sql = "SELECT * FROM customer where id='$id'";

$result = mysqli_query($db, $sql);

$user = mysqli_fetch_assoc($result);

$result16 = mb_substr($user['Phone_number'], 0, 3);

$result17 = mb_substr($user['Phone_number'], 3, 3);

$result18 = mb_substr($user['Phone_number'], 6, 4);

$result19 = "(" . $result16 . ") " . $result17 . "-" . $result18;



if (isset($_POST['customer'])) {
  // echo '<pre>',print_r($_POST);exit;
  //$id=$_GET['id'];
  
  
    $country_code = $_POST['country_code'];
  $fname = $_POST['fname'];

  $lname = $_POST['lname'];

  $mobnumber = $_POST['mobnumber'];
  
  $country_code_name = $_POST['add_customer_country_code'];

  $mobnumber1  = str_replace("(", "", $mobnumber);

  $mobnumber2  = str_replace(")", "", $mobnumber1);

  $mobnumber3  = str_replace("-", "", $mobnumber2);

  $mobnumber4  = str_replace(" ", "", $mobnumber3);

  /* $lastvisit=$_POST['lastvisit'];*/

  $city = $_POST['city'];

  $ethnicity = $_POST['ethnicity'];
  $event=$_POST['event'];

  $current_date = date("Y/m/d");

  $newDate = date("m/d/Y", strtotime($current_date));

  $referral=$_POST['referral'];
  $friendname=$_POST['userid'];
  $purpose_type= $_POST['purpose_type'];
  $purpose_details= $_POST['purpose_details'];

  $customer_update = "UPDATE customer SET 
                            event='$event',
                          first_name='$fname',

                           Last_name='$lname',

                           Phone_number='$mobnumber4',

                           last_visit='$newDate',

                           homecity='$city',

                           ethnicity='$ethnicity',
                           
                           referral='$referral',
                           friend='$friendname',
                           purpose_type='$purpose_type',
                           purpose_details='$purpose_details',
                           country_code = '$country_code',
                           country_code_name ='$country_code_name'

                           WHERE id='$id'";

  mysqli_query($db, $customer_update);
  $action_by = $_SESSION['user_id'];
  $timestamp_insert = "INSERT INTO timestamps (type,object_id,action,action_by)

  VALUES ('Customer','$id','Updated','$action_by')";

  $result = mysqli_query($db,$timestamp_insert);

//   header("Location: ../Orders/Addorders.php?id=$id");
  header("Location: ../Customers/CustomersDetails.php");

}







include('../includes/header.php');





?>





<style>
    .detailsbox{
        
        display:none;
    }
    
</style>

<div id="content-wrapper">

  <div class="container-fluid">

    <?php $status = $_SESSION['status']; ?>

    <div class="row">

      <div class="col-md-12">

        <div class="col-md-8" style="float:left;">
          <h3> Update Customer</h3>
        </div>

        <?php if ($status == '1') { ?>

          <div class="col-md-4 text-right" style="float:left;"><a onClick="return confirm('Are you sure you want to delete?')" href='CustomerDelete.php?id=<?= $id ?>' class="btn btn-primary">Delete</a></div>

        <?php }; ?>

      </div>

    </div>

    <hr>


    <?php

    $ethnicityname = "SELECT * FROM ethnicity order by ethncity_name";



    $ethnicitsult = mysqli_query($db, $ethnicityname);





    ?>



    <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">

      <div class="col-md-7">



        <form action="AddCustomer.php?id=<?= $id ?>" autocomplete='off' method="post">

          <div class="form-group">

            <label for="fname">First Name *</label>

            <input type="text" class="form-control" required name="fname" id="fname" aria-describedby="fname" value='<?= $user['first_name'] ?>' placeholder="First Name *" required>

          </div>

          <div class="form-group">

            <label for="lname">Last Name *</label>

            <input type="text" class="form-control" required name="lname" id="lname" aria-describedby="lname" value='<?= $user['Last_name'] ?>' placeholder="Last Name *" required>

          </div>

          <div class="form-group">

            <label for="mobnumber">Mobile Number *</label>

            <?php
            $sql="SELECT * FROM `login_user` where email='".$_SESSION['login_user']."' AND level='9'";
            $auth_user = mysqli_query($db, $sql);
            while($roww=mysqli_fetch_assoc($auth_user)){
              $auth_email=$roww['email'];
            }
            if($auth_email){
              $masked_no=$result19;
            }else{
              $masked_no="(***) " . '***-' . substr($result19, -4);
            }
            ?>
            <input type="text" class="form-control" required name="mobnumber" id="TextBox1" aria-describedby="mobnumber"  country-code-name =<?= $user['country_code_name']==""?'us':$user['country_code_name'] ?> country='<?=$user['country_code']?>' value='<?= $masked_no ?>' placeholder="Mobile Number *">
            <input type="hidden" id="add_customer_country_code" name="add_customer_country_code" value="">

          </div>

          <!--  <div class="form-group">

    <label for="lastvisit">Last Visit  *</label>

    <input type="date" class="form-control" name="lastvisit" id="lastvisit"  required placeholder="Last Visit" value=''>

    <span id="homecity" class="text-danger font-weight-bold"></span>

  </div> -->



          <div class="form-group">

            <label for="exampleInputEmail1">Home City *</label>

            <input type="text" class="form-control" required name="city" id="city" aria-describedby="email" placeholder="Home City *" value='<?= $user['homecity'] ?>' required>

            <span id="homecity" class="text-danger font-weight-bold"></span>

          </div>
          <div class="form-group">

            <label for="fname">Ethnicity *</label>

            <select required class="form-control" name="ethnicity" id="ethnicity">

              <!--<option value="">Please Select Ethnicity..</option>-->

              <?php

              while ($ethnicitsult1 = mysqli_fetch_assoc($ethnicitsult)) {

                $ethncity_name = $ethnicitsult1['ethncity_name'];



              ?>

                <option <?php if ($user['ethnicity'] == $ethncity_name) {
                          echo "selected";
                        } ?> value="<?= $ethncity_name ?>"><?= $ethncity_name ?></option>

              <?php



              } ?>

            </select>

          </div>
       <div class="form-group">
    <label for="mobnumber">What Brings You To Town?*
</label>
    <br>
    <?php 
    
    $sql = "SELECT * FROM purpose_type order by purpose ASC";
$result = mysqli_query($db, $sql);
$datas =mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    ?>
    <select class="form-control purpose_types" name="purpose_type">
        
        <?php foreach($datas as $data ) { ?>
        <option <?php if ($user['purpose_type'] == $data['id']) {
                          echo "selected";
                        } ?> value="<?php echo $data['id']?>" data-details="<?php echo $data['details']?>"><?php echo $data['purpose']?> </option>
        
        
        <?php 
        
        
        
        }?>
        
    </select>
    
  </div>
  <?php if ($user['purpose_details']!=""){ ?>
   <div class="form-group detailsbox" style="display:block">
      <label for="mobnumber">Please Specify</label>
      <textarea class="form-control" name="purpose_details"><?php echo $user['purpose_details']; ?></textarea>
      
  </div>
<?php } ?>
          <input type="text" class="form-control" required name="event" id="city" aria-describedby="event" placeholder="Event *" value='<?= $user['event'] ?>' hidden>


          <?php

          $referral_sql = "SELECT * FROM referral_types";
          $referral_result = mysqli_query($db, $referral_sql);
          $referral_data = mysqli_fetch_all($referral_result, MYSQLI_ASSOC);

          $referral_array = explode(' (', $user['referral']);
          $referral_default_value = $referral_array[0];
          $referral_default_value = trim($referral_default_value);
          $friend = $user['friend'];
          $friendid = $user['friend'];
            
          $sssql = "SELECT * FROM customer WHERE id='$friend' ";
            $fresult = mysqli_query($db, $sssql);
            $firlas=mysqli_fetch_row($fresult);
            $friend=$firlas['2']." ".$firlas['3'];
         
            
          if (isset($referral_array[1])) {
            $friend = $referral_array[1];
            $friend = str_replace(')', '', $friend);
            $friend = $user['friend'];
          }

          ?>
          <div class="form-group">
            <label for="referral">Referred Through *</label>
            <select required class="form-control" name="referral" id="referral" onchange="referralInput(this);">
              <option value="">Please Select Referral..</option>

              <?php foreach ($referral_data as $referral_option) {
              ?>
                <option <?php if ($user['referral'] == $referral_option['id']) {
                          echo "selected";
                        } ?> value="<?= $referral_option['id'] ?>" <?php if($referral_option['name'] === $referral_default_value) echo 'selected'; ?>> <?= $referral_option['name'] ?> </option>

              <?php } ?>

            </select>

          </div>

          <div class="form-group" style="text-align:center;">
            <input type="hidden" name="country_code" value="">
            <button type="submit" name="customer" onclick="myFunction()" class="btn btn-primary">Update</button>

          </div>

        </form>



      </div>

    </div>

  </div>



  <!-- /.container-fluid -->



  <!-- Sticky Footer -->

  <footer class="sticky-footer">

    <div class="container my-auto">

      <div class="copyright text-center my-auto">

        <!-- <span>Copyright © Universal Orlando Resort 2018</span> -->

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

<script src="../build/js/intlTelInput.js"></script>
  <script>
    var input = document.querySelector("#TextBox1");
    var codename = $("#TextBox1").attr("country-code-name");
    
   
    var iti = window.intlTelInput(input, {
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      // hiddenInput: "full_number",
      // initialCountry: "auto",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      // preferredCountries: ['cn', 'jp'],
      // separateDialCode: true,
      utilsScript: "../build/js/utils.js",
    });
    
    
    
    iti.setCountry(codename);
    
  </script>

<script>

    function myFunction(){
      $('.iti__selected-flag').each(function(i,flag){
        var a=$(flag)[0].title;
        const myArray = a.split(" ");
        var code=myArray[myArray.length - 1];
        console.log(code);
        $('input[name="country_code"]').val(code);
     
      })
      
      
      
       $('.iti__country-list').each(function(index,element){
        var b = $(element).find('.iti__active').attr('data-country-code');
        $('#add_customer_country_code').val(b);

      })
    }

  </script>

<script type="text/javascript">
  $.validate({

    lang: 'en'

  });
</script>

<script>
 $(".purpose_types").change(function(){
  var details=$( this ).find(':selected').data( "details" );
 
  if(details==1){
      
      $('.detailsbox').css('display','block');
      
  }
  else{
      $('.detailsbox').css('display','none');
      
  }
});
  function referralInput(el) {
    if (el.value == '8') {
      let lable = "<label for='referral'>Friend Name</label>";
      let input = $("<input type='text' class='form-control' name='friend' placeholder='Friend Name' value='<?php echo $friend; ?>' onblur='setTimeout(()=>{$(\"#userlist\").remove();},200)' onkeyup='checkUser(this);' required>");
      let input2 = $("<input id='userid' type='hidden' name='userid' value='<?php echo $friendid; ?>'>");
      let div = $('<div></div').attr('class', 'form-group').attr('id', 'friend').append(lable, input, input2);
      $(el).parent().after(div)

    } else {
      $('#friend').remove();
    }
  }

  referralInput(document.getElementById('referral'));

  function checkUser(elem, newuser=false){
    if(elem.value){
      ch = elem.value;
    }else{
      $('#userlist').remove();
      return false;
    }
    
    if(newuser){
      var userlink = 'UpdateCustomer.php?id=';
      var useraction = '';
    }else{
      var userlink = '#';
      var useraction = "onclick='setUser(this);'";
    }
    
    $.ajax({
      url: '../Customers/check_user.php?user=' + ch,
      success: function(response) {
        console.log(response);
        data = JSON.parse(response);
        let suggestions = $("<div></div>").addClass('dropdown').attr('id','userlist');
          let list = $("<div id='selectuser' class='dropdown-menu show bg-light'></div>");
          if(data.status == true){
            data.data.forEach(element => {
              listItem = $('<a onclick="setUser(this);" '+ useraction +'></a>').addClass('dropdown-item text-dark').attr('data-key', element.id).attr('href', userlink + element.id).html(element.name);
              list.append(listItem);
            });
            
          }

          suggestions.html(list);
          $('#userlist').remove();
          $(elem).after(suggestions);
        }

      });

  }

  function setUser(op){
    $('#userid').val($(op).data('key'));
    console.log($(op).data('key'));
    $('input[name=friend]').val($(op).text());
    $('#selectuser').hide();

  }

  $("#TextBox1").on("keyup", function(e) {

    e.target.value = e.target.value.replace(/[^\d]/, "");

    e.target.value = e.target.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');

    e.target.value = e.target.value.replace(/[^\w ]/, '');

    console.log(e.target.value.length);

    if (e.target.value.length === 10) {

      // do stuff

      var ph = e.target.value.split("");

      ph.splice(3, 0, ") ");
      ph.splice(7, 0, "-");

      $("#TextBox1").val('(' + ph.join(""))

    }

  })
 
</script>














<?php
}
else{
 include('../Config/Connection.php');

 //Check Login

      session_start();

       $login_check=$_SESSION['id'];

     if ($login_check!='1')

     {
      $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
       header("../Login/login.php");

     }

     

    //End Login

    //Insert Customers

    if(isset($_POST['customer']))

    {

      $fname=$_POST['fname'];

      $lname=$_POST['lname'];

      $phone=$_POST['phone'];
      $country_code=$_POST['country_code'];
      $country_code_name = $_POST['add_customer_country_code'];
      

      // $mobnumber1  = str_replace("(", "", $mobnumber);

      //  $mobnumber2  = str_replace(")", "", $mobnumber1);

       

      //  $mobnumber3  = str_replace("-", "", $mobnumber2);

      //   $mobnumber4  = str_replace(" ", "", $mobnumber3);

     // var_dump($mobnumber4);die;

       //$lastvisit=$_POST['lastvisit'];

      $city=$_POST['city'];

      $ethnicity=$_POST['ethnicity'];

      $referral=$_POST['referral'];
      $friendname=$_POST['userid'];
    $purpose_type= $_POST['purpose_type'];
    $purpose_details= $_POST['purpose_details'];
      $current_date=date("Y/m/d");

       $newDate = date("m/d/Y", strtotime($current_date));

      $customer_insert = "INSERT INTO customer(customer_id,first_name,Last_name,country_code,country_code_name,Phone_number,last_visit,homecity,ethnicity,referral,friend,purpose_type,purpose_details)VALUES ('0','$fname','$lname','$country_code','$country_code_name','$phone','$newDate','$city','$ethnicity','$referral','$friendname','$purpose_type','$purpose_details')";
      
      $result = mysqli_query($db,$customer_insert);

       $id= mysqli_insert_id($db); 
       if($id){
        $action_by = $_SESSION['user_id'];
        $timestamp_insert = "INSERT INTO timestamps (type,object_id,action,action_by)
        VALUES ('Customer','$id','Created','$action_by')";
        $result = mysqli_query($db,$timestamp_insert);
      }


      header( "Location: ../Orders/Addorders.php?id=$id" );

     /* header( "Location: CustomersDetails.php" );*/

    }

    //End Insert

    //Include Header

 include('../includes/header.php');

 //End Header

?>
<style>
    .detailsbox{
        
        display:none;
    }
    
</style>
      <div id="content-wrapper">

       <div class="container-fluid">

	  

	  <div class="col-md-12">

		<h3>Add Customer</h3>

	  <hr>

	   </div>	

	  <?php

    $ethnicityname = "SELECT * FROM ethnicity order by ethncity_name";
    $ethnicitsult = mysqli_query($db,$ethnicityname);

    //Referral Types
    $referral_sql = "SELECT * FROM referral_types where active = 0 order by name asc ";
    $referral_result = mysqli_query($db, $referral_sql);
    $referral_data = mysqli_fetch_all($referral_result, MYSQLI_ASSOC);

   ?>


	   <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">

	   <div class="col-md-7">

	   

      <form action="AddCustomer.php" autocomplete='off' method="post" onblur="return validation();" name="vfrom">

        <div class="form-group">

    <label for="fname">First Name *</label>

    <input type="text" required class="form-control" onblur='setTimeout(()=>{$("#userlist").remove();}, 200)' onkeyup="checkUser(this,true);" name="fname" id="fname" aria-describedby="fname"  placeholder="First Name *" value="" >

    <span id="username"  class="text-danger font-weight-bold"> </span>

  </div>

  <div class="form-group">

    <label for="lname">Last Name *</label>
    <input type="hidden" name="country_code" id="country_code" value="">

    <input type="text" class="form-control" name="lname"id="lname" aria-describedby="lname" required placeholder="Last Name *" value="">

    <span id="lastname" class="text-danger font-weight-bold"> </span>

  </div>

   <div class="form-group">
    <label for="mobnumber">Mobile Number *</label>
    <br>
    <input type="tel" id="phone" required class="form-control" name="phone" aria-describedby="mobnumber" placeholder="Mobile Number *"  value="">
    <input type="hidden" id="add_customer_country_code" name="add_customer_country_code" value="">
  </div>

  

   <div class="form-group">

    <label for="Homecity">Home City *</label>

    

    <input type="text" class="form-control" name="city" id="city" aria-describedby="email" required placeholder="Home City *" value="">

    <span id="homecity" class="text-danger font-weight-bold"></span>

  </div> 

 <div class="form-group">

   <label for="fname">Ethnicity *</label> 

      <select required  class="form-control" name="ethnicity" id="ethnicity" >

         <option value="">Please Select Ethnicity..</option>

         <?php

       while($ethnicitsult1 = mysqli_fetch_assoc($ethnicitsult)) {

        $ethncity_name=$ethnicitsult1['ethncity_name'];



          ?>

     <option value="<?=$ethncity_name?>"><?=$ethncity_name?></option>

     <?php



   }?>

    </select>

</div>
   <div class="form-group">
    <label for="mobnumber">What Brings You To Town?*</label>
    <br>
    <?php 
    
    $sql = "SELECT * FROM purpose_type order by purpose ASC";
$result = mysqli_query($db, $sql);
$datas =mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    ?>
    <select class="form-control purpose_types" name="purpose_type">
        <option value="">Select Reason</option>
        <?php foreach($datas as $data ) { ?>
        <option data-details="<?php echo $data['details']?>" value="<?php echo $data['id']?>"><?php echo $data['purpose']?> </option>
        
        
        <?php 
        
        
        
        }?>
        
    </select>
    
  </div>
  <div class="form-group detailsbox">
      <label for="mobnumber">Please Specify</label>
      <textarea class="form-control" name="purpose_details"></textarea>
      
  </div>

<?php if(count($referral_data) > 0):  //check for referral_data ?>
<!-- Referral types -->
 <div class="form-group">
   <label for="referral">Referred Through *</label> 
      <select required  class="form-control" name="referral" id="referral" onchange="referralInput(this);">
         <option value="">Please Select Referral..</option>

        <?php foreach($referral_data as $referral_option) {
        ?>
          <option value="<?=$referral_option['id']?>"> <?=$referral_option['name']?> </option>

        <?php } ?>

    </select>

</div>

<?php endif; //check for referral_data ?>


  <div class="form-group" style="text-align:center;">
    <button type="submit"  id="abcd"  onclick="myFunction()" name="customer"class="btn btn-primary">Submit</button>
  </div>

</form>



</div >

</div>

</div>

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





    <script type="text/javascript">

 $.validate({

    lang: 'en'

  });

 

</script>

<script>
$(".purpose_types").change(function(){
  var details=$( this ).find(':selected').data( "details" );
  
  if(details==1){
      
      $('.detailsbox').css('display','block');
      
  }
  else{
      $('.detailsbox').css('display','none');
      
  }
});
    $("#TextBox1").on("keyup", function(e) {

  e.target.value = e.target.value.replace(/[^\d]/, "");

 e.target.value=e.target.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');

  e.target.value=e.target.value.replace(/[^\w ]/,'');

 console.log(e.target.value.length);

  if (e.target.value.length === 10) {

    // do stuff

    var ph = e.target.value.split("");

    ph.splice(3, 0, ") "); ph.splice(7, 0, "-");

    $("#TextBox1").val('('+ph.join(""))

  }

})

</script>


<script src="../build/js/intlTelInput.js"></script>
  <script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      // hiddenInput: "full_number",
      // initialCountry: "auto",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      // preferredCountries: ['cn', 'jp'],
      // separateDialCode: true,
      utilsScript: "../build/js/utils.js",
    });
  </script>
  <script>
    function myFunction(){
    var a=document.getElementsByClassName('iti__selected-flag')[0].title;
      const myArray = a.split(" ");
      var code=myArray[myArray.length - 1];
      document.getElementById('country_code').value =code;
   
 $('.iti__country-list').each(function(index,element){
        var b = $(element).find('.iti__active').attr('data-country-code');
        $('#add_customer_country_code').val(b);

      })




    }


  </script>

<script>
  function referralInput(el){
    if(el.value == '8'){
      let lable = "<label for='referral'>Friend Name</label>";
      let input = $("<input type='text' class='form-control' name='friend' placeholder='Friend Name' onblur='setTimeout(()=>{$(\"#userlist\").remove();},200)' onkeyup='checkUser(this);' required>");
      let input2 = $("<input id='userid' type='hidden' name='userid'>");
      let div = $('<div></div').attr('class','form-group').attr('id','friend').append(lable, input, input2);
      $(el).parent().after(div)
      
    }else{
      $('#friend').remove();
    }
  }
  
  
  function checkUser(elem, newuser=false){
    if(elem.value){
      ch = elem.value;
    }else{
      $('#userlist').remove();
      return false;
    }
    
    if(newuser){
      var userlink = 'UpdateCustomer.php?id=';
      var useraction = '';
    }else{
      var userlink = '#';
      var useraction = "onclick='setUser(this);'";
    }
    
    $.ajax({
      url: '../Customers/check_user.php?user=' + ch,
      success: function(response) {
        console.log(response);
        data = JSON.parse(response);
        let suggestions = $("<div></div>").addClass('dropdown').attr('id','userlist');
          let list = $("<div id='selectuser' class='dropdown-menu show bg-light'></div>");
          if(data.status == true){
            data.data.forEach(element => {
              listItem = $('<a onclick="setUser(this);" '+ useraction +'></a>').addClass('dropdown-item text-dark').attr('data-key', element.id).attr('href', userlink + element.id).html(element.name);
              list.append(listItem);
            });
            
          }

          suggestions.html(list);
          $('#userlist').remove();
          $(elem).after(suggestions);
        }

      });

  }

  function setUser(op){
    $('#userid').val($(op).data('key'));
    console.log($(op).data('key'));
    $('input[name=friend]').val($(op).text());
    $('#selectuser').hide();

  }

</script>


  </body>

</html>
<?php }?>
