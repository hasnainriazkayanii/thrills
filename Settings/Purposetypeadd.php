<?php
 include('../Config/Connection.php');

  session_start();
  $login_check=$_SESSION['id'];
  $level = $_SESSION['level'] ?? 1;
  $status=$_SESSION['status']; 

// header('Location: Purposetype.php');


        if (isset($_POST['submit'])) {
            
            $purpose=$_POST['purpose'];
         $details=$_POST['details'];
           
             $delete= "TRUNCATE TABLE  purpose_type";
             $deleteres=mysqli_query($db, $delete);
      
            foreach($purpose as $key=>$value){
                var_dump($purpose[$key],$details[$key]);
           
                if($details[$key]==="1")
                {
                   $detailsc=1; 
                    
                }
                else{
                    
                    
                    $detailsc=0;
                }
                 $query ="insert into `purpose_type`( purpose, details)values( '$purpose[$key]','$detailsc')";
                 
                $insert = mysqli_query($db, $query);
                
            }
            
            
        }
        
        
      
        
        ?>

