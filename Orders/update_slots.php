<?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
include('../Config/Connection.php');

require_once "../libraries/vendor/autoload.php";
include "../Config/twilio.php";
$current_date=date("Y-m-d", strtotime($_GET['date']));


        $options="";                    
        $timeslot="SELECT * FROM time_slots order by time ASC";
        $timeslotress = mysqli_query($db, $timeslot);
        
        while ($timeslotres = mysqli_fetch_assoc( $timeslotress)){
        $checktime= date('g:i A',strtotime($timeslotres['time']));
        $checks="SELECT * FROM `order` where date_of_visit = '$current_date' AND time= '$checktime'" ;
        $slotschecktime = mysqli_query($db, $checks);
        // $slotsbook=mysqli_fetch_assoc($slotschecktime);
        $slotsbook=mysqli_num_rows($slotschecktime);
        // echo json_encode(array('timeslots'=>$slotschecktime));
        $ava=$timeslotres['slots']-$slotsbook;
        if($slotsbook<$timeslotres['slots']){
       
       
        
        
        
        $options.='<option value="'.$checktime.'"> '.$checktime.' ('.$ava.')</option>';

        }   
        } 
        
echo json_encode(array('timeslots'=>$options));
?>