<?php
session_start();

$login_check=$_SESSION['id'];

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}

if (! isset($_REQUEST['action']))
{
	http_response_code(404);
	die();
}

$action = $_REQUEST['action'];

if (! function_exists('action_'.$action))
{
	http_response_code(404);
	die();
}

$action = 'action_'.$action;
$action();

function action_add_member()
{
	if (!isset($_REQUEST['member_id']) || empty($_REQUEST['member_id']))
		return false;

	$member_id = $_REQUEST['member_id'];

	if (in_array($member_id, $_SESSION['message_system']['selected_members']))
		return true;

	$_SESSION['message_system']['selected_members'][] = $member_id;
	return true;

}

function action_remove_member()
{
	if (!isset($_REQUEST['member_id']) || empty($_REQUEST['member_id']))
		return false;

	$member_id = $_REQUEST['member_id'];

	if (! in_array($member_id, $_SESSION['message_system']['selected_members']))
		return true;

	$key = array_search($member_id, $_SESSION['message_system']['selected_members']);
	unset($_SESSION['message_system']['selected_members'][$key]);
	$_SESSION['message_system']['selected_members'] = array_values($_SESSION['message_system']['selected_members']);
	return true;
}

function action_save_message()
{
	if (!isset($_REQUEST['message']) || empty($_REQUEST['message']))
		return false;

	$message = $_REQUEST['message'];

	$_SESSION['message_system']['message'] = $message;
}

function action_remove_message()
{
	if (isset($_SESSION['message_system']['message']))
		unset($_SESSION['message_system']['message']);
}

function action_get_new_messages()
{
	if (!isset($_REQUEST['mobile_number']) || empty($_REQUEST['mobile_number']))
		return false;

	include('../Config/Connection.php');

	$mobile_number = $_REQUEST['mobile_number'];

	$sql = "SELECT * FROM `customer` WHERE Phone_number='$mobile_number'";
	$result = mysqli_query($db, $sql);
	$member = mysqli_fetch_assoc($result);

	$sql = "SELECT * FROM `massages` WHERE contact_no='$mobile_number' AND seen=0 ORDER BY created_at ASC";
	$result = mysqli_query($db, $sql);
	$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);

	foreach ($messages as $message)
	{
		if ($message['seen'] === 1)
			continue;

		$sql = "UPDATE `massages` SET seen=1 WHERE id={$message['id']}";
		$result = mysqli_query($db, $sql);
	}

	?>

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

	<?php
}

function action_display_state()
{
	echo '<pre>';
	print_r($_SESSION['message_system']);
	echo '<pre>';
	exit();
}

function change_timezone($date, $from, $to)
{
	$date = new DateTime($date, new DateTimeZone($from));
	$date->setTimezone(new DateTimeZone($to));
	return $date;
}
