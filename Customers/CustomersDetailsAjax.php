<?php
include('../Config/Connection.php');

session_start();

$login_check = $_SESSION['id'];

$tbl_name = "`customer` ORDER BY (SELECT created_at FROM `massages` WHERE contact_no=customer.Phone_number AND seen=0 AND type='recieved' ORDER BY created_at DESC LIMIT 1) DESC,id DESC";
$targetpage = "../Customers/CustomersDetails.php";
$status = $_SESSION['status'];

if ($login_check != '1') {
  $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location: ../Login/login.php");
}


//paginate

$query = "SELECT COUNT(*) as num FROM $tbl_name";
$total_pages = mysqli_fetch_array(mysqli_query($db,$query));
$total_pages = $total_pages['num'];
$limit = 50; 

if(isset($_GET['page'])){
    $page = $_GET['page'];

    if($page>1)
        $start = ($page - 1) * $limit;
    else
        $start = $limit;

    $sql = "SELECT * FROM $tbl_name LIMIT $start, $limit";
    $result_page = mysqli_query($db,$sql);

    if (mysqli_num_rows($result_page) > 0) {
		$data = [];

    while ($row = mysqli_fetch_assoc($result_page)) {

        if($row["last_visit"]){
            $listVisitDate = date("m/d/Y", strtotime($row["last_visit"]));
        }else{
            $listVisitDate = $row["last_visit"];
        }

        $result12 = mb_substr($row["Phone_number"], 0, 3);
        $result13 = mb_substr($row["Phone_number"], 3, 3);
        $result14 = mb_substr($row["Phone_number"], 6, 4);
        $result15 = "(" . $result12 . ") " . $result13 . "-" . $result14;


        $sql = "SELECT count(id) as messages FROM `massages` WHERE contact_no='{$row['Phone_number']}' AND seen=0 AND type='recieved'";
        $result = mysqli_query($db, $sql);
        $messages = mysqli_fetch_assoc($result)['messages'];
        if($messages > 0){
            $flag = "class='font-weight-bold'";
            $view_messages = "<a href='../messages/chat.php?member_id={$row['id']}' class='btn btn-success'>View Messages</a>";
        }else{
            $view_messages = "";
            $flag = "";
        }

        //checked for message

         //Get all checked
         $selected_members = array();
         if (isset($_SESSION['message_system']['selected_members']))
             $selected_members = $_SESSION['message_system']['selected_members'];

         if(in_array($row['id'], $selected_members)){
         $checked = "checked";
         unset($selected_members[array_search($row['id'], $selected_members)]);
         }else{
           $checked = "";
         };


            $name = ucwords($row["first_name"]) . " " . ucwords($row["Last_name"]);
            $city = ucwords($row["homecity"]);
            $ethnicity = $row["ethnicity"];

        $tbl_row[0] = "<input type='checkbox' class='check-this mx-auto' name='member_ids[]' value='" . $row["id"] . "' form='message-multiple-members-form' $checked>";
		$tbl_row[1] = "<span $flag ><div>$name</div><div> $city <span class='text-muted'> - $ethnicity</span></div></span>";
		$tbl_row[2] = "<a href='tel:" . $result15 . "'>" . $result15 . "<a>";
		$tbl_row[3] = "$listVisitDate";
		$tbl_row[4] = "<a href=../Orders/Addorders.php?id=" . $row["id"] . " class='btn btn-info' role='button'>Add Order</a>";

		if($status == '1'){
            $tbl_row[5] = "<a href=UpdateCustomer.php?id=" . $row["id"] . " class='btn btn-info' role='button'> Edit</a>";
            $tbl_row[6] = "$view_messages";
        }
        $tbl_row[7] = "<form action='../messages/send_message.php' method='post'><input type='hidden' name='member_id' value=" . $row['id']  . "><input type='hidden' name='message' class='individual_message'><button type='submit' class='btn btn-info'>Send Message</button></form>";

        array_push($data,$tbl_row);
    }
    $end_data = ["draw"=> $page,
                "recordsTotal"=> $total_pages,
                "recordsFiltered"=> $limit,
                "data"=> $data];
    echo json_encode($end_data);
}





}