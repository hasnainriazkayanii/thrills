<?php

include('../Config/Connection.php');

session_start();

$login_check=$_SESSION['id'];

//var_dump($data1);

if ($login_check!='1') {
    $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");

}

$id=$_GET['id'];
$aStatus=$_GET['status'];

$sql="SELECT * FROM tickettypes where id='$id'";

$result=mysqli_query($db,$sql);

$user=mysqli_fetch_assoc($result);

// var_dump($user);

if(isset($_POST['customer']))

{

    $ticketType=$_POST['ticketType'];

    /* $TicketCode=$_POST['TicketCode'];*/

    $numberofdays=$_POST['numberofdays'];

    $adultprice=$_POST['adultprice'];

    $childprice=$_POST['childprice'];

    $ticketname=$_POST['ticketname'];

    $theme_park_id=$_POST['theme_park_id'];

    $active=$_POST['active'];
    $addOn = $_POST['addon'];
    $create_date=time();

if($_FILES['ticketimage']['size'] == 0){
    $ticket_attachment_DBpath = "";
}
else{
    $filename = $_FILES['ticketimage']['name'];
    $ticket_attachment_DBpath = ",image = 'images/ticket_attachments/$filename'";

    move_uploaded_file($_FILES['ticketimage']['tmp_name'], "../images/ticket_attachments/".$filename);
}



    $customer_update = "UPDATE tickettypes SET 

        ticket_type= '$ticketType',

     numberofdays= '$numberofdays',

     adult_price='$adultprice',

     child_price='$childprice',

     ticket_name='$ticketname',

     theme_park_id = $theme_park_id,

     adctive='$active',
     addOn='$addOn',

     created_on='$create_date'
     
     $ticket_attachment_DBpath

       WHERE id='$id'";

//var_dump($customer_update);

    mysqli_query($db,$customer_update);
    $action_by = $_SESSION['user_id'];
    $timestamp_insert = "INSERT INTO timestamps (type,object_id,action,action_by)

    VALUES ('Ticket Type','$id','Updated','$action_by')";
  
    $result = mysqli_query($db,$timestamp_insert);
    header( "Location: TicketsDetails.php?status=".$aStatus );



}

include('../includes/header.php');

?>

<?php
$theme_parks_query = "SELECT * FROM theme_parks where active = 1 ORDER BY code DESC";
$theme_parks = mysqli_query($db, $theme_parks_query);
?>













<div id="content-wrapper">

    <div class="container-fluid">





        <div class="col-md-12">

            <h3>Update Ticket Type</h3>

            <hr>

        </div>

    </div>



    <div class="container" style="display:flex;justify-content:center;margin-top:4%;">

        <div class="col-md-7">



            <form action="UpdateTicketType.php?id=<?=$id?>&status=<?=$aStatus?>" method="post" enctype="multipart/form-data">

                <div class="form-group">

                    <label for="fname">Ticket Type *</label>

                    <input type="text" class="form-control" required name="ticketType" id="name" aria-describedby="fname" value='<?=$user['ticket_type']?>' placeholder="Ticket Type *">

                </div>

                <!--<div class="form-group">

                 <label for="fname">Ticket Code *</label>

                 <input type="text" class="form-control" required name="TicketCode" id="name" aria-describedby="TicketCode" placeholder="Ticket Code *">

               </div>-->

                <div class="form-group">

                    <label for="fname">Number Of Days *</label>

                    <input type="text" class="form-control" onkeypress="return AllowNumbersOnly(event)" required name="numberofdays" value='<?=$user['numberofdays']?>' id="noofdays" aria-describedby="fname" placeholder="Number Of Days *">

                </div>

                <div class="form-group">

                    <label for="fname">Adult price *</label>

                    <input type="text" class="form-control" required name="adultprice" id="adultprice" aria-describedby="adultprice" value='<?=$user['adult_price']?>'  placeholder="Adult price *">

                </div>

                <div class="form-group">

                    <label for="fname">Child price *</label>

                    <input type="text" class="form-control" required name="childprice" id="childprice" aria-describedby="childprice" value='<?=$user['child_price']?>'  placeholder="Child price *">

                </div>



                <div class="form-group">

                    <label for="fname">Ticket name *</label>

                    <input type="text" class="form-control" required name="ticketname" id="ticketname" aria-describedby="ticketname" value='<?=$user['ticket_name']?>'  placeholder="Ticket name *">

                </div>

                <div class="form-group">
                <label for="place">Theme Park *</label>

                <select class="form-control" name="theme_park_id" value='<?=$user['theme_park_id']?>'>

                    <?php

                    while($theme_park = mysqli_fetch_assoc($theme_parks)) {
                        $tp_name=$theme_park['name'];
                        $tp_id = $theme_park['id'];
                        $tp_code = $theme_park['code'];
                        ?>

                        <option value="<?=$tp_id?>" <?php if ($tp_id == $user['theme_park_id']) echo 'selected';?>><?=$tp_name." (".$tp_code.")"?></option>

                        <?php
                    }
                    ?>
                </select>
                </div>


                <div class="form-group">
                <label for="place">Active *</label>

                <select value='<?=$user['active']?>' class="form-control" name="active">

                    <option  value="True">true</option>

                    <option value="False">false</option>

                </select>
                </div>
                <div class="form-group">

                    <label for="place">AddOn *</label>

                    <select class="form-control" name="addon">

                        <option <?=(!$user['addon']?'selected':'')?> value="0">No</option>

                        <option <?=($user['addon']?'selected':'')?> value="1">Yes</option>



                    </select>
                </div>
                <div class="form-group">

                    <label for="image">Ticket image *</label>
                    <?php

                        if ($user['image']  != ""){

                    ?>
                    <div id="img-preview"> <img src="../<?php echo $user['image']; ?>" width="100px" height="100px"></div>
                    <?php } ?>
                    <input type="hidden" class="old_image" value="<?php echo $user['image']; ?>">
                    <input type="file" class="form-control" name="ticketimage" id="ticketimage" aria-describedby="ticketimage">

                </div>


                <div class="form-group" style="text-align:center;">

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

<script type="text/javascript">


    $('form').submit(function (e) {
        if ($('#ticketimage').get(0).files.length === 0 && $('.old_image').val() == "") {
            e.preventDefault();
            alert("No files selected.");
        }
    })


    function AllowNumbersOnly(e) {

        var code = (e.which) ? e.which : e.keyCode;

        if (code > 31 && (code < 48 || code > 57)) {

            e.preventDefault();

        }

    }



</script>