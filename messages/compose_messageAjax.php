<?php
session_start();
include('../Config/Connection.php');

$login_check=$_SESSION['id'];

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
};


/*-----------Get Customers or Members-------------*/

// Set per page
   $limit = 10;

// Get Page number
if (isset($_REQUEST['page']) && is_numeric((int) $_REQUEST['page']) && $_REQUEST['page'])
   $page = $_REQUEST['page'];
   if($page>1)
        $start = ($page - 1) * $limit;
    else
        $start = $limit;


   $sql = "SELECT * FROM `customer` ORDER BY id  DESC LIMIT $start, $limit";
   $result = mysqli_query($db, $sql);
   $members = mysqli_fetch_all($result, MYSQLI_ASSOC);

      foreach ($members as $member) {  
            $checked = '';
            
             if(in_array($member['id'], $selected_members)): $checked = "checked"; unset($selected_members[array_search($member['id'], $selected_members)]); endif;
             $flag = ((int)$member['is_subscribed'])? 'YES' : 'NO';


            echo "<tr role='row' class='odd'>";
 
            echo "<td style='padding-right:28px;'><input type='checkbox' class='check-this' name='member_ids[]' value=" . $member['id'] . "form='message-multiple-members-form' " . $checked . " ></td>";

            echo "<td class='sorting_1'>" . $member['first_name'] . ' ' . $member['Last_name'] . "</td>";
            echo "<td>" . $member['Phone_number']. "</td>";
            echo "<td>" . $member['homecity'] . "</td>";
            echo "<td>" . $flag . "</td>";
            echo "<td>
               <form action='send_message.php' method='post'>
                  <input type='hidden' name='member_id' value=" . $member['id'] . ">
                  <input type='hidden' name='message' class='individual_message'>
                  <button type='submit' class='btn btn-info'>Send Message</button>
               </form>
            </td>";

           echo "</tr>";
      };
   ?>