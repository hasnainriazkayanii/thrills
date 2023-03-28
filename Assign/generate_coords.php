<?php
/*===================================================================================================
This file was used to convert customer address to Latitude and Longitude using google geocoding api 
===================================================================================================*/

// include('../Config/Connection.php');

// $sql = "SELECT * FROM `guest` WHERE user_lat IS NULL AND user_long IS NULL";
// $result = mysqli_query($db, $sql);
// $guests = mysqli_fetch_all($result, MYSQLI_ASSOC);

// foreach ($guests as $guest)
// {
// 	$sql = "SELECT * FROM `order` WHERE order_id='{$guest['login_id']}'";
// 	$result = mysqli_query($db, $sql);
// 	$order = mysqli_fetch_assoc($result);
// 	if (! $order) continue;

// 	$sql = "SELECT * FROM `customer` WHERE id={$order['customer_id']}";
// 	$result = mysqli_query($db, $sql);
// 	$customer = mysqli_fetch_assoc($result);
// 	if (! $customer) continue;

// 	$address = urlencode($customer['homecity']);
// 	if (! $address) continue;

// 	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyAI0TJsSinxPjQXjFj9yDm0bvgHjHN9WsM";

// 	$curl = curl_init($url);
// 	curl_setopt($curl, CURLOPT_URL, $url);
// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// 	$resp = curl_exec($curl);
// 	curl_close($curl);
// 	$resp = json_decode($resp);

// 	$lat = $resp->results[0]->geometry->location->lat?? '';
// 	$lng = $resp->results[0]->geometry->location->lng?? '';

// 	$sql = "UPDATE `guest` SET user_lat=$lat, user_long=$lng WHERE id={$guest['id']}";
// 	$result = mysqli_query($db, $sql);
// }

// $sql = "SELECT * FROM `guest` WHERE user_lat IS NULL AND user_long IS NULL";
// $result = mysqli_query($db, $sql);
// $guests = mysqli_fetch_all($result, MYSQLI_ASSOC);
// echo '<h1>';
// echo count($guests);
// echo '</h1>';
// echo '<pre>';
// print_r($guests);
// exit();