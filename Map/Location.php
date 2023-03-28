<?php
session_start();


include('../Config/Connection.php');
$login_check=$_SESSION['id'];

   //var_dump($data1);

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}

$sql = "select * from guest";

$sth = mysqli_query($db,$sql);



while($row = mysqli_fetch_assoc($sth))
 {
   if(!empty($row["user_lat"]) && !empty($row["user_long"]) ){
    $value2[]=array($row["guest_name"],$row["user_lat"],$row["user_long"],$row["id"]);
   }
}

// echo "<script>var locations= ".json_encode($value2,JSON_NUMERIC_CHECK).";</script>";

$current_date = date("Y-m-d");
$sql = "SELECT * FROM `order` JOIN `customer` ON order.customer_id=customer.id WHERE order.assign=1 AND order.date_of_visit = '$current_date'";
$result = mysqli_query($db, $sql);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
// $customers = array();
// foreach ($orders as $order)
// {
//   $customer_id = $order['customer_id'];
//   $sql = "SELECT * FROM `customer` WHERE `id`=$customer_id";
//   $result = mysqli_query($db, $sql);
//   $customer = mysqli_fetch_assoc($result);
//   $customers[] = $customer;
// }

// ===========================Code For Customer Login====================
$sql = "SELECT * FROM `guest` where `islogedin`=1";
$result = mysqli_query($db, $sql);
$logedin_customers = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($logedin_customers as &$guest)
{
  $sql = "SELECT * FROM `order` WHERE order_id='{$guest['login_id']}'";
  $result = mysqli_query($db, $sql);
  $order = mysqli_fetch_assoc($result);
  if (! $order) continue;

  $sql = "SELECT * FROM `customer` WHERE id={$order['customer_id']}";
  $result = mysqli_query($db, $sql);
  $customer = mysqli_fetch_assoc($result);
  if (! $customer) continue;

  $guest['homecity'] = $customer['homecity'];
}

// ===========================Code For Customer logout====================
$sql = "SELECT * FROM `guest` where `islogedin`=0 AND (user_lat!='' AND user_lat!='0') AND (user_long!='' AND user_long!='0')";
$result = mysqli_query($db, $sql);
$logedout_customers = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($logedout_customers as &$guest)
{
  $sql = "SELECT * FROM `order` WHERE order_id='{$guest['login_id']}'";
  $result = mysqli_query($db, $sql);
  $order = mysqli_fetch_assoc($result);
  if (! $order) continue;

  $sql = "SELECT * FROM `customer` WHERE id={$order['customer_id']}";
  $result = mysqli_query($db, $sql);
  $customer = mysqli_fetch_assoc($result);
  if (! $customer) continue;

  $guest['homecity'] = $customer['homecity'];
}

?>

<?php

include('../includes/header.php');

?>

<!DOCTYPE html>

<html> 




      <div id="content-wrapper">

       <div class="container-fluid">

    

    <div class="col-md-12">

    <h3>Map</h3>

    <hr>

     </div> 

   

   

     <div class="container">


    

   

    <!--div id="map"></div-->
    <!--<embed src="http://18.117.240.68:8080"  style="width:60vw; height:100vh>-->
    <button class="btn btn-success" onClick="openMap()"> Map </button>

        <!--<iframe src="http://18.117.240.68:8080/" style="width:60vw; height:100vh"></iframe>-->









 

 

  </div>

</form>



</div >

</div>

</div>

       



     



    </div>

   

    <!-- Logout Modal-->

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

      <div class="modal-dialog" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>

            <button class="close" type="button" data-dismiss="modal" aria-label="Close">

              <span aria-hidden="true">×</span>

            </button>

          </div>

          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>

          <div class="modal-footer">

            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

            <a class="btn btn-primary" href="login.html">Logout</a>

          </div>

        </div>

      </div>

    </div>









<head> 

  <meta http-equiv="x-ua-compatible" content="IE=edge">

  <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 

  <title>Google Maps Multiple Markers</title> 

<!--   <script src="http://maps.google.com/maps/api/js?sensor=false" 

          type="text/javascript"></script> -->

         <!-- <script src="https://maps.google.com/maps/api/js?key=AIzaSyBc1MXhOP5ibzFXz_zryAaxQ-DZUxxXxI8&libraries=places"type="text/javascript"></script> -->

     

       

  <style type="text/css">
#map{
  width:100%;
  height:585px;
}
  </style>

</head> 

