<?php
include('../Config/Connection.php');

//login check

session_start();

$login_check = $_SESSION['id'];

if ($login_check != '1') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $full_url = $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $_SESSION['intended_url'] = $full_url;
    header("location: ../Login/login.php");
}

//end



$id = $_GET['id'];

$sql = "SELECT * FROM `order` where id='$id'";

$result = mysqli_query($db, $sql);

$user = mysqli_fetch_assoc($result);

// order parameter

if (isset($_POST['updateorder'])) {

    $customer_id = $_POST['idHidden'];
    
    $gateway = $_POST['gateway'];

    $customer = $_POST['customer'];

    $date_of_vist1 = $_POST['date_of_vist'];

    $date_of_vist = date("Y/m/d", strtotime($date_of_vist1));

    $time1 = $_POST['time'];

    $aa12 = date("g:i A", strtotime($time1));

    $option = $_POST['option'];

    $price = $_POST['cost'];

    $ticket_type = $_POST['ticket_type'];

    $ticket_type33 = (explode(" ", $ticket_type));

    $ticket_type2 = array_pop($ticket_type33);

    $ticket_type34 = implode(" ", $ticket_type33);

    $adults = $_POST['adults'];

    $kids = $_POST['kids'];

    $discount = $_POST['discount'];

    $total = $_POST['total'];

    $theme_park_id = $_POST['theme_park_id'];

    $hideorder = $_POST['hideorder'] == true ? 1 : 0;

    $deposit = $_POST['deposit'];

    //// for change order_id;
    $sql = "SELECT theme_park_parents.code 
                                    FROM theme_parks 
                                    LEFT JOIN theme_park_parents ON theme_parks.theme_park_parent_id=theme_park_parents.id
                                    WHERE theme_parks.id='$theme_park_id'";

    $result = mysqli_query($db, $sql);

    $theme_park_parent = mysqli_fetch_assoc($result);
    $theme_park_parent_code = $theme_park_parent['code'];

    $order_id = $user['order_id'];
    $order_id = $theme_park_parent_code . substr($order_id, 2);

    /// end change order_id

    $timestamp = time();

    //end parameters

    $datetimeFormat = 'Y-m-d';

    $date = new \DateTime();

    $date->setTimestamp($timestamp);

    $create_date = $date->format($datetimeFormat);

    $ticket_order = $adults . 'ad/' . $kids . 'ch/' . $ticket_type34;

    $order_insert = "UPDATE `order` SET
                `order_id`='$order_id',

                `customer_id`='$customer_id',

                `customer`='$customer',

                `date_of_visit`='$date_of_vist',

                `time`='$aa12',

                `option`='$option',

                `ticket_type`='$ticket_type',

                `price`='$price',

                `ticket_order`='$ticket_order',

                `adults`='$adults',

                `kids`='$kids',

                `discount`='$discount',

                `total`='$total',

                `assign`=0,

                `create_time`='$timestamp',
                `theme_park_id`=$theme_park_id,
                `is_hide`=$hideorder,

                `deposit`=$deposit,
                `gateway`='$gateway'

                

                WHERE id='$id'";
                
// print_r($order_insert);
    $result = mysqli_query($db, $order_insert);

    if ($kids > $user['kids']) {

        $guest_insert = "INSERT INTO guest (order_id,guest_name,guest_mobile,inactive,isdisabled,type,login_id,ticket_id,entitlement)
                                                    VALUES ('$id','$customer','',0,1,'kid','','','')";

        $result = mysqli_query($db, $guest_insert);
    }




    header("Location: Orderdetails.php?active=0&sucess=1");
}





$sql = "SELECT * FROM customer";

$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) > 0) {



    while ($row = mysqli_fetch_assoc($result)) {





        $objet = new stdClass;

        $objet->id = $row["id"];

        $objet->label = $row["first_name"] . ' ' . $row["Last_name"];

        $Customer_name[] = $objet;
    }

    $Customer_name = json_encode($Customer_name);
}





include('../includes/header.php');
?>



