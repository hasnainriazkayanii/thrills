<?php

include('../Config/Connection.php');

session_start();
$login_check=$_SESSION['id'];


 //var_dump($data1);

if ($login_check!='1') {
 $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location:http://cheapthrillstix.com/app/appadmin/Login/login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$settings = array();

	$admin_mobile = trim($_POST['admin_mobile']);
	$settings['admin_mobile'] = $admin_mobile;


	foreach ($settings as $name => $value)
	{
		$sql = "SELECT * FROM `settings` WHERE name='$name'";
		$result = mysqli_query($db, $sql);
		$property = mysqli_fetch_assoc($result);

		if ($property)
		{
			$sql = "UPDATE `settings` SET value='$value' WHERE name='$name'";
			$result = mysqli_query($db, $sql);
		}
		else
		{
			$sql = "INSERT INTO `settings` (name, value) VALUES ('$name', '$value')";
			$result = mysqli_query($db, $sql);
		}
	}
}


// Admin Mobile
$sql = "SELECT * FROM `settings` WHERE name='admin_mobile'";
$result = mysqli_query($db, $sql);
$property = mysqli_fetch_assoc($result);
if ($property)
	$admin_mobile = $property['value'];
else
	$admin_mobile = "";

include('../includes/header.php');
?>

<div id="content-wrapper">
	<div class="container-fluid">
		<div class="row">
	      <div class="col-md-12">
	      	<div class="col-md-8 new-header" style="float:left;">
	      		<h3 class="new-fonts">SMS Settings</h3>
	      	</div>
	      	<div class="col-md-4 text-right new-header" style="float:left;">
	      		<a href="../Customers/AddCustomer.php" class="btn btn-danger">Back to Settings</a>
	      	</div>
	      </div>
	  	</div>

	  	<hr>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="p-4 mt-4">
			<div class="d-flex">
				<div class="form-group mr-4">
					<label class="font-weight-bold">Admin Mobile</label>
					<input type="text" value="<?php echo $admin_mobile; ?>" name="admin_mobile">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</form>
	</div>
</div>