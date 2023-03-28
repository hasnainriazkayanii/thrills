<?php date_default_timezone_set("America/New_York"); ?>

<?php
include('../Config/Connection.php');

session_start();

$level = $_SESSION["level"] ?? 1;

// Get new messages count
$sql = "SELECT count(id) AS new_messages_count FROM `massages` WHERE seen=0 AND type='recieved' AND contact_no IN (SELECT Phone_number FROM `customer`)";
$result = mysqli_query($db, $sql);
$new_messages_count = (int) mysqli_fetch_assoc($result)['new_messages_count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="description" content="">

  <meta name="author" content="">



  <title>Cheap Thrills Admin Portal</title>

  <link rel="icon" href="../Images/favicon.jpg" type="../Images/favicon.jpg">
    <link rel="icon" type="image/png" href="../images/CT-favicon3.png" />

  <!-- Bootstrap core CSS-->

  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">



  <!-- Custom fonts for this template-->

  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">



  <!-- Page level plugin CSS-->

  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">



  <!-- Custom styles for this template-->

  <link href="../css/sb-admin.css" rel="stylesheet">

  <style type="text/css">
    .navbar-nav .nav-item .nav-link .badge {
      position: static;
      margin-left: 0 !important;
      top: 0px;
      font-weight: 400;
      font-size: 0.6rem;
    }
    input.typeahead.form-control {
    text-transform: capitalize;
}
  </style>

<link rel="stylesheet" href="../build/css/intlTelInput.css">
<style>
    option:disabled {
    color: #cbcbcb;
}
    
</style>
</head>



<body id="page-top">

  <!--    ======================copy from here    start  -->




<?php if (!isset($hide_navbar) || $hide_navbar !== true): ?>
  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">



    <a class="navbar-brand mr-1" href="">Cheap Thrills Admin</a>



    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">

      <i class="fas fa-bars"></i>

    </button>



    <!-- Navbar Search -->

    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">

      <div class="input-group">



      </div>

      </div>

    </form>



    <!-- Navbar -->

    <ul class="navbar-nav ml-auto ml-md-0">



      <li class="nav-item dropdown no-arrow">

        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

          <i class="fas fa-user-circle fa-fw"></i>

        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="background-color: #fff;">
          <a style="    padding: 0.25rem 3.5rem; color:#000;" class="dropdown-item" href="../Login/logout.php">Logout</a>

        </div>

      </li>

    </ul>



  </nav>



  <div id="wrapper">



    <!-- Sidebar -->

    <ul class="sidebar navbar-nav">

      <li class="nav-item" style="cursor: pointer;">

        <a class="nav-link" href="../index.php">

          <i class="fas fa-fw fa-tachometer-alt"></i>

          <span>Dashboard</span>

        </a>

      </li>

      <li class="nav-item" style="cursor: pointer;">

        <a class="nav-link" href="../Customers/CustomersDetails.php">

          <i class="fas fa-fw fa-folder"></i>

          <span>Customers</span>
          <?php if ($new_messages_count) : ?>
            <span class="badge badge-pill badge-warning"><?php echo $new_messages_count; ?></span>
          <?php endif; ?>
        </a>

      </li>
      
       <li class="nav-item" style="cursor: pointer;">
        <a class="nav-link" href="../Orders/Orderdetails.php">
          <i class="fas fa-fw fa-folder"></i>
          <span>Orders</span>
        </a>
      </li>
      
      <!--/**-->
      <!--ticket usage permission level 2-salesmanager-->
      
      <!--***/-->
    <?php if($level >=2): //start if ====0 ?>
      <li class="nav-item" style="cursor: pointer;">
        <a class="nav-link" href="../History/History.php">
          <i class="fas fa-fw fa-folder"></i>
          <span>Ticket Usage</span>
        </a>
      </li>

      <!--<li class="nav-item" style="cursor: pointer;">-->
      <!--  <a class="nav-link" href="../Orders/Orderdetails.php?active=0">-->
      <!--    <i class="fas fa-fw fa-folder"></i>-->
      <!--    <span>Orders Original</span>-->
      <!--  </a>-->
      <!--</li>-->
      
        <li class="nav-item" style="cursor: pointer;">
        <a class="nav-link" href="../Map/Location.php">
          <i class="fas fa-fw fa-folder"></i>
          <span>Map</span>
        </a>
      </li>
      <?php endif; //end if ====0 ?>
      
      
      <?php if($level >= 9): //start if ====1 ?>
        
        <!-- Report Section -->
        <li class="nav-item" style="cursor: pointer;">
          <a class="nav-link" href="../Report/Report.php">
            <i class="fas fa-fw fa-file-download"></i>
            <span>Sales Report</span>
          </a>
        </li>
        <!-- // End Report Section -->

      <li class="nav-item" style="cursor: pointer;">
        <a class="nav-link" href="../Ticket/DetailsTicket.php?active=0">
          <i class="fas fa-fw fa-folder"></i>
          <span>Tickets</span>
        </a>
      </li>




      <!--<li class="nav-item dropdown" style="cursor: pointer;">
          <a class="nav-link" href="../Menu/MenuDetails.php">
            <i class="fas fa-fw fa-folder"></i>
            <span>Menu</span>
          </a>
        </li>-->

      <li class="nav-item" style="cursor: pointer;">
        <a class="nav-link" href="../Settings/SettingDetails.php">
          <i class="fas fa-fw fa-folder"></i>
          <span>Settings</span>
        </a>
      </li>
      <?php endif; // end if =====1?>

    </ul>


    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

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

    </div>
    
  <?php endif; ?>







    <!--middle content start -->



    <!--middle content end -->



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>







    <!-- Bootstrap core JavaScript-->

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->

    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>



    <!-- Core plugin JavaScript-->

    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>



    <!-- Custom scripts for all pages-->

    <script src="../js/sb-admin.min.js"></script>









    <!-- Page level plugin JavaScript-->

    <?php if (isset($datatable) && $datatable !== false) : ?>

      <script src="../vendor/datatables/jquery.dataTables.js"></script>

      <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

    <?php endif; ?>


    <!-- Custom scripts for all pages-->

    <script src="../js/sb-admin.min.js"></script>



    <!-- Demo scripts for this page-->

    <?php if (!isset($datatable) || $datatable !== false) : ?>
      <script src="../js/demo/datatables-demo.js"></script>
    <?php endif; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>



    <script>
      $("#dataTable_filter").css('display', 'none');
    </script>









</body>



</html>