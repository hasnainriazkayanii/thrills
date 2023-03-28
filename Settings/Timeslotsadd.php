<?php
 include('../Config/Connection.php');

  session_start();
  $login_check=$_SESSION['id'];
  $level = $_SESSION['level'] ?? 1;
  $status=$_SESSION['status']; 

header('Location: Timeslots.php');


        if (isset($_POST['submit'])) {
            
            $times=$_POST['time'];
            $slots=$_POST['slots'];
           
             $delete= "TRUNCATE TABLE  time_slots";
             $deleteres=mysqli_query($db, $delete);
      
            foreach($times as $key=>$value){
                
                 $query ="insert into `time_slots`( time, slots)values( '$times[$key]', '$slots[$key]')";
                 
                $insert = mysqli_query($db, $query);
            }
            
            
        }
        
        
      
        
        ?>

