<?php
include('Config/Connection.php');
session_start();
$login_check = $_SESSION['id'];
$level = $_SESSION["level"] ?? 1;

//var_dump($data1);

if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location:Login/login.php");
}


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
  <!-- <meta http-equiv="refresh" content="5"> -->

  <meta name="author" content="">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />



  <title>Cheap Thrills Admin Portal</title>

    <link rel="icon" type="image/png" href="images/CT-favicon3.png" />

  <!-- Bootstrap core CSS-->

  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">



  <!-- Custom fonts for this template-->

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">



  <!-- Page level plugin CSS-->

  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">



  <!-- Custom styles for this template-->

  <link href="css/sb-admin.css" rel="stylesheet">


  <style type="text/css">
    .navbar-nav .nav-item .nav-link .badge {
      position: static;
      margin-left: 0 !important;
      top: 0px;
      font-weight: 400;
      font-size: 0.6rem;
    }
  </style>

</head>



<body id="page-top">







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

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">

          <a class="dropdown-item" href="Login/logout.php">Logout</a>

        </div>

      </li>

    </ul>



  </nav>



  <div id="wrapper">



    <!-- Sidebar -->

    <ul class="sidebar navbar-nav">

      <li class="nav-item" style="cursor: pointer;">

        <a class="nav-link" href="">

          <i class="fas fa-fw fa-tachometer-alt"></i>

          <span>Dashboard</span>

        </a>

      </li>



      <li class="nav-item" style="cursor: pointer;">

        <a class="nav-link" href="Customers/CustomersDetails.php">

          <i class="fas fa-fw fa-folder"></i>

          <span>Customers</span>
          <?php if ($new_messages_count) : ?>
            <span class="badge badge-pill badge-warning"><?php echo $new_messages_count; ?></span>
          <?php endif; ?>

        </a>

      </li>

      <li class="nav-item dropdown" style="cursor: pointer;">

        <a class="nav-link" href="Orders/Orderdetails.php?active=0">

          <i class="fas fa-fw fa-folder"></i>

          <span>Orders</span>

        </a>



      </li>
      
      
      <!-- Insert temporary link for ticket usage and maps-->
      
      <?php if($level >=2): //start if ====0 ?>
      <li class="nav-item" style="cursor: pointer;">

        <a class="nav-link" href="History/History.php">

          <i class="fas fa-fw fa-folder"></i>

          <span>Ticket Usage</span>

        </a>
      </li>
      
      
       <li class="nav-item dropdown" style="cursor: pointer;">

        <a class="nav-link" href="Map/Location.php">

          <i class="fas fa-fw fa-folder"></i>

          <span>Map</span>

        </a>
      </li>
      <?php endif;/// end if ===0 ?>
      
      <!--End Insert Ticket Usage and maps-->
      
      
      
      
      

      <?php if($level >= 8): //start if ====1 ?>
                
        <!-- Report Section -->
        <li class="nav-item" style="cursor: pointer;">
          <a class="nav-link" href="Report/Report.php">
            <i class="fas fa-fw fa-file-download"></i>
            <span>Sales Report</span>
          </a>
        </li>
        <!-- // End Report Section -->
        
      <li class="nav-item" style="cursor: pointer;">

        <a class="nav-link" href="Ticket/DetailsTicket.php?active=0">

          <i class="fas fa-fw fa-folder"></i>

          <span>Tickets</span>

        </a>

      </li>




      <!--        <li class="nav-item dropdown" style="cursor: pointer;">

          <a class="nav-link" href="History/History.php">

            <i class="fas fa-fw fa-folder"></i>

            <span>Ticket Usage</span>

          </a>

        

        </li> -->

     

      <!--       <li class="nav-item dropdown" style="cursor: pointer;">

          <a class="nav-link" href="messages/index.php">

            <i class="fas fa-fw fa-folder"></i>

            <span>Messages</span>

            <?php if ($new_messages_count) : ?>
              <span class="badge badge-pill badge-warning"><?php echo $new_messages_count; ?></span>
            <?php endif; ?>
            
          </a>
        </li> -->



      <li class="nav-item dropdown" style="cursor: pointer;">

        <a class="nav-link" href="Settings/SettingDetails.php">

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



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>



    <!-- Core plugin JavaScript-->

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>



    <!-- Custom scripts for all pages-->

    <script src="js/sb-admin.min.js"></script>









    <!-- Page level plugin JavaScript-->

    <script src="vendor/datatables/jquery.dataTables.js"></script>

    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>



    <!-- Custom scripts for all pages-->

    <script src="js/sb-admin.min.js"></script>



    <!-- Demo scripts for this page-->



    <script>
      $.validate({

        lang: 'es'

      });
    </script>

