<?php

include('../Config/Connection.php');

session_start();

$login_check = $_SESSION['id'];

$level = $_SESSION['level'];


if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}


// If the request is outside of message system refresh the message system session
if (!isset($_REQUEST['manage_state']))
  unset($_SESSION['message_system']);

$message = "";
if (isset($_SESSION['message_system']['message'])) {
  $message = $_SESSION['message_system']['message'];
}


// Get Pre defined text messages
$sql_msg = "SELECT * FROM `text_messages`";
$result_msg = mysqli_query($db, $sql_msg);
$predefined_messages = mysqli_fetch_all($result_msg, MYSQLI_ASSOC);

$tbl_name = "customer ORDER BY (SELECT created_at FROM `massages` WHERE contact_no=customer.Phone_number AND seen=0 AND type='recieved' ORDER BY created_at DESC LIMIT 1) DESC,id DESC";

$targetpage = "../Customers/CustomersDetails.php";

if (isset($_POST['submit']))
  include('../includes/pagination.php');

include('../includes/header.php');
$sqlasc = "SELECT * FROM referral_types ";
$resultasc = mysqli_query($db, $sqlasc);
$dataasc = mysqli_fetch_all($resultasc, MYSQLI_ASSOC);

$sqlc = "SELECT * FROM customer where referral IS NOT NULL ";
$resultc = mysqli_query($db, $sqlc);
$datac = mysqli_fetch_all($resultc, MYSQLI_ASSOC);
$basediv=count($datac);

$xValues=array();
$yValues=array();
$cn="";
// $sqlf = "SELECT * FROM customer where referral IS NULL";
// $resultf = mysqli_query($db, $sqlf);
// $dataf = mysqli_fetch_all($resultf, MYSQLI_ASSOC);
// array_push($xValues,'Other'.'('.count($dataf).')');
//  $equ=number_format((float)(count($dataf)/$basediv)*100, 2, '.', '');
//     array_push($yValues,$equ);

foreach ($dataasc as $d){
    $equ=0;
    $ids=$d['id'];
    $sqlf = "SELECT * FROM customer where referral = '$ids'";
$resultf = mysqli_query($db, $sqlf);
$dataf = mysqli_fetch_all($resultf, MYSQLI_ASSOC);

    array_push($xValues,$d['name'].'('.count($dataf).')');
    
    $equ=number_format((float)(count($dataf)/$basediv)*100, 2, '.', '');
    array_push($yValues,$equ);
}

?>



