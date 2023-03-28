<?php
include('../Config/Connection.php');
session_start();

if (!isset($_GET['id']))
	header("Location: Orderdetails.php");

require_once "../libraries/vendor/autoload.php";

$guest_id=$_GET['id'];

$sql = "SELECT * FROM `guest` WHERE `id`=$guest_id";
$result = mysqli_query($db,$sql);
$guest = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM `order` WHERE `id`={$guest['order_id']}";
$result = mysqli_query($db,$sql);
$order = mysqli_fetch_assoc($result);

use Twilio\Rest\Client;
include "../Config/twilio.php";

$ticket_id = $guest['ticket_id'];
$sql = "SELECT * FROM history WHERE barcode='$ticket_id' group by history_date";
$result = mysqli_query($db, $sql);
$history = mysqli_fetch_all($result, MYSQLI_ASSOC);
$history = $history[count($history) - 1];
$guest_name_title=$guest['guest_name']; 
$str=$guest['ticket_id'];
$ticket_last_digits=substr($str, strlen($str) -7);
$text_msg = "";
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

            if($result_unit){

                while($row_unit=mysqli_fetch_assoc($result_unit))

                {
                       $text_msg .= $row_unit['theme_park_code']." ".substr($ticket_id, -4)."\n";
                       $text_msg .= $guest['guest_name']."\n\n";
                       //$text_msg .= $row_unit['entitlement']." - ".ucfirst($row_unit['type'])."\n\n";

                }

            }

                    

          while($row_group=mysqli_fetch_assoc($result_group))

          {

              $sql_ticket = "SELECT * FROM history WHERE barcode='$ticket_id' and history_date='".$row_group['history_date']."' ORDER BY history_time ASC";

              $result_ticket = mysqli_query($db, $sql_ticket);

              if($result_ticket){ 

                    

                   

                    //$a=0;

                    $cnt=0;

                            $text_msg .= date("l F d",strtotime($row_group['history_date']))."\n"; 

                     

                 

                    while($user=mysqli_fetch_assoc($result_ticket))

                    {

                       //var_dump($user);die;

                        if($cnt == 0){

                            $text_msg .= $user['park']." ".date("g:i A",strtotime($user['history_time']))."\n";

                        }else{

                          if($user['method_transfer'] != "no"){

                               $text_msg .= "Then ".$user['method_transfer']." to ".$user['park']." at ".date("g:i A",strtotime($user['history_time']))."\n"; 

                          }

                        }

                          

                        $cnt++;

                       

                    }  

                    $text_msg .= "\n";

                }

          }

$sql = "SELECT * FROM `settings` WHERE name='admin_mobile'";
$result = mysqli_query($db, $sql);
$property = mysqli_fetch_assoc($result);
if ($property)
    $admin_mobile = $property['value'];
else
    $admin_mobile = "";


$client = new Client($account_sid, $auth_token);
try
{
    $client->messages->create(
        $admin_mobile,
        array(
            'from' => $twilio_number,
            'body' => $text_msg
        )
    );

    $admin_mobile = str_replace("+1", "", $admin_mobile); // Convert to local format from internation format
    // Store message in database
    $sql = "INSERT INTO `massages` (contact_no, message, type) VALUES ('$admin_mobile', '$text_msg', 'sent')";
    $result = mysqli_query($db, $sql);
}
catch (Exception $e)
{
    
}
    

$_SESSION['success_msg'] = 'Usage texted succssfully';
header('Location: ../Assign/Addassign.php?id='.$order['id']);
