<?php
include('../Config/Connection.php');
session_start();


require_once "../libraries/vendor/autoload.php";

$guest_id=$_GET['id'];

$sql = "SELECT * FROM `guest` WHERE `id`=$guest_id";
$result = mysqli_query($db,$sql);
$guest = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM `order` WHERE `id`={$guest['order_id']}";
$result = mysqli_query($db,$sql);
$order = mysqli_fetch_assoc($result);




$ticket_id = $guest['ticket_id'];

$sql = "SELECT * FROM history WHERE barcode='$ticket_id' group by history_date";
$result = mysqli_query($db, $sql);
$history = mysqli_fetch_all($result, MYSQLI_ASSOC);
$history = $history[count($history) - 1];
$guest_name_title=$guest['guest_name']; 
$str=$guest['ticket_id'];
$ticket_last_digits=substr($str, strlen($str) -7);
$text_msg = "<div class='col-sm-12' style='font-size:18px;'> <b>".$guest_name_title."</b> </div><br>";
 $sql_group = "SELECT * FROM history WHERE barcode='$ticket_id' group by history_date";



          $result_group = mysqli_query($db, $sql_group);



          $sql_unite = "SELECT history.*, ticket.*,                                    

                  theme_park_parents.code as theme_park_code 

                        FROM history

                        join ticket on history.barcode=ticket.ticketshowid 

                        LEFT JOIN theme_park_parents on ticket.theme_park_parent_id=theme_park_parents.id

                        WHERE history.barcode = '$ticket_id'

                        GROUP BY history.barcode";



          $result_unit = mysqli_query($db, $sql_unite);

          if ($result_unit) {
             
            while ($row_unit = mysqli_fetch_assoc($result_unit)) {

               $text_msg .= "<div class='col-sm-12'>";

               $text_msg .=  "<p style='font-weight: bold; font-size: 15px; margin-bottom: 0px;'>" . $row_unit['theme_park_code'] . " " . substr($ticket_id, -4) . "</p>";



               $text_msg .= "<p style='font-weight: bold; font-size: 15px; margin-bottom: 0px;'>" . $row_unit['entitlement'] . " - " . ucfirst($row_unit['type']) . "</p>";

              $text_msg .= "<hr>";

               $text_msg .= "</div>";
              
            }
            
          }



          while ($row_group = mysqli_fetch_assoc($result_group)) {

            $sql_ticket = "SELECT * FROM history WHERE barcode='$ticket_id' and history_date='" . $row_group['history_date'] . "' ORDER BY history_time ASC";

            $result_ticket = mysqli_query($db, $sql_ticket);

            if ($result_ticket) {





              //$a=0;

              $cnt = 0;

               $text_msg .= "<div class='col-sm-12'>";

               $text_msg .= "<p style='font-weight: bold; font-size: 15px; margin-bottom: 0px;'>" . date("l F d", strtotime($row_group['history_date'])) . "</p>";

               $text_msg .= "</div>";





              while ($user = mysqli_fetch_assoc($result_ticket)) {

                //var_dump($user);die;

                if ($cnt == 0) {

                   $text_msg .= "<div class='col-sm-12'>";

                  $text_msg .= "<p style='margin-bottom: 0px;'>" . $user['park'] . " " . date("g:i A", strtotime($user['history_time'])) . "</p>";

                  $text_msg .= "</div>";
                } else {

                  $text_msg .= "<div class='col-sm-12'>";

                  if ($user['method_transfer'] != "no") {

                     $text_msg .= "<p style='margin-bottom: 0px;'>Then " . $user['method_transfer'] . " to " . $user['park'] . " at " . date("g:i A", strtotime($user['history_time'])) . "</p>";
                  }



                  $text_msg .= "</div>";
                }



                $cnt++;
              }



               $text_msg .= "<div class='col-sm-12'><hr></div>";
            }
          }
        
echo json_encode(array('history'=>$text_msg));
 