</body>

</html>
<?php
$ishide = '';
if($level < 8){
 $ishide = "AND order.is_hide=0";
}

$current_date = date("Ymd");

// Fetch upcoming orders
$sql = "SELECT *, order.id as order_id, order.order_id as order_code, theme_parks.id as theme_park_id, theme_parks.name AS park_name, customer.Phone_number FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id INNER JOIN `customer` ON order.customer_id=customer.id WHERE order.date_of_visit>=$current_date $ishide AND order.status<3 ORDER BY order.date_of_visit ASC,STR_TO_DATE(time, '%l:%i %p')";
$result = mysqli_query($db, $sql);
$upcoming_orders_unsorted = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Format orders by date
$src_tz = new DateTimeZone('America/Chicago');
$dest_tz = new DateTimeZone('America/New_York');
$upcoming_orders = array();
foreach ($upcoming_orders_unsorted as $order) {
  $today = new DateTimeImmutable(date("Y-m-d H:i:s"), $src_tz);
  $date_of_visit = new DateTimeImmutable($order['date_of_visit'] . ' ' . $order['time'], $src_tz);

  $today = $today->setTimezone($dest_tz);
  $date_of_visit = $date_of_visit->setTimezone($dest_tz);

  $diff = $today->diff($date_of_visit);

  if ((int) $diff->days === 0) {
    $upcoming_orders['TODAY'][] = $order;
  } else if ((int) $diff->days === 1) {
    $upcoming_orders['TOMORROW'][] = $order;
  }
  // else
  //   $upcoming_orders['In '.$diff->days.' days'][] = $order;
}


// Fetch orders that needs ticket to be assigned
$today = new DateTimeImmutable();
$tomorrow = $today->modify('+1 day');
$tomorrow = $tomorrow->format('Ymd');
$sql = "SELECT *, order.id as order_id, order.order_id as login_id, theme_parks.id as theme_park_id, theme_parks.name AS park_name FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id WHERE order.assign=0 $ishide AND order.date_of_visit>=$current_date AND order.date_of_visit<=$tomorrow AND order.status<3 ORDER BY order.date_of_visit";
$result = mysqli_query($db, $sql);
$unassigned_orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($unassigned_orders as $key => $order) {
  $unassigned_orders[$key]['need_assign'] = true;

  // Check if screenshot has been taken
  $sql = "SELECT * FROM `screenshot` WHERE login_id='{$order['login_id']}'";
  $result = mysqli_query($db, $sql);
  $screenshot = mysqli_fetch_assoc($result);
  if ($screenshot)
    $unassigned_orders[$key]['screenshot_taken'] = true;
  else
    $unassigned_orders[$key]['screenshot_taken'] = false;
}


$sql = "SELECT *, order.id as order_id, order.order_id as login_id, theme_parks.id as theme_park_id, theme_parks.name AS park_name FROM `order` INNER JOIN `theme_parks` ON order.theme_park_id=theme_parks.id WHERE order.id NOT IN (SELECT order_id FROM `history`) AND order.assign=1 AND order.date_of_visit=$current_date ORDER BY order.date_of_visit";
$result = mysqli_query($db, $sql);
$UpcomingOrders=mysqli_num_rows($result);
$unupdated_history_orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($unupdated_history_orders as $key => $order) {
  $unupdated_history_orders[$key]['need_history_update'] = true;

  // Check if screenshot has been taken
  $sql = "SELECT * FROM `screenshot` WHERE login_id='{$order['login_id']}'";
  $result = mysqli_query($db, $sql);
  $screenshot = mysqli_fetch_assoc($result);
  if ($screenshot)
    $unupdated_history_orders[$key]['screenshot_taken'] = true;
  else
    $unupdated_history_orders[$key]['screenshot_taken'] = false;
}

$need_attention_orders = array_merge($unassigned_orders, $unupdated_history_orders);


// Get New messages
$sql = "SELECT * FROM `massages` WHERE type='recieved' AND seen=0 AND contact_no IN (SELECT Phone_number FROM `customer`) GROUP BY contact_no ORDER BY created_at DESC";
$result = mysqli_query($db, $sql);
$new_messages = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($new_messages as &$message) {
  $sql = "SELECT id, first_name, Last_name FROM `customer` WHERE Phone_number={$message['contact_no']}";
  $result = mysqli_query($db, $sql);
  $customer = mysqli_fetch_assoc($result);
  $message['name'] = $customer['first_name'] . ' ' . $customer['Last_name'];
  $message['member_id'] = $customer['id'];
}