<style>
  .dataTables_length {
    display: none !important;
  }

  .dataTables_filter {
    display: none;
  }

  .dataTables_info {
    display: none;
  }

  div.dataTables_wrapper div.dataTables_paginate {

    margin: 0;

    display: none;

    white-space: nowrap;

    text-align: right;

  }

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
          <h3 class="new-fonts">Customers</h3>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

        <div class="col-md-4 text-right new-header" style="float:left;">

        <?php if ((int) $level >= 8): ?>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#marketing_text">
        Marketing Text
        </button>
        <?php endif; ?>


          <a href="../Customers/AddCustomer.php" class="btn btn-primary">Add Customer</a>
          <!-- <a href="../messages/compose_message.php" class="btn btn-secondary">Send Message</a>  -->
        </div>

      </div>

    </div>

    <hr>
    <!-- notification -->
    <div class="container">
      <?php if (isset($_SESSION['notifications'])) : ?>
        <?php foreach ($_SESSION['notifications'] as $key => $notification) { ?>
          <?php if ($notification['type'] === 'success') : ?>
            <div class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <?php echo $notification['message']; ?>
            </div>
          <?php elseif ($notification['type'] === 'error') :  ?>
            <div class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <?php echo $notification['message']; ?>
            </div>
          <?php endif; ?>
        <?php unset($_SESSION['notifications'][$key]);
        } ?>
      <?php endif; ?>

      <?php if($level>= 8): // access level check msg ================  ?>
        
        <!-- marketing text Modal -->
        <div class="modal fade" id="marketing_text" tabindex="-1" aria-labelledby="marketing_textLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="marketing_textLabel">Send Marketing Text</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                <!-- Message input form -->
                <div class="mb-3">
                  <select id="predefined-message" class="form-control">
                    <option value="" selected>Select Message</option>
                    <?php foreach ($predefined_messages as $msg) { ?>
                      <option value="<?php echo $msg['message']; ?>" <?php if ($msg['message'] === $message) echo 'selected'; ?>><?php echo $msg['title']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
        
                  <form id="message-multiple-members-form" action="../messages/send_message.php" method="post">
                    <textarea rows="8" cols="84" required="" name="message" id="message" class="form-control" value="<?php echo $message ?>"><?php echo $message ?></textarea>
                    <div class="mt-4">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">Send to selected members</button>
                        <button type="button" class="btn btn-warning" id="clear-message">Clear</button>
                      </div>
                    </div>
                  </form>
                  
                </div>
 
                <!-- // Message input form -->
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              </div>
            </div>
          </div>
        </div>

    <?php endif; // access level check msg================  ?>
    
    <div class="card mb-3">

      <div class="card-header text-center">
        <?php

        /*if (isset($_POST['submit']) == 'true') {
          //$sql_count = "SELECT id FROM `customer` WHERE 1";

          $name = $_POST['search_name'];

          $name1 = (explode(" ", $name));

          $sql_count = " SELECT * FROM customer WHERE first_name like '%" . $name1[0] . "%' OR Last_name like'%" . $name1[0] . "%' OR Phone_number like'%" . $name1[0] . "%' 
          OR last_visit like'%" . $name1[0] . "%' OR homecity like'%" . $name1[0] . "%' OR ethnicity like'%" . $name1[0] . "%' or notes like  '%" . $name1[0] . "%' or email like '%" . $name1[0] . "%' or date_added like '%" . $name1[0] . "%'
          or country_code like '%" . $name1[0] . "%' ORDER BY id  DESC";

          $q = mysqli_query($db, $sql_count);
          $count = mysqli_num_rows($q);
          echo '<b>Total Records: </b>';
          echo "$count";
        }*/
        ?>
      </div>
<div id="content-wrapper">
  <div class="container-fluid">

<canvas id="myChart" style="width:100%;max-width:800px"></canvas>
        
  <script>
var xValues = <?php echo json_encode($xValues); ?>;
var yValues = <?php echo json_encode($yValues); ?>;
var barColors = [
  "#b91c34",
  "#00aba9",
  "#2b5797",
  "#e8c3b9",
  "#4b77a9",
  "#5f255f",
  "#1a1d1a",
  "#01019d"
];

arrayOfObj = xValues.map(function(d, i) {
  return {
    label: d,
    data: yValues[i] || 0
  };
});

sortedArrayOfObj = arrayOfObj.sort(function(a, b) {
  return b.data-a.data;
});

newArrayLabel = [];
newArrayData = [];
sortedArrayOfObj.forEach(function(d){
  newArrayLabel.push(d.label);
  newArrayData.push(d.data);
});
console.log(newArrayLabel);
console.log(newArrayData);

new Chart("myChart", {
  type: "pie",
  data: {
    labels: newArrayLabel,
    datasets: [{
      backgroundColor: barColors,
      data: newArrayData
    }]
  },
  options: {
    title: {
      display: true,
      text: "Referal type for customer",
      fontSize:22,
      padding: 40,
    },
            legend: {
                position: 'right',
                labels: {
                    fontColor: "black",
                    boxWidth: 20,
                    padding: 20,
                    fontSize:14
                }
            }
  }
});
</script>      

   </div>
</div>
      <form action="CustomersDetails.php" method="post">

        <div class="input-group">

          <?php  if($level >= 8){ //check access level search ?>
            <div class="input-group-append mar-10">
              <input type="text" class="form-control" name='search_name' placeholder="Search for" aria-label="Search" />

              <input style="padding: 4px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" type="submit" class="btn btn-primary" name="submit" value="Search">
              <a style="padding: 7px 6px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px; border-radius: 5px;" href="CustomersDetails.php" class="btn btn-primary">Show All</a>
            </div>

          <?php  }else{ //check access level search  ?>

          <div class="input-group py-4 mar-10">
            <input type="text" class="form-control py-4" name='search_name' placeholder="Search for Customer Records" aria-label="Search" />
            <input style="padding: 4px 20px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" type="submit" class="btn btn-primary" name="submit" value="Search">
          </div>

          <?php  } //check access level search ?>

        </div>

      </form>

      <?php



      if (isset($_POST['submit']) == 'true') {

        $name = $_POST['search_name'];

        $name1 = (explode(" ", $name));

        $sql1 = " SELECT * FROM customer WHERE first_name like '%" . $name1[0] . "%' OR Last_name like'%" . $name1[0] . "%' OR Phone_number like'%" . $name1[0] . "%' 
        OR last_visit like'%" . $name1[0] . "%' OR homecity like'%" . $name1[0] . "%' OR ethnicity like'%" . $name1[0] . "%' or notes like  '%" . $name1[0] . "%' or email like '%" . $name1[0] . "%' or date_added like '%" . $name1[0] . "%'
        or country_code like '%" . $name1[0] . "%' ORDER BY id  DESC LIMIT";

        $q = mysqli_query($db, $sql1);

        ?>

        <div class="card-body">
          <h5 class="text-center"><?= mysqli_num_rows($q) . " Results found"; ?></h5>

          <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

              <thead>

                <?php $status = $_SESSION['status'];

                ?>

                <tr id="th-new">

                <?php if($level >= 8):  // access level checkbox?>
                  
                  <th class="select-all sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width:10px !important;" > <input type="checkbox" id="check-all" name=""></th>
                <?php endif; // access level checkbox?>
                
                <th class="th-1">Name</th>
                
                <?php if($level >= 2):  // access level mobile?>
                  <th class="th-2">Mobile Number</th>
                <?php endif; // access level mobile?>

                  <th class="th-3">Last Visit </th>

                  <th class="th-4">Referral Source</th>

                  <th class="th-6">Add Order</th>

                  <?php if ($status == '1' && $level >= 8) { ?>

                    <th class="th-7">Edit</th>

                    <th class="th-8">Recieved Messages</th>

                    <th class="">Send Message</th>
                  <?php }; ?>

                </tr>
              </thead>

              <tbody id="#table_body">

                <?php

                if (mysqli_num_rows($q) > 0) {

                  while ($row1 = mysqli_fetch_assoc($q)) {

                    $originalDate = $row1["last_visit"];

                    $country_code = $row1["country_code"];

                    if($level >= 8){
                        
                      $result16 = mb_substr($row1["Phone_number"], 0, 3);
                      $result17 = mb_substr($row1["Phone_number"], 3, 3);
                      $result18 = mb_substr($row1["Phone_number"], 6, 4);

                      $result19 = "(" . $result16 . ") " . $result17 . "-" . $result18;
                      $telephone = "<td><a href='tel:'$result19'>" . $country_code . $result19 . "<a></td>";
                      
                    }else if($level >= 1){
                        
                      $result16 = mb_substr($row1["Phone_number"], 0, 3);
                      $result17 = mb_substr($row1["Phone_number"], 3, 3);
                      $result18 = mb_substr($row1["Phone_number"], 6, 4);

                      $result19 = "(" . $result16 . ") " . $result17 . "-" . $result18;
                      $telephone = "<td><a href='tel:'$result19'>" . $country_code . $result19 . "<a></td>";
                      
                    }else{
                      $telephone = "<td>(***) ***-" . substr($row1["Phone_number"], strlen($row1["Phone_number"]) - 4)."</td>";
                    }

                    //print_r($originalDate);die;

                    if ($originalDate) {

                      $newDate = date("m/d/Y", strtotime($originalDate));
                    } else {

                      $newDate = $row["last_visit"];
                    }
                    
                  if($level >= 8){

                    $sql = "SELECT count(id) as messages FROM `massages` WHERE contact_no='{$row1['Phone_number']}' AND seen=0 AND type='recieved'";
                    $result = mysqli_query($db, $sql);
                    $messages = mysqli_fetch_assoc($result)['messages'];

                    if ($messages > 0) {
                      $view_messages = "<a href=\"../messages/chat.php?member_id={$row1['id']}\" class=\"btn btn-success\">View Messages</a>";
                      $unread = "class='font-weight-bold'";
                    } else {
                      $view_messages = "";
                      $unread = "";
                    }

                    //Get all checked
                    $selected_members = array();
                    if (isset($_SESSION['message_system']['selected_members'])) {
                      $selected_members = $_SESSION['message_system']['selected_members'];
                    }

                    $message = "";
                    if (isset($_SESSION['message_system']['message'])) {
                      $message = $_SESSION['message_system']['message'];
                    }


                    // Get Pre defined text messages
                    /* $sql = "SELECT * FROM `text_messages`";
                    $result = mysqli_query($db, $sql);
                    $predefined_messages = mysqli_fetch_all($result, MYSQLI_ASSOC); */


                    if (in_array($row1['id'], $selected_members)) {
                      $checked = "checked";
                      unset($selected_members[array_search($row1['id'], $selected_members)]);
                    } else {
                      $checked = "";
                    };

                    $check_box =  "<td style='padding:10px;'>
                                    <input type='checkbox' class='check-this mx-auto' name='member_ids[]' value='" . $row1["id"] . "' form='message-multiple-members-form' $checked>
                                    </td>";

                  }else{
                    $check_box = "";

                  }


                    echo "<tr>
                             $check_box

                        <td><div $unread class='info' data-info='" . $row1["id"] . "'>" . ucwords($row1["first_name"]) . " " . ucwords($row1["Last_name"]) . "
                              <div>" . ucwords($row1["homecity"]) . "<span class='text-muted'> ".(((int) $level >= 8)? "(" . ucwords($row1["ethnicity"]) . ")": "")."</span>
                              </div>
                            </div>
                    </td>
                    $telephone
                    <td>" . $newDate . "</td>
                    <td>" . $row1["referral"] . "</td>
                      <td><a href=../Orders/Addorders.php?id=" . $row1["id"] . " class='btn btn-info' role='button'>Add Order</a></td>";

                      if ($status == '1' && $level >= 8) {
                        echo "

                    <td><a href=AddCustomer.php?id=" . $row1["id"] . " class='btn btn-info' role='button'> Edit</a></td>

                    <td>" . $view_messages . "</td>

                    <td>
                        <form action='../messages/send_message.php' method='post'>
                          <input type=\"hidden\" name=\"member_id\" value=" . $row1['id']  . ">
                          <input type=\"hidden\" name=\"message\" class=\"individual_message\">
                          <button type=\"submit\" class=\"btn btn-info\">Send Message</button>
                        </form>
                    </td>

                    </tr>";
                    }
                  }
                }
                ?>
              </tbody>
              
            </table>
            
          </div>
        </div>
    <?php  } else {     ?>
        
            <div class="card-body">
              <?php  if($level >= 8): //check access level customer records ?>

              <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                  <thead>

                    <?php $status = $_SESSION['status'];

                    ?>

                    <tr id="th-new">

                      <th class="select-all sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width:1% !important;" aria-sort="ascending" aria-label="Name: activate to sort column descending"><input type="checkbox" id="check-all" name=""></th>

                      <th class="th-1">Name</th>

                      <th class="th-2">Mobile Number</th>

                      <th class="th-3">Last Visit </th>

                      <th class="th-6">Add Order</th>

                      <?php if ($status == '1') { ?>

                        <th class="th-7">Edit</th>

                        <th class="th-8">Recieved Messages</th>

                        <th class="">Send Message</th>
                        <th class="">View Note</th>



                      <?php }; ?>



                    </tr>

                  </thead>



                  <tbody id="#table_body">



                  <?php

                  $sql = "SELECT COUNT(id) as total_customers FROM `customer`";
                  $result = mysqli_query($db, $sql);
                  $count = mysqli_fetch_assoc($result)['total_customers'];

                  $sql = "SELECT * FROM `customer`  ORDER BY (SELECT created_at FROM `massages` WHERE contact_no=customer.Phone_number AND seen=0 AND type='recieved' ORDER BY created_at DESC LIMIT 1) DESC , id DESC Limit 10";
                  $result_page = mysqli_query($db, $sql);

                  if (mysqli_num_rows($result_page) > 0) {



                    while ($row = mysqli_fetch_assoc($result_page)) {

                      // $originalDate=$row["last_visit"];

                      /*var_dump($row['id']);

                      var_dump($originalDate);*/

                      if ($row["last_visit"]) {

                        $listVisitDate = date("m/d/Y", strtotime($row["last_visit"]));
                      } else {

                        $listVisitDate = $row["last_visit"];
                      }

                      $country_code = $row["country_code"];
                      $result12 = mb_substr($row["Phone_number"], 0, 3);

                      $result13 = mb_substr($row["Phone_number"], 3, 3);

                      $result14 = mb_substr($row["Phone_number"], 6, 4);

                      //print_r($result13);exit;
                      if($level >=8){
                        $result15 = "(" . $result12 . ") " . $result13 . "-" . $result14;
                        $telephone = "<td><a href='tel:" . $result15 . "'>" . $result15 . "<a></td>";
                      }else{
                        $telephone = "";
                        
                      }

                      // var_dump($result15);die;

                      $sql = "SELECT count(id) as messages FROM `massages` WHERE contact_no='{$row['Phone_number']}' AND seen=0 AND type='recieved'";
                      $result = mysqli_query($db, $sql);
                      $messages = mysqli_fetch_assoc($result)['messages'];
                      if ($messages > 0) {
                        $view_messages = "<a href=\"../messages/chat.php?member_id={$row['id']}\" class=\"btn btn-success\">View Messages</a>";
                        $unread = "class='font-weight-bold'";
                      } else {
                        $view_messages = "";
                        $unread = '';
                      }

                      //Get all checked
                      $selected_members = array();
                      if (isset($_SESSION['message_system']['selected_members']))
                        $selected_members = $_SESSION['message_system']['selected_members'];

                      if (in_array($row['id'], $selected_members)) {
                        $checked = "checked";
                        unset($selected_members[array_search($row['id'], $selected_members)]);
                      } else {
                        $checked = "";
                      };

                      echo "<tr>
                          <td style=\"padding:1%;\">
                            <input type='checkbox' class='check-this mx-auto' name='member_ids[]' value='" . $row["id"] . "' form='message-multiple-members-form' $checked>
                          </td>
                          <td><div $unread class='info' data-info='" . $row['id'] . "'> 
                              <div>" . ucwords($row["first_name"]) . " " . ucwords($row["Last_name"]) . "</div>
                              <div>" . ucwords($row["homecity"]) . "<span class='text-muted'> - " . ucwords($row["ethnicity"]) . "</span></div>
                              </div>
                          </td>
                          " . $telephone . "
                          <td>$listVisitDate</td>
                          <td><a href=../Orders/Addorders.php?id=" . $row["id"] . " class='btn btn-info' role='button'>Add Order</a></td>";

                      if ($status == '1' && $level >= 8) {

                        echo "
                          <td><a href=AddCustomer.php?id=" . $row["id"] . " class='btn btn-info' role='button'> Edit</a></td>
                          <td>" . $view_messages . "</td>
                          <td>
                          <form action='../messages/send_message.php' method='post'>
                            <input type=\"hidden\" name=\"member_id\" value=" . $row['id']  . ">
                            <input type=\"hidden\" name=\"message\" class=\"individual_message\">
                            <button type=\"submit\" class=\"btn btn-info\">Send Message</button>
                          </form>
                        </td>
                        <td>
                          <button type=\"submit\" class=\"btn btn-info view_Notes\" id=\"view_Notes\"  data-info='" . $row['id'] . "' data-toggle=\"modal\" data-target=\"#exampleModal\">View Note</button>
                        </td>

                      </tr>";
                      }
                    }
                  }

                  ?>

                  </tbody>

                </table>

              </div>

              <?php  endif; //check access level customer records 
              }
              ?>
            </div>

          </div>

        </div>


        <?php //load more btn;
          if (!isset($_POST['submit']) == 'true' && $level >= 8) {
            ?>

          <div class="col-md-12 text-center" style="display: flex;justify-content: center;">
            <div id='loadMore' class='btn btn-primary px-4'>Load More</div>
          </div>
          
        <?php } ?>

<!-- /.container-fluid -->
    </div>

        <!-- Sticky Footer -->

        <!-- /.content-wrapper -->
      </div>
      
    </div>
    
    <!-- /#wrapper -->
<!--     
    <footer class="sticky-footer">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>Copyright © Your Website 2018</span>
        </div>
      </div>
    </footer> -->

    <?php if($level >= 8): // access level check 4  ?>
  <!-- Customer Info Modal -->
  <button id="open-modal" type="" class="d-none" data-toggle="modal" data-target="#customerInfo">Open modal</button>

  <div class="modal fade bd-example-modal-lg" id="customerInfo" tabindex="-1" aria-labelledby="infoTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoTitle">Customer Info</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="info-body" class="modal-body">

          <div id="customerHistory" class="container"></div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Home City</th>
                <th scope="col">Mobile</th>
                <th scope="col">Last Visit</th>
                <th scope="col">Order ID</th>
                <th scope="col">Guest Name</th>
                <th scope="col">Ticket Id</th>
                <th scope="col">Name On Ticket</th>
              </tr>
            </thead>
            <tbody id="get_user_data">
            </tbody>
          </table>
          <form id=NotesForm>
            <div class="form-group">
              <label>Notes:</label>
              <input type="hidden" name="notesID" id="notesID" value="">
              <textarea type="text" name="Notes" id="Notes" class="form-control Notes" required></textarea>
            </div>
            <input id="addNotes" type="button" class="btn btn-primary" value="Add" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!--// Customer Info Modal -->
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Notes</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Note</th>
              <th scope="col">Created At</th>
            </tr>
          </thead>
          <tbody id="get_notes">
          </tbody>
        </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<!-- Note Modal End -->


<script>
    // for info modal

    $(".view_Notes").on('click', function(event) {
      let infoId = $(event.currentTarget).data('info');
      console.log(infoId);
      $("#exampleModal").click();
      //get all data by AJAX
      $.ajax({
        url: '../Customers/add_note.php?id=' + infoId,
        success: function(res) {
          console.log(res);
          $('#get_notes').html(res);
          
        }
      });

    });
  </script>
  <script>
    // for info modal

    $(".info").on('click', function(event) {
      let infoId = $(event.currentTarget).data('info');
      console.log(infoId);
      $("#open-modal").click();

      //get all data by AJAX
      $.ajax({
        url: '../Customers/info.php?id=' + infoId,
        success: function(res) {
          // res_data = JSON.parse(res);
          console.log(res);
          //$('#info-body').html(res);

          $('#notesID').val(infoId);
          $('#get_user_data').html(res);
          
          // $('#Notes').val(res);
        }
      });

    });
  </script>

  <script>
    $("#addNotes").on('click', function(event) {

      let notesID = $("#notesID").val();
      let Notes = $("#Notes").val();
      console.log(notesID);
      var data = $('#NotesForm').serialize();
      $.ajax({
        type: "POST",
        url: '../Customers/info.php?id=' + notesID,
        data: data,
        success: function(res) {
          // res_data = JSON.parse(res);
          console.log(res);

          //$('#info-body').html(res);
          // $('#Notes').val(res);

          $('[data-dismiss="modal"]').click();

          setTimeout(() => {
            alert('Note Added Successfully');
          }, 100);

        },
        error: function(res) { // if error occured
          console.log("Error occured, please try again");
        },
      });
    });
  </script>


  <script>
    let page_no = 2;
    let total_pages = <?php echo $count > 50 ? ceil($count / 50) : 1 ?>;

    var table = $('#dataTable').dataTable({
      columnDefs: [{
    "defaultContent": "-",
    "targets": "_all"
  }],
      paging: false,
      order: [
        ['6', 'desc']
      ]
    });

    $("#loadMore").click(function() {
      if (page_no < total_pages) {
        $("#loadMore").html("Loading ...");
        $.ajax({
          url: 'CustomersDetailsAjax.php?page=' + page_no,
          success: function(response) {
            data = JSON.parse(response).data;
            table.fnAddData(data);
            page_no += 1;
            $("#loadMore").html("Load More");

          }, //success
          error: function() {
            $("#loadMore").html("Loading Error");
            $("#loadMore").addClass("btn-danger");
          }
        }) //ajax
      } //if
      else {
        $("#loadMore").hide();
      }
    }) //click
  </script>

<?php endif; // access level check 4 ?> 


<script type="text/javascript">
  // =================  Script for the send message
    (function() {
      const checkAll = document.getElementById('check-all');
      const checkboxes = document.getElementsByClassName('check-this');
      checkAll.addEventListener('click', function() {
        for (let i = 0; i < checkboxes.length; ++i) {
          if (this.checked) {
            checkboxes[i].checked = true;
            addMemberToState(checkboxes[i].value);
          } else {
            checkboxes[i].checked = false;
            removeMemberFromState(checkboxes[i].value);
          }
        }
      });
    })();

    (function() {
      const message = document.getElementById('message');
      const individualMessages = document.getElementsByClassName('individual_message');

      for (let i = 0; i < individualMessages.length; ++i)
        individualMessages[i].value = message.value;

      message.addEventListener('keyup', function() {
        for (let i = 0; i < individualMessages.length; ++i)
          individualMessages[i].value = this.value;
      });
    })();

    // Clear Message
    (function() {
      const clearBtn = document.getElementById('clear-message');
      const message = document.getElementById('message');
      clearBtn.addEventListener('click', function() {
        message.value = "";
        removeMessageFromState();
      });
    })();

    // Mange state. The state is managed by manage_state.php file on server side
    (function() {
      const message = document.getElementById('message');
      const memberIds = document.getElementsByClassName('check-this');
      for (let i = 0; i < memberIds.length; ++i) {
        memberIds[i].addEventListener('change', function() {
          if (this.checked)
            addMemberToState(this.value);
          else
            removeMemberFromState(this.value);
        });
      }

      message.addEventListener('change', function() {
        saveMessageToState(this.value);
      })
    })();

    function addMemberToState(id) {
      $.ajax({
        url: '../messages/manage_state.php?action=add_member&member_id=' + id,
        success: function(res) {}
      });
    }

    function removeMemberFromState(id) {
      $.ajax({
        url: '../messages/manage_state.php?action=remove_member&member_id=' + id,
        success: function(res) {}
      });
    }

    function saveMessageToState(message) {
      $.ajax({
        url: '../messages/manage_state.php?action=save_message&message=' + message,
        success: function(res) {}
      });
    }

    function removeMessageFromState() {
      $.ajax({
        url: '../messages/manage_state.php?action=remove_message&message=' + message,
        success: function(res) {}
      });
    }


    // Code for selecting predefined messages
    (function() {
      const predefinedMessage = document.getElementById('predefined-message');
      predefinedMessage.addEventListener('change', function() {
        message.value = this.value;

        saveMessageToState(this.value);
      });
    })();
  </script>
  <!-- ================= // Script for the send message -->





  </body>

  </html>