<style>



    .my-form{
        width: 100%;

        height: 38px;

        border-radius: 5px;

        border: 1px solid #33333338;
    }



    .tt-query, /* UPDATE: newer versions use tt-input instead of tt-query */

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



    .tt-query {
        /* UPDATE: newer versions use tt-input instead of tt-query */

        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);

    }



    .tt-hint {

        color: #999;

    }



    .tt-menu {
        /* UPDATE: newer versions use tt-menu instead of tt-dropdown-menu */

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



    .tt-suggestion.tt-is-under-cursor {
        /* UPDATE: newer versions use .tt-suggestion.tt-cursor */

        color: #fff;

        background-color: #0097cf;



    }



    .tt-suggestion p {

        margin: 0;

    }





    .left-col{
        margin-top: 10px;

        float: left !important;

        width: 25% !important;

    }

    .right-col{
        margin-top: 10px;

        float: left !important;

        width: 75% !important;

    }
    #balance_due{
        font-weight: bold;
        font-size: 24px;
    }


    @media only screen and (max-width: 1367px) and (min-width: 678px)  {

        .form-group {

            margin-bottom: 0 !important;

        }
    }

    @media only screen and (max-width: 780px) and (min-width: 750px)  {



        .left-col{
            margin-top: 10px;

            float: left !important;

            width: 52% !important;

        }

        .right-col{
            margin-top: 10px;

            float: left !important;

            width: 48% !important;

        }

    }

    @media only screen and (max-width: 1290px) and (min-width: 1230px)  {

        .left-col{
            margin-top: 10px;

            float: left !important;

            width: 28% !important;

        }

        .right-col{
            margin-top: 10px;

            float: left !important;

            width: 70% !important;

        }

    }

    @media only screen and (max-width: 1030px) and (min-width: 1020px)  {



        .left-col{
            margin-top: 10px;

            float: left !important;

            width: 52% !important;

        }

        .right-col{
            margin-top: 10px;

            float: left !important;

            width: 48% !important;

        }

    }

    @media only screen and (max-width: 678px) and (min-width: 0px)  {

        label {

            font-size: 12px;

            display: inline-block;

            margin-bottom: .5rem;

        }

        .small-row{

            padding-left: 0px !important;

            padding-right: 0px !important;

        }

        .col-small{

            padding-left: 0px !important;

            padding-right: 0px !important;

        }

        .left-col{
            margin-top: 10px;

            float: left !important;

            width: 40% !important;

        }

        .right-col{
            margin-top: 10px;

            float: left !important;

            width: 60% !important;

        }

        footer.sticky-footer {

            display: -webkit-box;

            display: -ms-flexbox;

            display: flex;

            position: fixed !important;

            right: 0;

            bottom: 0;

            width: calc(100% - 90px);

            height: 60px;

            background-color: #e9ecef;

        }





        .new-fonts{

            font-size: 16px !important;

            font-weight: 600 !important;

        }



        .new-header{

            margin-top: 10px !important;

            width: 50% !important;

            float: left !important;

        }

        .new-header1{



            width: 50% !important;

            float: left !important;

        }

        .new-col-header{

            padding-left: 0px !important;

            padding-right: 0px !important;

        }

    }

    .col-small{

        margin-top: 10px;

    }

    .top-col {

        margin-bottom: 1rem !important;
        clear: both;
    }

</style>

