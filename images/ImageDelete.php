<?php
include('../Config/Connection.php');
$id=$_GET['id'];
$sql33 = "SELECT image$id FROM  image where id=6";

$result33 = mysqli_query($db, $sql33);
$row = $result33->fetch_assoc();
//print_r($row[image.$id]);exit;
if($row[image.$id])
{
  $imageAsarray=explode("/",$row[image.$id]);
             $image=end($imageAsarray);

unlink('../uploads/'.$image);

}

$image_insert= "UPDATE image SET 
                          image$id=''
                         WHERE id='6'";
                         
   //var_dump($image_insert);
    $result = mysqli_query($db,$image_insert);
    
  header( "Location: imageupload.php" );


?> 