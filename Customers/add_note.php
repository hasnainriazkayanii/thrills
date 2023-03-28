<?php
include('../Config/Connection.php');

session_start();

$login_check = $_SESSION['id'];


if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}

// Main code starts here

$id = $_GET['id'];


  $select = "SELECT * from notes where customer_id='$id'";
  $result = mysqli_query($db, $select);
  while ($row = mysqli_fetch_assoc($result)) {
    echo   "
      <tr>
      <td>" . $row["note"] . "</td>
      
      <td>" . $row["created_at"] . "</td>";
  }
