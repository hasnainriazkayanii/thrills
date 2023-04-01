<?php
include('../Config/Connection.php');
$table='';
$user_id = @$_GET['id'];
$returnArray = array();
$customerQuery ="SELECT * from login_user where id=$user_id";
$customerResult = mysqli_query($db,$customerQuery);
if (mysqli_num_rows($customerResult) > 0){
    $customer = mysqli_fetch_assoc($customerResult);
    $table.='<div class="col-md-12 text-center"><h3>'.$customer['user_name'].'</h3></div>';
}
$table .='
<table class="table table-bordered">
<thead>
<tr>
<th>Date</th>
</tr>
</thead>
<tbody>
';
$query = "SELECT * from timestamps where type='Login' and object_id=$user_id";
$result = mysqli_query($db,$query);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)){
    $table .='<tr><td>'.date('m/d/Y h:i A',strtotime('+1 hour',strtotime($row['date_time']))).'</td></tr>';
}
}
else{
    $table .='<tr><td>No result found</td></tr>';
}
$table .='</tbody></table>';
//var_dump($query);

echo $table;


?>