<div id="content-wrapper">



    <div class="container-fluid">

        <?php $status = $_SESSION['status']; ?>

        <div class="row">

            <div class="col-md-12 new-col-header">

                <div class="col-md-8 new-header" style="float:left;"><h3 class="new-fonts"> Update Order </h3></div>

                <?php if ($status == '1') {
                    ?>

                    <div class="col-md-4 text-right new-header1" style="float:left;" ><a  onClick="return confirm('Are you sure you want to delete?')" href='Deleteorders.php?id=<?= $id ?>'class="btn btn-primary">Delete</a></div>

                <?php }; ?>

            </div>

        </div>

        <hr>



    </div> 



    <?php
    $sql = "SELECT * FROM ticket where isengaged=0";

    $result = mysqli_query($db, $sql);
    ?>

    <?php
    $theme_parks_query = "SELECT * FROM theme_parks where active = 1 ORDER BY code DESC";
    $theme_parks_result = mysqli_query($db, $theme_parks_query);
    $theme_parks = [];

    while ($theme_park = mysqli_fetch_assoc($theme_parks_result)) {
        array_push($theme_parks, array('id' => $theme_park['id'], 'name' => $theme_park['name'], 'code' => $theme_park['code']));
    }
    ?>

    <?php
    $parktype = "SELECT * FROM parktype";

    $parkresult = mysqli_query($db, $parktype);
    ?>



    <?php
    $parktypesvalue = "SELECT * FROM tickettypes ORDER BY ticket_name DESC";

    $parkresult1 = mysqli_query($db, $parktypesvalue);
    ?>



    <div class="container-fluid" style="display:flex;">

        <div class="col-md-6 ">

            <form action="Updateorders.php?id=<?= $id ?>" autocomplete='off' autocomplete='off' method="post">

                <div class="row small-row" >   



                    <div class="col-md-12 col-small">

                        <div class="col-md-12 top-col">

                            <div class="left-col">   

                                <label style="display: block;" for="fname">Customer *</label>

                            </div>

                            <div class="right-col"> 

                                <input type="text" class="typeahead form-control"  name="customer" id="costomer" value="<?= $user['customer'] ?>" aria-describedby="customer" placeholder="Customer *" readonly >

                                <input type="hidden" name="idHidden" value="<?= $user['customer_id'] ?>" id="idhide"></div>

                        </div>





                        <div class="col-md-12 top-col">

                            <div class="left-col"> 

                                <label style="display: block;" for="fname">Date Of Visit *</label>

                            </div>

                            <div class="right-col">



                                <?php $newDate = date("m/d/Y", strtotime($user['date_of_visit'])); ?>

                                <input type="text" required name="date_of_vist"  id="datepicker" class="form-control" value="<?= $newDate ?>">
                            </div>





                        </div>

                        <div class="col-md-12 top-col">

                            <div class="left-col">

                                <label style="display: block;" for="fname">Time *</label>
                            </div>







