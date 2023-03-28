<?php
include('../Config/Connection.php');
session_start();
unset($_SESSION['id']);  
 header("location: ../Login/login.php");
?>