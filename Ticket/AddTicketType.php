<?php

include('../Config/Connection.php');

session_start();

$login_check=$_SESSION['id'];

if ($login_check!='1')

{
    $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");

}

if(isset($_POST['submittickettype']))

{

    $ticketType=$_POST['ticketType'];

    $numberofdays=$_POST['numberofdays'];

    $adultprice=$_POST['adultprice'];

    $childprice=$_POST['childprice'];

    $ticketname=$_POST['ticketname'];

    $theme_park_id = $_POST['theme_park_id'];

    $image = $_POST['image'];
    $addOn = $_POST['addon'];

    $filename = $_FILES['ticketimage']['name'];
    $ticket_attachment_DBpath = "images/ticket_attachments/".$filename;

    move_uploaded_file($_FILES['ticketimage']['tmp_name'], "../images/ticket_attachments/".$filename);




    $active=$_POST['active'];

    $create_date=time();

    $park_insert = "INSERT INTO tickettypes (ticket_type,numberofdays,adult_price,child_price,ticket_name,adctive,created_on,theme_park_id,image,addOn)

            VALUES ('$ticketType','$numberofdays','$adultprice','$childprice','$ticketname','$active','$create_date', $theme_park_id,'$ticket_attachment_DBpath','$addOn')";

    $result = mysqli_query($db,$park_insert);
    $id  = mysqli_insert_id($db);
    if($id){
        $action_by = $_SESSION['user_id'];
        $timestamp_insert = "INSERT INTO timestamps (type,object_id,action,action_by)
        VALUES ('Ticket Type','$id','Created','$action_by')";
        $result = mysqli_query($db,$timestamp_insert);
    }

    header( "Location: TicketsDetails.php" );

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

            <h3>Add Ticket Type</h3>

            <hr>

        </div>

    </div>

    <div class="container" style="display:flex;justify-content:center;margin-top:4%; ">

        <div class="col-md-7">

            <form action="AddTicketType.php" autocomplete='off' method="post" enctype="multipart/form-data">

                <div class="form-group">

                    <label for="fname">Ticket Type *</label>

                    <input type="text" class="form-control" required name="ticketType" id="name" aria-describedby="fname" placeholder="Ticket Type *">

                </div>

                <div class="form-group">

                    <label for="fname">Number Of Days *</label>

                    <input type="text" class="form-control" onkeypress="return AllowNumbersOnly(event)" required name="numberofdays" id="noofdays" aria-describedby="fname" placeholder="Number Of Days *">

                </div>

                <div class="form-group">

                    <label for="fname">Adult price *</label>

                    <input type="text" class="form-control" required name="adultprice" id="adultprice" aria-describedby="adultprice" placeholder="Adult price *">

                </div>

                <div class="form-group">

                    <label for="fname">Child price *</label>

                    <input type="text" class="form-control" required name="childprice" id="childprice" aria-describedby="childprice" placeholder="Child price *">

                </div>



                <div class="form-group">

                    <label for="fname">Ticket name *</label>

                    <input type="text" class="form-control" required name="ticketname" id="ticketname" aria-describedby="ticketname" placeholder="Ticket name *">

                </div>

                <div class="form-group">
                <label for="place">Theme Park *</label>

                <select class="form-control" name="theme_park_id">

                    <?php

                    while($theme_park = mysqli_fetch_assoc($theme_parks)) {
                        $tp_name=$theme_park['name'];
                        $tp_id = $theme_park['id'];
                        $tp_code = $theme_park['code'];
                        ?>

                        <option value="<?=$tp_id?>"><?=$tp_name." (".$tp_code.")"?></option>


                        <?php
                    }
                    ?>

                </select>

                </div>
                <div class="form-group">

                <label for="place">Active *</label>

                <select class="form-control" name="active">

                    <option value="True">true</option>

                    <option value="False">false</option>



                </select>
                </div>



                <div class="form-group">

                    <label for="place">AddOn *</label>

                    <select class="form-control" name="addon">

                        <option value="0">No</option>

                        <option value="1">Yes</option>



                    </select>
                </div>

                <div class="form-group">

                    <label for="image">Ticket image *</label>

                    <input type="file" class="form-control" required name="ticketimage" id="ticketimage" aria-describedby="ticketimage">

                </div>


                <div class="form-group" style="text-align:center;">

                    <button type="submit"  name="submittickettype"class="btn btn-primary">Submit</button>

                </div>

            </form>

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



        </div>

    </div>







    </body>



    </html>

    <script type="text/javascript">





        function AllowNumbersOnly(e) {

            var code = (e.which) ? e.which : e.keyCode;

            if (code > 31 && (code < 48 || code > 57)) {

                e.preventDefault();

            }

        }



    </script>

