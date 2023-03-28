<?php
include "../Config/Connection.php";
$returnArray = array();
$current_date = date("Y-m-d");
$theme_park_id = $_POST['theme_park_id'];
$query = "SELECT DISTINCT set_link from ticket where set_link != '' and theme_park_parent_id = '$theme_park_id' and expire_date >= '$current_date' and active='True' order by expire_date desc";
$result = mysqli_query($db,$query);
while ($row = mysqli_fetch_assoc($result)){
    $returnArray[] = $row;
}
echo json_encode($returnArray);
?>