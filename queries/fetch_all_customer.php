<?php
include('../Config/Connection.php');
$id = @$_GET['id'];
$table = "(
    SELECT * FROM `customer`  ORDER BY 
    (SELECT created_at FROM `massages` WHERE contact_no=customer.Phone_number AND seen=0 AND type='recieved' ORDER BY created_at DESC LIMIT 1)
    ) temp";
//$table = "accounting";
// Table's primary key
$primaryKey = 'id';
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database.
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'first_name', 'dt' => 1 ),
    array( 'db' => 'Last_name',  'dt' => 2 ),
    array( 'db' => 'homecity',  'dt' => 3 ),
    array( 'db' => 'ethnicity',  'dt' => 4 ),



);
// Include SQL query processing class
require 'ssp.customer.class.php';
// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ));

?>