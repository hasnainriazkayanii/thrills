<?php

include('../Config/Connection.php');

session_start();

$login_check = $_SESSION['id'];

$level = $_SESSION['level'];


if ($login_check != '1') {
    $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}


include('../includes/header.php');



?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">


<style>

    @media only screen and (max-width: 678px) and (min-width: 0px) {

        .new-header {

            margin-top: 10px !important;

            width: 50% !important;

            float: left !important;

        }

        .new-fonts {

            font-size: 14px !important;

        }

        .new-header {

            padding-left: 0px;

            padding-right: 0px;

            margin-top: 10px !important;

            width: 50% !important;

            float: left !important;

        }

    }
</style>

<!-- Custom scripts for table sorting -->

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

<div id="content-wrapper">



    <div class="container-fluid">

        <!-- DataTables Example -->

        <div class="row">

            <div class="col-md-12">

                <div class="col-md-8 new-header" style="float:left;">
                    <h3 class="new-fonts">Payment History</h3>
                </div>

                <div class="col-md-4 text-right new-header" style="float:left;">

                    <a href="Orderdetails.php" class="btn btn-primary">Back</a>
                    <!-- <a href="../messages/compose_message.php" class="btn btn-secondary">Send Message</a>  -->
                </div>

            </div>

        </div>

        <hr>
        <!-- notification -->


                    <table id="datatable_example" class="display" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                    </table>

        </div>




    <!-- /.container-fluid -->
</div>

<!-- Sticky Footer -->

<!-- /.content-wrapper -->
</div>

</div>

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    table = $('#datatable_example').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "type": "GET",
            "url": '../queries/fetch_payment_history.php',
            "data": {
                "id": "<?php echo @$_GET['order_id'] ?>",
            }
        },
        // "ajax": "../queries/fetch_payment_history.php",
        //"data": {'id':'<?php //echo $_GET['order_id'] ?>//'},
        "columns": [
            { "data": 0},
            { "data": 1},
            { "data": 2},
        ],
    });

</script>

</body>

</html>