<!--  <input id="checkin"  name="time" width="100%" value="<?= $user['time'] ?>"/>-->   <div class="right-col">

                                <select   class="form-control" name="time">



                                    <option <?php
                                    if ($user['time'] == '8:00 AM') {
                                        echo "selected";
                                    }
                                    ?> value="8:00 AM">8:00 AM</option>

                                    <option <?php
                                    if ($user['time'] == '8:30 AM') {
                                        echo "selected";
                                    }
                                    ?>  value="8:30 AM">8:30 AM</option>

                                    <option <?php
                                    if ($user['time'] == '9:00 AM') {
                                        echo "selected";
                                    }
                                    ?>  value="9:00 AM">9:00 AM</option>

                                    <option <?php
                                    if ($user['time'] == '9:30 AM') {
                                        echo "selected";
                                    }
                                    ?>  value="9:30 AM">9:30 AM</option>

                                    <option <?php
                                    if ($user['time'] == '10:00 AM') {
                                        echo "selected";
                                    }
                                    ?>  value="10:00 AM">10:00 AM</option>

                                    <option <?php
                                    if ($user['time'] == '10:30 AM') {
                                        echo "selected";
                                    }
                                    ?>  value="10:30 AM">10:30 AM</option>

                                    <option <?php
                                    if ($user['time'] == '11:00 AM') {
                                        echo "selected";
                                    }
                                    ?>   value="11:00 AM">11:00 AM</option>

                                    <option <?php
                                    if ($user['time'] == '11:30 AM') {
                                        echo "selected";
                                    }
                                    ?>  value="11:30 AM">11:30 AM</option>

                                    <option <?php
                                    if ($user['time'] == '12:00 PM') {
                                        echo "selected";
                                    }
                                    ?>   value="12:00 PM">12:00 PM</option>

                                    <option <?php
                                    if ($user['time'] == '12:30 PM') {
                                        echo "selected";
                                    }
                                    ?>  value="12:30 PM">12:30 PM</option>

                                    <option <?php
                                    if ($user['time'] == '1:00 PM') {
                                        echo "selected";
                                    }
                                    ?>  value="1:00 PM">1:00 PM</option>





                                </select>
                            </div>



                        </div>



                        <div class="col-md-12 top-col">

                            <div class="left-col"> 

                                <label style="display: block;" for="fname">Adults *</label>
                            </div>



                            <div class="right-col">

                                <input type="text" class="form-control" onkeypress="return AllowNumbersOnly(event)" name="adults"  id="adults" aria-describedby="adults" onkeyup="total_value()"  placeholder="Adults *" value="<?= $user['adults'] ?>">
                            </div>



                        </div>

                    </div>



                    <div class="col-md-12 col-small" >

                        <div class="col-md-12 top-col">

                            <div class="left-col">

                                <label style="display: block;" for="fname">Kids</label></div>



                            <div class="right-col">

                                <input type="text" class="form-control" onkeypress="return AllowNumbersOnly(event)" name="kids"  id="random" aria-describedby="Kids" onkeyup="total_value()" placeholder="Kids" value="<?= $user['kids'] ?>"></div>

                        </div> 



                        <div class="col-md-12 top-col">
                            <div class="left-col"> 

                                <label for="fname">Theme Park *</label>
                            </div>
                            <div class="right-col">
                                <select   class="my-form" id="theme_park" name="theme_park_id" onchange="change_theme_park()">
                                    <?php
                                    foreach ($theme_parks as $theme_park) {
                                        ?>

                                        <option <?php
                                        if ($user['theme_park_id'] == $theme_park['id']) {
                                            echo "selected";
                                        }
                                        ?> value="<?= $theme_park['id'] ?>"><?= $theme_park['name'] . " (" . $theme_park['code'] . ")" ?></option>

                                        <?php
                                    }
                                    ?>   
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 top-col">

                            <div class="left-col">  

                                <label style="display: block;" for="fname">Ticket Type *</label></div>



                            <div class="right-col">

                                <select   class="my-form" id="changeTicket" name="ticket_type" onchange="ticket(this.value)">

                                    <option value="0">Please Select Ticket Type..</option>

                                </select>

                            </div>



                        </div>
                        <div class="col-md-12 top-col"> 
                            <div class="left-col">
                                <label style="display: block;" for="deposit">Deposit</label>
                            </div>
                            <div class="right-col form-group d-flex">
                                <select name="gateway" id="gateway" class="form-control">
                                    <option value="" selected> Select Payment Method </option>
                                    <option value="Cash" <?php if (isset($user['gateway']) && $user['gateway'] === 'Cash') echo 'selected'; ?>>Cash </option>
                                    <option value="Website" <?php if (isset($user['gateway']) && $user['gateway'] === 'Website') echo 'selected'; ?>>Website</option>
                                    <option value="Venmo" <?php if (isset($user['gateway']) && $user['gateway'] === 'Venmo') echo 'selected'; ?>>Venmo</option>
                                    <option value="Zelle" <?php if (isset($user['gateway']) && $user['gateway'] === 'Zelle') echo 'selected'; ?>>Zelle</option>
                                    <option value="Cashapp" <?php if (isset($user['gateway']) && $user['gateway'] === 'Cashapp') echo 'selected'; ?>>Cashapp</option>
                                    <option value="Paypal" <?php if (isset($user['gateway']) && $user['gateway'] === 'Paypal') echo 'selected'; ?>>Paypal</option>
                                </select>
                                
                                <input type="text" class="form-control" onkeypress="return AllowNumbersOnly(event)" onblur="total_value()"  name="deposit" id="deposit" value="<?php echo $user['deposit'] ?? 0; ?>">
                            </div>
                        </div>


                        <div class="col-md-12 top-col">

                            <div class="left-col">

                                <label style="display: block;" for="fname">Discount *</label></div>



                            <div class="right-col">

                                <input type="text" class="form-control"  name="discount" id="discount" onblur="total_value()"  aria-describedby="total" value="<?= $user['discount'] ?>" placeholder="Discount *"  ></div>



                        </div>

                        <div class="col-md-12 top-col"> 

                            <div class="left-col">

                                <label style="display: block;" for="fname">Total Orders *</label></div>

                            <div class="right-col">



                                <input type="number" class="form-control" onkeypress="return AllowNumbersOnly(event)"  name="total" id="total" aria-describedby="total" value="<?= $user['total'] ?>" placeholder="Total Orders *" readonly></div>



                        </div>
                    <div class="col-md-12 top-col">

                        <div class="left-col">
                            <label for="balance_due">Balance Due</label>
                        </div>

                        <div class="right-col">
                            <span id="balance_due">$0</span>
                            <!--<input type="number" class="form-control" name="balance_due" id="balance_due" aria-describedby="balance_due" value='0' placeholder="Balance Due" readonly>-->
                        </div>

                    </div>




                    </div>

                    <div class="col-md-12">
                        <div class="left-col">
                        </div>
                        <?php if ((int) $level >= 8): ?>
                            <div class="right-col">
                                <input type="checkbox" class="form-check-inline" name="hideorder" id="hidefor" aria-describedby="hide" <?= $user['is_hide'] ? 'checked' : ''; ?>>
                                <label for="hidefor">Hide for other users.</label>
                            </div>
                        <?php endif; ?>
                    </div>





                    <div class="col-md-12 top-col">   



                        <input type="hidden" class="form-control" onkeyup="total_value()"  name="cost" id="cost" aria-describedby="cost" placeholder="Cost *" value="<?= $user['price'] ?>" >

                    </div>



                    <div class="col-md-12">   



                        <input type="hidden" class="form-control" name="child_price" id="child_price"  aria-describedby="fname" readonly="true">

                    </div>







                    <div class="col-md-12 top-col">



                        <label for="type"></label>





                        <select  hidden class="form-control" name="option">



                            <option <?php
                            if ($user['option'] == '1 day ptp') {
                                echo "selected";
                            }
                            ?> value="1 day ptp">1 day ptp</option>

                            <option <?php
                            if ($user['option'] == '1 day base(us)') {
                                echo "selected";
                            }
                            ?> value="1 day base(us)">1 day base(us)</option>

                            <option<?php
                            if ($user['option'] == '1 day base(ios)') {
                                echo "selected";
                            }
                            ?>  value="1 day base(ios)">1 day base(ios)</option>



                        </select>  



                    </div>

                </div>

                <div class="col-md-12 text-center small-btn">



                    <div class="form-group" >

                        <button type="submit"  name="updateorder"class="btn btn-primary" style="

                                margin-top: -51px;">Submit</button>

                    </div>

                </div>

            </form>

        </div>

    </div>



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



        </div>

    </div>





    <!-- Bootstrap core JavaScript-->



    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="/resources/demos/style.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>





     <!-- <script src="../js/sb-admin.min.js"></script>-->

