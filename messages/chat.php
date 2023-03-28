<?php
session_start();
include('../Config/Connection.php');

$login_check=$_SESSION['id'];

if ($login_check!='1') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
         $full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       $_SESSION['intended_url'] = $full_url;
        header("location: ../Login/login.php");
    }

if (!isset($_REQUEST['member_id']))
{
	http_response_code(404);
	die();
}

$member_id = $_REQUEST['member_id'];

// Get Member
$sql = "SELECT * FROM `customer` WHERE id=$member_id";
$result = mysqli_query($db, $sql);
$member = mysqli_fetch_assoc($result);


// Get Messages
$sql = "SELECT * FROM `massages` WHERE contact_no={$member['Phone_number']} ORDER BY created_at ASC";
$result = mysqli_query($db, $sql);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($messages as $message)
{
	if ($message['seen'] === 1)
		continue;

	$sql = "UPDATE `massages` SET seen=1 WHERE id={$message['id']}";
	$result = mysqli_query($db, $sql);
}


// Get Pre defined text messages
$sql = "SELECT * FROM `text_messages`";
$result = mysqli_query($db, $sql);
$predefined_messages = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<?php include('../includes/header.php'); ?>

<style type="text/css">

body {
	overflow: hidden;
	overflow-y: scroll;
}

.content {
	width: 100%;
}

.chat-online {
    color: #34ce57
}

.chat-offline {
    color: #e4606d
}

.chat-messages {
    display: flex;
    flex-direction: column;
    max-height: 730px;
    overflow-y: scroll
}

.chat-message-left,
.chat-message-right {
    display: flex;
    flex-shrink: 0
}

.chat-message-left {
    margin-right: auto
}

.chat-message-right {
    flex-direction: row-reverse;
    margin-left: auto
}
.py-3 {
    padding-top: 1rem!important;
    padding-bottom: 1rem!important;
}
.px-4 {
    padding-right: 1.5rem!important;
    padding-left: 1.5rem!important;
}
.flex-grow-0 {
    flex-grow: 0!important;
}
.border-top {
    border-top: 1px solid #dee2e6!important;
}

</style>


<main class="content">
    <div class="container p-0">
		<div class="card h-100">
			<div class="row g-0">
				<div class="col-12">
					<div class="py-2 px-4 border-bottom d-lg-block">
						<div class="d-flex align-items-center py-1">
							<div class="flex-grow-1 pl-3">
								<strong><?php echo $member['first_name'] . ' ' . $member['Last_name']; ?></strong>
								<br>
								<span>+1<?php echo $member['Phone_number']; ?></span>
							</div>
							<div>
								<a href="index.php" class="btn btn-danger">Back to Messages</a>
								<!-- <button class="btn btn-primary btn-lg mr-1 px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone feather-lg"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></button>
								<button class="btn btn-info btn-lg mr-1 px-3 d-none d-md-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video feather-lg"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></button>
								<button class="btn btn-light border btn-lg px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal feather-lg"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button> -->
							</div>
						</div>
					</div>

					<div class="position-relative">
						<div class="chat-messages p-4">

							<?php foreach ($messages as $message) { ?>
								<?php if ($message['type'] === 'sent'): ?>
								<div class="chat-message-right pb-4">
									<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
										<div class="d-flex justify-content-between font-weight-bold mb-1">
											<div class="text-muted small text-nowrap"><?php echo change_timezone($message['created_at'], 'America/Chicago', 'America/New_York')->format('g:i a'); ?></div>
											<div class="pl-1">Admin</div>
										</div>
										<?php echo str_replace("\n", "<br>", $message['message']); ?>
									</div>
								</div>
								<?php else: ?>
								<?php if ($message['seen'] === '0' && !isset($unread_messages)): ?>
									<?php $unread_messages = true; ?>
									<div class="mb-2">
										<hr class="mb-0">
										<div class="text-primary text-center">New unread messages</div>
									</div>
								<?php endif; ?>
								<div class="chat-message-left pb-4">
									<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
										<div class="d-flex justify-content-between font-weight-bold mb-1">
											<div class="pr-1"><?php echo $member['first_name'] . ' ' . $member['Last_name']; ?></div>
											<div class="text-muted small text-nowrap"><?php echo change_timezone($message['created_at'], 'America/Chicago', 'America/New_York')->format('g:i a'); ?></div>
										</div>
										<?php echo str_replace("\n", "<br>", $message['message']); ?>
									</div>
								</div>
								<?php endif; ?>
							<?php } ?>

						</div>
					</div>

					<div class="flex-grow-0 py-3 px-4 border-top">
						<div class="input-group">
							<select id="predefined-message">
								<option value="" selected>Select Message</option>
								<?php foreach ($predefined_messages as $msg) { ?>
									<option value="<?php echo $msg['message']; ?>"><?php echo $msg['title']; ?></option>
								<?php } ?>
							</select>
							<textarea class="form-control" placeholder="Type your message" name="message" id="message"></textarea>
							<button class="btn btn-primary" id="send-message">Send</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>


<script>
	const  message = document.getElementById('message');
	const sendBtn = document.getElementById('send-message');
	const chatMessages = document.getElementsByClassName('chat-messages')[0];

	// Scroll down to the latest messages
	chatMessages.scrollTop = chatMessages.scrollHeight;

	sendBtn.addEventListener('click', function () {
		memberID = <?php echo $member['id']; ?>;
		sendMessage(memberID, message);
		message.value = '';
	});

	const messageUpdateInterval = setInterval(updateMessagesDisplay, 1000);

	function sendMessage(memberID, message)
	{
		$.ajax({
	        url: 'ajax_send_message.php',
	        method: 'POST',
	        data: {member_id: memberID, message: message.value},
	        success: function (res) {
	        	res = JSON.parse(res);
	        	if (res.status === false) {
	        		switch(res.errorCode) {
	        			case 21610:
	        				alert("Message couldn't be send to this user!\nThis user has blocked you.");
	        				deleteMember(memberID);
	        				break;
	        		}
	        	}
	        	//updateMessagesDisplay();
	        	setTimeout(function () {
	        		chatMessages.scrollTop = chatMessages.scrollHeight;
	        	}, 1000);
	        }
	     });
	}
	function deleteMember(id){
		$.ajax({
			url: 'delete_customer.php?id='+id,
			success: function(){
				alert ("This member has been deleted");
				window.location="index.php";
			}
		});

	}

	function updateMessagesDisplay()
	{
		let phoneNumber = '<?php echo $member['Phone_number']; ?>';
		$.ajax({
	        url: 'manage_state.php?action=get_new_messages&mobile_number='+phoneNumber,
	        success: function (res) {
	        	$('.chat-messages').append(res);
	        }
	    });
	}


	// Code for selecting predefined messages
	(function () {
		const predefinedMessage = document.getElementById('predefined-message');
		predefinedMessage.addEventListener('change', function () {
			message.value = this.value;
		});
	})();


</script>

<?php

function change_timezone($date, $from, $to)
{
	$date = new DateTime($date, new DateTimeZone($from));
	$date->setTimezone(new DateTimeZone($to));
	return $date;
}

?>

