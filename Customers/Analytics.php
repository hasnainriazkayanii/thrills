<?php

include('../Config/Connection.php');

session_start();

$login_check = $_SESSION['id'];

$level = $_SESSION['level'];


if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}

if (isset($_POST['submit'])) {
  $hide_navbar = true;
}

include('../includes/header.php');


$sql = "SELECT * FROM referral_types ";
$result = mysqli_query($db, $sql);
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

foreach ($data as $d){
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
  canvas#myChart {
    margin: auto;
    
}
</style>

<!-- Custom scripts for table sorting -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
</body>

</html>