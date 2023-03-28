<?php

include('../Config/Connection.php');

session_start();

$login_check=$_SESSION['id'];
  

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}


//Main Code starts here


if(isset($_GET['id']) && isset($_GET['get'])){
    echo "<pre>";
    $id = $_GET['id'];

    // Fetch the customer
    $sql_customer = "SELECT * from customer where id='$id'";
    $customer = mysqli_fetch_assoc( mysqli_query($db, $sql_customer) );

    // Fetch all orders with guests and tickets
    $orders = array();

    $sql_orders = "SELECT * from `order` where `customer_id`='$id'";
    $orders_data = mysqli_query($db, $sql_orders);

    while( $row = mysqli_fetch_assoc($orders_data) )
    {
        array_push($orders, $row);
    }


    print_r($orders);

}