</script>

<script>

                            $(function () {

                                $("#datepicker").datepicker();

                            });

</script>







<script>

    ticketTypes = [];

<?php
while ($parkvalue1 = mysqli_fetch_assoc($parkresult1)) {
    $ticket_type = $parkvalue1['ticket_name'];
    $adult_price = $parkvalue1['adult_price'];
    $ticketToShow = $ticket_type . ' $' . $adult_price;
    ?>
        ticketTypes.push({
            id: <?= $parkvalue1['id'] ?>,
            ticket_name: "<?= $parkvalue1['ticket_name'] ?>",
            adult_price: <?= $parkvalue1['adult_price'] ?>,
            ticketToShow: "<?php echo $ticket_type . ' $' . $adult_price; ?>",
            theme_park_id: "<?= $parkvalue1['theme_park_id'] ?>"
        });
    <?php
}
?>

    console.log('ticket types');
    console.log(ticketTypes);

    function change_theme_park(init = false) {
        var theme_park_id = $('#theme_park').val();

        console.log(theme_park_id);

        var selectedTicketTypes = ticketTypes.filter(ticket_type => ticket_type.theme_park_id == theme_park_id);
        console.log(selectedTicketTypes);

        $('#changeTicket').html('<option value="0">Please Select Ticket Type..</option>');
        selectedTicketTypes.forEach(ticket_type => {
            $('#changeTicket').append(`
    <option value="${ticket_type.ticketToShow}">${ticket_type.ticketToShow}</option>
    `)
        })

        if (init) {
            $('#changeTicket').val("<?php echo $user['ticket_type']; ?>");
        } else {
            $('#changeTicket').val('0');
            ticket('0');
    }



    }

    function ticket(id) {

        var ticket_name = id;

        var ticket = ticket_name.split(' ');

        var ticket1 = ticket.pop();

        var final_ticket2 = ticket.toString();

        var final_ticket = final_ticket2.replace(/\,/g, " ");
        
        var getprice = ticket1.split('$');
            var getprice_new = getprice.pop();

        $.ajax({

            type: 'POST',

            url: '../Ajax/Getorder.php',

            async: true,
            
            data:{final_ticket:final_ticket,getprice_new:getprice_new},

            success: function (data) {

                console.log(data);

                if (data == "No")

                {





                    $('#cost').val("");

//$('#numberofdays').val("");

                } else

                {





                    let dataAll = JSON.parse(data);

                    console.log(dataAll.adult_price);

                    var adults = $("#adults").val();

                    var random = $("#random").val()

                    var discount = $("#discount").val();

                    var total = parseInt(dataAll.adult_price) * parseInt(adults) + parseInt(dataAll.child_price) * parseInt(random) - discount;

                    $("#total").val(total);

                    $('#cost').val(dataAll.adult_price);

                    $('#child_price').val(dataAll.child_price);
                    calculate_due();


                }

            }

        })
    
    }
    function calculate_due() {
        /*
         * balance due should be adults + kids *(multiply) that by ticket type, then subtract discount equals ORDER TOTAL then  
         * subtract deposit, equals BALANCE DUE. 
         * This same information will also be added to the ORDERS page where appropriate
         */
        var adults = $("#adults").val();    //adults
        var random = $("#random").val();   //kids
        var discount = $("#discount").val();
        var ticket_type = $('#cost').val();
        var deposit = $('#deposit').val();
        var total_person = parseInt(adults) + parseInt(random);
        console.log("total_person",total_person);
        var ticket_rate = total_person * ticket_type;
        console.log("ticket_rate",ticket_rate);
        var due_total = (ticket_rate) - discount - deposit;
        $('#balance_due').html("$"+due_total);
    }
    change_theme_park(true);
    calculate_due();