// echo '<pre>';
// print_r($new_messages);
// exit();
?>

<div class="container-fluid pt-4">
  <div class="row">
    <div class="col-sm-4 col-lg-8 col-xl-6">
      <!-- Upcoming Orders -->
      <section class="upcoming-orders" style="margin-bottom: 3rem;">
        <div>
          <!-- For Pc and tablets -->
          <?php

            if(count($upcoming_orders) > 0){
          ?>
          <h3 class="font-weight-bold pl-4 mb-4 d-none d-sm-block" style="font-size:1.5rem">
            <img src="images/icon-calendar.jpg" alt="Upcoming Orders" style="width: 36px; margin-top: -9px;margin-right:10px">Upcoming Orders
          </h3>
          <?php }else{
            echo '<h3 class="font-weight-bold pl-4 mb-4 d-none d-sm-block" style="font-size:1.5rem">No Current Orders</h3>';
          } ?>
          <!-- For mobile -->
          <?php
            if(count($upcoming_orders) > 0){
          ?>
          <h6 class="font-weight-bold mb-4 d-block d-sm-none">
            <img src="images/icon-calendar.jpg" alt="Upcoming Orders" style="width: 40px; margin-top: -9px;">
            <span style="margin-left:7px">
              Upcoming Orders
            </span>
          </h6>
          
          <?php }else{
            echo `<h6 class="font-weight-bold mb-4 d-block d-sm-none">
            <span style="margin-left:7px">
            No Current Orders
            </span>
          </h6>`;
          } ?>
          <?php $counter = 1; ?>
          <?php foreach ($upcoming_orders as $title => $orders) { ?>
            <?php if ($counter === 3) break; ?>
            <div class="mb-4">
              <div class="row">
                <div class="col-sm-2 d-none d-sm-block"></div>
                <div class="col-sm-6">
                  <h4 class="font-weight-bold mb-3"><?php echo $title; ?></h4>
                </div>
                <div class="col-sm-4 d-none d-sm-block"></div>
              </div>
              <?php foreach ($orders as $order) {

                  if($order['is_hide'] == 1 && $_SESSION['level'] != 9){
                      continue;
                  }


                  ?>
                <div class="mb-4 d-none d-sm-block">
                  <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-4"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-2 text-center">
                      <?php if (preg_match('/\bUniversal\b/i', $order['park_name'])) : ?>
                        <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-3px">
                      <?php elseif (preg_match('/\bSeaWorld\b/i', $order['park_name'])) : ?>
                        <img src="images/seaworld-logo-small.png" alt="" style="width: 64px;margin-top:-3px">
                      <?php elseif (preg_match('/\bIslands\b/i', $order['park_name'])) : ?>
                        <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-3px">
                      <?php endif; ?>
                    </div>
                    <div class="col-sm-6" style="font-size:15px;">
                      <div style="font-weight:500; margin-top:-12px;">
                        <a href="/Customers/UpdateCustomer.php?id=<?php echo $order['customer_id']; ?>" style="color:black; text-decoration:none;"><b><?php echo $order['customer']; ?></b></a> <?php echo $order['adults']; ?> adults / <?php echo $order['kids'] ?? 0; ?> kids<br>
                        <?php echo $order['park_name']; ?> -
                        <?php if ($title === 'TODAY' || $title === 'TOMORROW') : ?>
                          <?php echo $order['time']; ?>
                        <?php else : ?>
                          <?php echo date('M j', strtotime($order['date_of_visit'])); ?>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <?php if ($title === "TODAY") : ?>
                        <?php if ((int) $level >= 8): ?>
                        <a href="tel:+1<?php echo $order['Phone_number']; ?>" class="btn btn-primary btn-sm mr-3" role="btn">Call</a>
                        <?php endif; ?>
                        <a href="/Orders/text_history.php?order=<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm" role="btn">Text Usage</a>
                      <?php elseif ($title === "TOMORROW") : ?>
                        <a href="/Orders/text_order_details.php?order_id=<?php echo $order['order_code']; ?>" class="btn btn-success btn-sm" role="btn">Send Confirmation Text</a>
                      <?php else : ?>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-4"></div>
                  </div>
                </div>

                <!-- For Mobile -->
                <div class="mb-4 d-block d-sm-none">
                  <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-4"></div>
                  </div>
                  <div class="row">
                    <div class="col-2 col-sm-2 text-center">
                      <?php if (preg_match('/\bUniversal\b/i', $order['park_name'])) : ?>
                        <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-3px;margin-left:-10px;">
                      <?php elseif (preg_match('/\bSeaWorld\b/i', $order['park_name'])) : ?>
                        <img src="images/seaworld-logo-small.png" alt="" style="width: 64px;margin-top:-3px;margin-left:-10px;">
                      <?php elseif (preg_match('/\bIslands\b/i', $order['park_name'])) : ?>
                        <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-3px;margin-left:-10px;">
                      <?php endif; ?>
                    </div>
                    <div class="col-10 col-sm-6 pl-4" style="font-size:15px;">
                      <div style="font-weight:500; margin-top:-12px;">
                        <a href="/Customers/UpdateCustomer.php?id=<?php echo $order['customer_id']; ?>" style="color:black; text-decoration:none;"><?php echo $order['customer']; ?></a> <?php echo $order['adults']; ?> adults / <?php $order['kids'] ?? 0; ?> kids<br>
                        <?php echo $order['park_name']; ?> -
                        <?php if ($title === 'TODAY' || $title === 'TOMORROW') : ?>
                          <?php echo $order['time']; ?>
                        <?php else : ?>
                          <?php echo date('M j', strtotime($order['date_of_visit'])); ?>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-10 col-sm-4 pl-4 mt-2">
                      <?php if ($title === "TODAY") : ?>
                        <a href="tel:+1<?php echo $order['Phone_number']; ?>" class="btn btn-primary btn-sm mr-3" role="btn">Call</a>
                        <a href="/Orders/text_history.php?order=<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm" role="btn">Text Usage</a>
                      <?php elseif ($title === "TOMORROW") : ?>
                        <a href="/Orders/text_order_details.php?order_id=<?php echo $order['order_code']; ?>" class="btn btn-success btn-sm" role="btn">Send Confirmation Text</a>
                      <?php else : ?>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-4"></div>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php ++$counter;
          } ?>
        </div>
      </section>
    </div>

    <div class="col-sm-4 col-lg-8 col-xl-6">
      <!-- New messages -->
      <section class="upcoming-orders">
        <div>
          <!-- For pc -->
          <h2 class="font-weight-bold pl-4 mb-4 d-flex" style="font-size:1.6rem;"><img src="images/message_icon.png" alt="Upcoming Orders" style="width: 36px; margin-top: -4px;margin-right:5px"><div class="d-none d-sm-block">New Messages</div></h2>
        </div>
        <div class="row">
          <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <div class="pl-4 w-100">
              <?php $counter = 1; ?>
              <?php foreach ($new_messages as $msg) { ?>
                <div class="mb-4 d-none d-sm-block" style="<?php if ($counter > 10) echo 'display:none !important;' ?>">
                  <div class="d-flex justify-content-between">
                    <div>
                      From <b><?php echo $msg['name']; ?></b> at <b><?php echo date('g:i a', strtotime($msg['created_at'])); ?></b>
                    </div>
                    <div>
                      <a href="messages/chat.php?member_id=<?php echo $msg['member_id']; ?>" class="btn btn-primary btn-sm">View Messages</a>
                    </div>
                  </div>
                </div>
              <?php ++$counter;
              } ?>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-4 col-lg-8 col-xl-6">
      <!-- Orders Need Attention -->
      <section class="upcoming-orders">
        <div>
          <!-- For pc and tablets -->
          <?php

            if(count($need_attention_orders)>0){
          ?>
          <h2 class="font-weight-bold pl-4 mb-4 d-none d-sm-block" style="font-size:1.6rem;"><img src="images/icon-alert.png" alt="Upcoming Orders" style="width: 36px; margin-top: -9px;margin-right:5px">Orders Need Attention</h2>
          <!-- For Mobile -->
          <h6 class="font-weight-bold mb-4 d-block d-sm-none">
            <img src="images/icon-alert.png" alt="Upcoming Orders" style="width: 50px; margin-top: -9px;">
            <span style="margin-left:7px">
              Orders Need Attention
            </span>
          </h6>
          <?php } ?>
          <!-- Orders that needs ticket assignment -->
          <div>
            <?php foreach ($need_attention_orders as $order) {

                if($order['is_hide'] == 1 && $_SESSION['level'] != 9){
                    continue;
                }


                ?>
              <!-- For PC and Tablets -->
              <div class="mb-4 d-none d-sm-block">
                <div class="row">
                  <div class="col-sm-2"></div>
                  <div class="col-sm-6">
                    <a href="/Customers/UpdateCustomer.php?id=<?php echo $order['customer_id']; ?>" style="color:black; text-decoration:none;">
                      <div class="font-weight-bold" style="font-size:15px;"><?php echo $order['customer']; ?></div>
                    </a>
                  </div>
                  <div class="col-sm-4"></div>
                </div>
                <div class="row">
                  <div class="col-sm-2 text-center">
                    <?php if (preg_match('/\bUniversal\b/i', $order['park_name'])) : ?>
                      <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-3px;margin-left:-10px;">
                    <?php elseif (preg_match('/\bSeaWorld\b/i', $order['park_name'])) : ?>
                      <img src="images/seaworld-logo-small.png" alt="" style="width: 64px;margin-top:-3px;margin-left:-10px;">
                    <?php elseif (preg_match('/\bIslands\b/i', $order['park_name'])) : ?>
                      <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-3px;">
                    <?php endif; ?>
                  </div>
                  <div class="col-sm-6" style="font-size:15px;">
                    <div style="font-weight:500;"><?php echo $order['park_name']; ?> - <?php echo date('M j', strtotime($order['date_of_visit'])); ?></div>
                  </div>
                  <div class="col-sm-4">
                    <?php if (isset($order['need_assign'])) : ?>
                      <a href="/Assign/Addassign.php?id=<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm" role="button">Assign Ticket</a>
                    <?php else : ?>
                      <a href="/Orders/order_history.php?id=<?php echo $order['order_id']; ?>" class="btn btn-warning btn-sm" role="button">Update Usage</a>
                    <?php endif; ?>
                  </div>
                </div>
                <?php if ($order['screenshot_taken']) : ?>
                  <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6">
                      <div class="text-danger">Took Screenshot</div>
                    </div>
                    <div class="col-sm-4"></div>
                  </div>
                <?php endif; ?>
                <div class="row">
                  <div class="col-sm-2"></div>
                  <div class="col-sm-6" style="font-size:20px;">
                    <div class="text-danger" style="color:rgb(37, 150, 190); font-weight:500;">-Ticket Need Assigned</div>
                  </div>
                  <div class="col-sm-4"></div>
                </div>
              </div>

              <!-- For Mobile -->
              <div class="mb-4 d-block d-sm-none">
                <div class="row">
                  <div class="col-2 col-sm-2"></div>
                  <div class="col-10 col-sm-6 pl-4">
                    <a href="/Customers/UpdateCustomer.php?id=<?php echo $order['customer_id']; ?>" style="color:black; text-decoration:none;">
                      <div class="font-weight-bold" style="font-size:15px;">
                        <?php echo $order['customer']; ?></div>
                    </a>
                  </div>
                  <div class="col-sm-4"></div>
                </div>
                <div class="row">
                  <div class="col-2 col-sm-2 text-center">
                    <?php if (preg_match('/\bUniversal\b/i', $order['park_name'])) : ?>
                      <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-3px;margin-left: -10px;">
                    <?php elseif (preg_match('/\bSeaWorld\b/i', $order['park_name'])) : ?>
                      <img src="images/seaworld-logo-small.png" alt="" style="width: 64px;margin-top:-3px;margin-left: -10px;">
                    <?php elseif (preg_match('/\bIslands\b/i', $order['park_name'])) : ?>
                      <img src="images/universal-logo-small.png" alt="" style="width: 64px;margin-top:-30px;margin-left: -10px;">
                    <?php endif; ?>
                  </div>
                  <div class="col-10 col-sm-6 pl-4" style="font-size:15px;">
                    <div style="font-weight:500;"><?php echo $order['park_name']; ?> - <?php echo date('M j', strtotime($order['date_of_visit'])); ?></div>
                  </div>
                  <div class="col-2"></div>
                  <div class="col-10 col-sm-4 pl-4" style="font-size:15px;">
                    <div class="text-danger" style="color:rgb(37, 150, 190); font-weight:500;">-Ticket Need Assigned</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-2 col-sm-2"></div>
                  <div class="col-10 col-sm-6 pl-4 mt-2">
                    <?php if (isset($order['need_assign'])) : ?>
                      <a href="/Assign/Addassign.php?id=<?php echo $order['order_id']; ?>" class="btn btn-info btn-sm" role="button">Assign Ticket</a>
                    <?php else : ?>
                      <a href="/Orders/order_history.php?id=<?php echo $order['order_id']; ?>" class="btn btn-warning btn-sm" role="button">Update Usage</a>
                    <?php endif; ?>
                  </div>
                  <div class="col-sm-4"></div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
</body>

</html>