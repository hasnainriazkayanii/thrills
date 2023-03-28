<?php
include('../Config/Connection.php');

/*header('Content-type: application/json');
extract($_REQUEST);
$inputJSON = file_get_contents('php://input');
$input = json_decode( $inputJSON, TRUE );*/ 

function send_response($message)
{
	echo json_encode($message);
} 
function get_all_item() 
{
    global $db;
    $q['query']="select * from image";
    $q['run'] = $db->query($q['query']);
    $all_details=array();
    if($q['run']->num_rows != '0')
    { 
        //var_dump($q['run']);
        while ($q['result'] = $q['run']->fetch_assoc())
        {
            $image1 = $q['result']['image1'];
            $image2 = $q['result']['image2'];
            $image3 = $q['result']['image3'];
            $image4 = $q['result']['image4'];
            $image5 = $q['result']['image5'];
            
            if($image1!='' && $image2=='' && $image3=='' && $image4=='' && $image5=='')
            {
                $image = $image1;
            }
            elseif($image2!='' && $image3=='' && $image4=='' && $image5=='')
            {
                $image = $image1.",".$image2;
            }
            elseif($image3!='' && $image4=='' && $image5=='')
            {
                 $image = $image1.",".$image2.",".$image3;
            }
            elseif($image4!='' && $image5=='')
            {
                 $image = $image1.",".$image2.",".$image3.",".$image4;
            }
            elseif($image5!='')
            {
                 $image = $image1.",".$image2.",".$image3.",".$image4.",".$image4;
            }
            
            $id = $q['result']['id'];
            $image = explode(",",$image);
            $details['image'] = $image;
            $details['id'] = $id;
            array_push($all_details, $details);
            //print_r($all_supplier);
            
        }
    }
    return $all_details;
   
}
//$data = get_all_supplier();
$data = get_all_item();



$message = array('status'=>'1','message'=>'All Details successfully','data'=>$data);
send_response($message);

?>

