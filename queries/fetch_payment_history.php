<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../Config/Connection.php');
$id = @$_GET['id'];
$table = "(
    select * from accounting where orderID = '$id'
    ) temp";
//$table = "accounting";
// Table's primary key
$primaryKey = 'id';
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database.
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'paymentMethod', 'dt' => 1 ),
    array( 'db' => 'paymentAmount',  'dt' => 2 ),
);
// Include SQL query processing class
require 'ssp.payment.class.php';
// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ));

?>