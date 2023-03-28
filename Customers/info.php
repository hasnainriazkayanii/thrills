<?php
include('../Config/Connection.php');

session_start();

$login_check=$_SESSION['id'];
  
if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}

// Main code starts here

$id = $_GET['id'];
$date = date('Y-m-d H:i:s');
$notes = $_POST['Notes'];

if ($notes == '') {

  $select = "SELECT * from customer left join `order` on `order`.customer_id=customer.id left join `guest` on `guest`.order_id=`order`.id left join ticket on ticket.ticketshowid=guest.ticket_id where customer.id='$id'";
  $result = mysqli_query($db, $select);
  while($row=mysqli_fetch_assoc($result)){
    $country_code = $row["country_code"];
    $result12 = mb_substr($row["Phone_number"], 0, 3);
    $result13 = mb_substr($row["Phone_number"], 3, 3);
    $result14 = mb_substr($row["Phone_number"], 6, 4);
    $result15 = "(".$country_code.")"."(" . $result12 . ") " . $result13 . "-" . $result14;
    if ($row["last_visit"]) {
      $listVisitDate = date("m/d/Y", strtotime($row["last_visit"]));
    } else {

      $listVisitDate = $row["last_visit"];
    }
    echo "
      <tr><td>".ucwords($row["first_name"]) . " " . ucwords($row["Last_name"]) ."</td>
      <td>".ucwords($row["homecity"])."</td>
      <td>".$result15."</td>
      <td>".$listVisitDate."</td>
      <td>".$row["order_id"]."</td>
      <td>".$row["guest_name"]."</td>
      <td>".$row["ticketshowid"]."</td>
      <td>".$row["name_on_ticket"]."</td></tr>";
    }
  //   if($res){
  //     echo json_encode($res);
  //   }else{
  //     $notes_all = ['Notes' => 'No notes available' ];
  //     echo json_encode($notes_all);
  //   }


  // } else {

  // $sql = "UPDATE customer SET Notes='$notes' where id='$id'";
  // $result = mysqli_query($db, $sql);

  // if ($result) {
  //   echo json_encode(['Notes'=>$notes]);

  // }else{
  //   echo json_encode(['error'=>"Not added"]);
  // }

}else{

    $sql = "INSERT INTO `notes`(`customer_id`, `note`, `created_at`) VALUES('$id','$notes','$date')";
    $result = mysqli_query($db, $sql);
    if($result){
      echo "Note Added Successfully";
    }else{
      echo "Failed to add note";
    }
}
