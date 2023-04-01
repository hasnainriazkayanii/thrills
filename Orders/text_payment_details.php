<?php
include('../Config/Connection.php');
session_start();
require_once "../libraries/vendor/autoload.php";
use Twilio\Rest\Client;
include "../Config/twilio.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$message = $_POST['message'];
	$customer_id = $_POST['customer_id'];

	$sql = "SELECT * FROM `customer` WHERE `id`=$customer_id";
	$result = mysqli_query($db, $sql);
	$customer = mysqli_fetch_assoc($result);

	$client = new Client($account_sid, $auth_token);
    try
    {
        $client->messages->create(
            $customer['country_code'].$customer['Phone_number'],
            array(
                'from' => $twilio_number,
                'body' => $message
            )
        );
      

        // Store message in database
        $sql = "INSERT INTO `massages` (contact_no, message, type) VALUES ('{$customer['Phone_number']}', '$message', 'sent')";
        $result = mysqli_query($db, $sql);
        
       
    }
    catch (Exception $e) { }

    header( "Location: Orderdetails.php?active=0&sucess=0" );
}

if (! isset($_GET['order_id']) && !isset($_GET['account_id']))
{
	http_response_code(404);
	die();
}

$order_id = $_GET['order_id'];
$account_id = $_GET['account_id'];

$sql = "SELECT * FROM `order` WHERE `order_id`='$order_id'";
$result = mysqli_query($db, $sql);
$order = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM `theme_parks` WHERE `id`={$order['theme_park_id']}";
$result = mysqli_query($db, $sql);
$theme_park = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM `accounting` WHERE `id`='$account_id'";
$result = mysqli_query($db, $sql);
$account = mysqli_fetch_assoc($result);

$message .= "Receipt # ".$account['id']." \n";
$message .= "Order # ".$order['order_id']." \n";
$message .= $order['customer']." \n\n";

$message .= $theme_park['name']." \n";

if($order['adults']>0 && $order['kids']==0 ){
    if($order['adults']==1){
     $message .= $order['adults']." adult \n";

   }
   else{
     $message .= $order['adults']." adults \n";
   }
}
else if($order['adults']==0 && $order['kids']>0 ){
    if($order['kids']==1){
     $message .= $order['kids']." kid \n";

   }
   else{
     $message .= $order['kids']." kids \n";
   }
   
}
else{
   if($order['kids']==1 && $order['adults']==1){
     $message .= $order['adults']." adult/".$order['kids']." kid \n";

   }
   else if($order['kids']==1 ){
     $message .= $order['adults']." adults/".$order['kids']." kid \n";

   }
   else if($order['adults']==1 ){
     $message .= $order['adults']." adult/".$order['kids']." kids \n";

   }
   else{
     $message .= $order['adults']." adults/".$order['kids']." kids \n";
   }
   
}
$message .= date("D M d",strtotime($order['date_of_visit'])).", ".date("g:i A",strtotime($order['time']))." \n\n";

$message .= "Payment: $".$account['paymentAmount']." \n";
//$message .= "Deposit (or payment or final payment for options) \n";

$message .= "
DO NOT REPLY";

?>
<?php include('../includes/header.php'); ?>
<body onLoad="my_form.submit();" style="visibility: hidden;">
<!-- Sticky Footer -->
<div id="content-wrapper">
    

     <div class="container-fluid">
      
      	<div class="container">
      		<h3>Send Message</h3>
      		<hr>
      		<form method="post" id="my_form">
      			<input type="hidden" name="customer_id" value="<?php echo $order['customer_id']; ?>">
      			<div>
	      			<textarea rows="8" cols="84" required="" name="message" id="message" class="form-control"><?php echo $message; ?></textarea>
	      		</div>
	      		<div class="mt-4">
	      			<div class="form-group">
                <?php if (isset($_REQUEST['added_order'])): ?>
                  <a href="Orderdetails.php?active=0&sucess=0" name="submitticket" class="btn btn-warning mr-4">Skip</a>
                <?php else: ?>
                  <a href="Orderdetails.php?active=0" name="submitticket" class="btn btn-warning mr-4">Cancel</a>
                <?php endif; ?>
						<button type="submit" name="submitticket" id="submitticket" class="btn btn-primary" onclick="disable_btn()">Send</button>
					</div>
	      		</div>
      		</form>
      	</div>

        <footer class="sticky-footer">

          <div class="container my-auto">

            <div class="copyright text-center my-auto">

              <!-- <span>Copyright © Universal Orlando Resort 2018</span> -->

            </div>

          </div>

        </footer>

   

      </div>

      <!-- /.content-wrapper -->



    </div>

    <!-- /#wrapper -->



    <!-- Scroll to Top Button-->

    <a class="scroll-to-top rounded" href="#page-top">

      <i class="fas fa-angle-up"></i>

    </a>



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

        

      </div>

    </div>

  </body>
  
  
  <script type="text/javascript">
 function disable_btn(){
  $('#submitticket').hide();
 }
 
 function submitonload() {
document.getElementById('my_form').submit();
</script>
</html>




 