</script>

<script>

    function total_value()

    {

        var num1 = document.getElementById('adults').value;

        //console.log(num1);

        var num2 = document.getElementById('random').value;



        var num3 = document.getElementById('cost').value;





        var child_price = document.getElementById('child_price').value;

        var discount = document.getElementById('discount').value;

        var total_value = document.getElementById('total').value;

        var total_value = '<?php echo $user['total']; ?>';

        /* console.log(total_value);
         
         */

        if (discount == '')

        {

            discount = 0;

            $("#total").val(total_value);

        }



        /*else if(num2 && child_price)
         
         {
         
         var data2 = parseInt(num2);
         
         var child_cost = $("#child_price").val();
         
         
         
         var totalPrice= data2*child_cost-discount;
         
         $("#total").val(totalPrice);
         
         }
         
         else if(num1 && num3)
         
         {
         
         var data2 = parseInt(num1);
         
         var price = $("#cost").val();
         
         
         
         var totalPrice= data2*price-discount;
         
         $("#total").val(totalPrice); 
         
         
         
         }*/

        if (num1 && num3 && num2)

        {





            var totalPrice = parseInt(num1) * parseInt(num3) + parseInt(num2) * parseInt(num3) - discount;



            $("#total").val(totalPrice);



        } else if (num1 && num2)

        {

            //console.log(num2);

            var data2 = parseInt(num1);

            var data3 = parseInt(num2);

            var data4 = parseInt(num3);

            var price = $("#cost").val();

            var child_cost = $("#child_price").val();

            console.log(child_cost);

            var totalPrice = data2 * price + data3 * child_cost - discount;

            $("#total").val(totalPrice);



        } else if (num3 && num2)

        {





            var totalPrice = num2 * num3;

            $("#total").val(totalPrice);



        } else if (num1 && num3)

        {

            /* var data2 = parseInt(num1);
             
             var data3=parseInt(num2);
             
             var data4=parseInt(num3);
             
             //var price = $("#cost").val();*/



            var totalPrice = num1 * num3;

            $("#total").val(totalPrice);



        } else if (total_value && discount)

        {

            var totalPrice = total_value - discount;

            $("#total").val(totalPrice);

        }







calculate_due();

    }

    function AllowNumbersOnly(e) {

        var code = (e.which) ? e.which : e.keyCode;

        if (code > 31 && (code < 48 || code > 57)) {

            e.preventDefault();

        }

    }



</script>















</body>



</html>