<body>



  <script type="text/javascript">
  
  function openMap(){
      
  const data = '<?php echo base64_encode($_SESSION['level']); ?>';
  
  const url = "http://18.117.240.68:8080?level="+data;
  
 // alert(url);
  
 // return;
  
   window.open(url,'_blank');
  }           

  </script>

  <script>
    
    // ===========================Start Real Code===============
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: {lat: 41.4925374, lng: -99.9018131}
         });

        function setTicketMarker(data) {
          var address = data.city;
          var geocoder = new google.maps.Geocoder();


          geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              var latitude = results[0].geometry.location.lat();
              var longitude = results[0].geometry.location.lng();

              var marker = new google.maps.Marker({
                position: {lat: latitude, lng: longitude},
                map: map,
                zIndex: 2
              });

              const contentString =
                '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Order ID: </b>'+ data.orderID +'</p>' +
                '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Customer Name: </b>'+ data.customer_name +'</p>' +
                '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Ticket Type: </b>'+ data.ticketType +'</p>' +
                "</div>";
              const infowindow = new google.maps.InfoWindow({
                content: contentString,
              });

              marker.addListener("click", () => {
                infowindow.open({
                  anchor: marker,
                  map,
                  shouldFocus: false,
                });
              });
            } 
          }); 
        }
        
        
        

        // function setLoggedInCustomerMarker(data) {
        //   var address = data.city;
        //   var geocoder = new google.maps.Geocoder();


        //   geocoder.geocode( { 'address': address}, function(results, status) {
        //     if (status == google.maps.GeocoderStatus.OK) {
        //       var latitude = results[0].geometry.location.lat();
        //       var longitude = results[0].geometry.location.lng();

        //       var marker = new google.maps.Marker({
        //         position: {lat: latitude, lng: longitude},
        //         map: map,
        //         icon: '../Images/login_marker_icon.png'
        //       });

        //       const contentString =
        //         '<div id="content">' +
        //         '<div id="siteNotice">' +
        //         "</div>" +
        //         '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Name: </b>'+ data.customer_name +'</p>' +
        //         '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Order ID: </b>'+ data.orderID +'</p>' +
        //         '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>City: </b>'+ data.city +'</p>' +
        //         "</div>";
        //       const infowindow = new google.maps.InfoWindow({
        //         content: contentString,
        //       });

        //       marker.addListener("click", () => {
        //         infowindow.open({
        //           anchor: marker,
        //           map,
        //           shouldFocus: false,
        //         });
        //       });
        //     } 
        //   }); 
        // }
        

        function setLoggedInCustomerMarker(data) {
          var latitude = data.lat;
          var longitude = data.lng;

          var marker = new google.maps.Marker({
            position: {lat: latitude, lng: longitude},
            map: map,
            icon: '../images/login_marker_icon.png',
            zIndex: 3
          });

          const contentString =
            '<div id="content">' +
            '<div id="siteNotice">' +
            "</div>" +
            '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Name: </b>'+ data.customer_name +'</p>' +
            '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Order ID: </b>'+ data.orderID +'</p>' +
            '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>City: </b>'+ data.city +'</p>' +
            "</div>";
          const infowindow = new google.maps.InfoWindow({
            content: contentString,
          });

          marker.addListener("click", () => {
            infowindow.open({
              anchor: marker,
              map,
              shouldFocus: false,
            });
          }); 
        }


        function setLoggedOutCustomerMarker(data) {
          var latitude = data.lat;
          var longitude = data.lng;

          var marker = new google.maps.Marker({
            position: {lat: latitude, lng: longitude},
            map: map,
            icon: '../images/logout_marker_icon.png',
            zIndex: 1
          });

          const contentString =
            '<div id="content">' +
            '<div id="siteNotice">' +
            "</div>" +
            '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Name: </b>'+ data.customer_name +'</p>' +
            '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>Order ID: </b>'+ data.orderID +'</p>' +
            '<p id="firstHeading" class="firstHeading" style="font-size:13px;"><b>City: </b>'+ data.city +'</p>' +
            "</div>";
          const infowindow = new google.maps.InfoWindow({
            content: contentString,
          });

          marker.addListener("click", () => {
            infowindow.open({
              anchor: marker,
              map,
              shouldFocus: false,
            });
          });
        }

        //For assigned tickets
        var data = [
           <?php foreach ($orders as $order) { ?>
            {
              customer_name: '<?php echo $order['first_name'].' '.$order['last_name']; ?>',
              city: '<?php echo $order['homecity']; ?>',
              orderID: '<?php echo $order['order_id']; ?>',
              ticketType: '<?php echo $order['ticket_type']; ?>',
            },
           <?php } ?>
        ];

        for (let i = 0; i < data.length; ++i)
          setTicketMarker(data[i]);

        // For logedin in customers
        data = [
           <?php foreach ($logedin_customers as $customer) { 
             if(!empty($customer['user_lat']) && !empty($customer['user_long'])):
             ?>
            {
              customer_name: '<?php echo $customer['guest_name'] ?? ''; ?>',
              orderID: '<?php echo $customer['login_id'] ?? ''; ?>',
              city: '<?php echo $customer['homecity'] ?? ''; ?>',
              lat: <?php echo $customer['user_lat'] ?? 0; ?>,
              lng: <?php echo $customer['user_long'] ?? 0; ?>,
            },
           <?php endif;
           } ?>
        ];

        for (let i = 0; i < data.length; ++i)
          setLoggedInCustomerMarker(data[i]);

        // For logedout in customers
        data = [
           <?php foreach ($logedout_customers as $customer) { ?>
            {
              customer_name: '<?php echo $customer['guest_name'] ?? ''; ?>',
              orderID: '<?php echo $customer['login_id'] ?? ''; ?>',
              city: '<?php echo $customer['homecity'] ?? ''; ?>',
              lat: <?php echo $customer['user_lat'] ?? 0; ?>,
              lng: <?php echo $customer['user_long'] ?? 0; ?>,
            },
           <?php } ?>
        ];

        for (let i = 0; i < data.length; ++i)
          setLoggedOutCustomerMarker(data[i]);

      }
    
    // ===========================End of Real Code============
   
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAI0TJsSinxPjQXjFj9yDm0bvgHjHN9WsM&callback=initMap">
    </script>

</body>

</html>