<?php
session_start();
$id=$_GET['id'];
include('../Config/Connection.php');

$sql = "SELECT * FROM `order` where `id`=$id";
$result = mysqli_query($db, $sql);
$order=mysqli_fetch_assoc($result);

$sql = "SELECT * FROM `guest` WHERE `order_id`={$order['id']}";
$result = mysqli_query($db, $sql);
$guests = mysqli_fetch_all($result, MYSQLI_ASSOC);

$ticket_ids = array();
foreach ($guests as $guest)
{
  if (! in_array($guest['ticket_id'], $ticket_ids))
    $ticket_ids[] = $guest['ticket_id'];
}

$sql = "SELECT * FROM `theme_parks` WHERE id={$order['theme_park_id']}";
$result = mysqli_query($db, $sql);
$park = mysqli_fetch_assoc($result);

$time = new DateTimeImmutable($order['time']);
$time = $time->modify("+30 minutes");
$time = $time->format("g:i A");

$date = $order['date_of_visit'];

$current_date = date("Y-m-d");

$method_transfer = $_REQUEST['transfer_method'];
if (empty($method_transfer))
  $method_transfer = "no";

foreach ($ticket_ids as $ticket_id)
{
  $sql = "INSERT INTO `history` (order_id, history_date, history_time, park, created_on, barcode, method_transfer)
 VALUES ({$order['id']},'$date','$time','{$park['name']}','$current_date', '$ticket_id', '$method_transfer')";
   $insert_result = mysqli_query($db,$sql);
}

// $sql = "DELETE FROM `history` WHERE order_id={$order['id']}";
// $result = mysqli_query($db, $sql);

$sql = "SELECT * FROM `history` WHERE order_id=".$order['id'];
$result = mysqli_query($db, $sql);
$history = mysqli_fetch_all($result, MYSQLI_ASSOC);

// echo '<pre>';
// print_r($history);
// exit();


$_SESSION['success_msg'] = 'Usage Updated Successfully';
header('Location:Orderdetails.php?active=0');