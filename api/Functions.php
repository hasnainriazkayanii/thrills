<?php
 include('../Config/Connection.php');

function send_response($message)
{
    $message = utf8_converter($message);
 echo json_encode($message);
}

function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });
 
    return $array;
}

function get_menu_detail()
{
     global $db;
    $q['query'] = "SELECT * FROM `menuoptions`"; 
    $q['run'] = $db->query($q['query']);
    $q['result'] = array();
    while($c=$q['run']->fetch_assoc())
    {
        array_push($q['result'], $c);
    }
    //$q['result'] = $q['run']->fetch_assoc();
    //var_dump($q['query']);
    return $q['result'];
}
?>