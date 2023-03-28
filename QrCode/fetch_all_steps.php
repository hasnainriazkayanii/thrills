<?php
include('../Config/Connection.php');
$returnArray = array();
$dataArray = array();
$query = "SELECT * from steps";
$result = mysqli_query($db,$query);
if($result){
    while ($row = mysqli_fetch_assoc($result)){
        $dataArray[] = $row;
    }
    $returnArray['data'] = $dataArray;
    $returnArray['status'] = 1;
    $returnArray['developer_message'] = "Data fetched Successfully";
}else{
    $returnArray['data'] = $dataArray;
    $returnArray['status'] = -1;
    $returnArray['developer_message'] = "Something went wrong";

}

echo json_encode($returnArray);


?>