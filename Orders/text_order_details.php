<?php
include('../Config/Connection.php');
session_start();
require_once "../libraries/vendor/autoload.php";
use Twilio\Rest\Client;
include "../Config/twilio.php";

$login_check = $_SESSION['id'];

if ($login_check != '1') {
    $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}
$hideorder = $_GET['is_hide'];
//var_dump($hideorder);

$login_status = $_SESSION['status'];

if ($login_status == '0') {
    $readonly ="readonly";
} else {
    $readonly = "";
}

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

if (! isset($_GET['order_id']))
{
	http_response_code(404);
	die();
}

$order_id = $_GET['order_id'];

include  'confirmed_text_message_template.php';


?>
<?php include('../includes/header.php'); ?>
<!-- Sticky Footer -->
<div id="content-wrapper">
    

     <div class="container-fluid">
      
      	<div class="container">
      		<h3>Send Message</h3>
      		<hr>
      		<form method="post">
      			<input type="hidden" name="customer_id" value="<?php echo $order['customer_id']; ?>">
      			<div>
	      			<textarea rows="8" cols="84" required="" name="message" id="message" class="form-control" <?php echo $readonly ?>><?php echo $message; ?></textarea>
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
  
  
  <script>
 function disable_btn(){
  $('#submitticket').hide();
 }
</script>
</html>




