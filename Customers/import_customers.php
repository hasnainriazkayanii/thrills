<?php

include('../Config/Connection.php');

session_start();

$login_check=$_SESSION['id'];
  

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}


// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if ($_FILES['csv_file']['name'])
	{
		$csvMimes = array(
		    'text/csv',
		    'text/plain',
		    'application/csv',
		    'text/comma-separated-values',
		    'application/excel',
		    'application/vnd.ms-excel',
		    'application/vnd.msexcel',
		    'text/anytext',
		    'application/octet-stream',
		    'application/txt',
		);
		
		if (in_array($_FILES['csv_file']['type'], $csvMimes))
		{
			$csv_file = fopen($_FILES["csv_file"]['tmp_name'], 'r');
			$field_names = fgetcsv($csv_file); // Get fields names from csv file

			$counter = 0;

			while (! feof($csv_file))
			{
				$record = fgetcsv($csv_file); // Get a single record from csv file

				// Get fields indexes
				$phone_index  	  = array_search("Phone", $field_names);
				$first_name_index = array_search("First Name", $field_names);
				$last_name_index  = array_search("Last Name", $field_names);
				$email_index 	  = array_search("Email", $field_names);
				$tags_index 	  = array_search("Tags", $field_names);

				// Get fields values from record
				$phone 		= $record[$phone_index];
				$first_name = $record[$first_name_index];
				$last_name 	= $record[$last_name_index];
				$email 		= $record[$email_index];
				$tags 		= $record[$tags_index];

				$date_added = date('m/d/Y'); 

				// Store fields values in database
				$sql = "INSERT INTO `customer` (customer_id, first_name, Last_name, Phone_number, last_visit, homecity, ethnicity, date_added, Email, Notes) VALUES (0, '$first_name', '$last_name', '$phone', '', '', '', '$date_added', '$email', '$tags')";
				$result = mysqli_query($db, $sql);

				++$counter;
			}
			
			$success = "<b>$counter</b> Customers added successfully";
		}
		else
		{
			$error = "Please select <b>CSV</b> file. The file you selected was not a <b>CSV</b> file.";
		}
	}
	else
	{
		$error = "You didn't select any file. Please select a <b>CSV</b> file to upload.";
	}
}


include('../includes/header.php');

?>

<div id="content-wrapper">
    <div class="container-fluid">
    	<?php if (isset($error)): ?>
    		<div class="alert alert-danger alert-dismissible mb-4">
			    <button type="button" class="close" data-dismiss="alert">&times;</button>
			    <?php echo $error; ?>
			</div>
    	<?php endif; ?>

    	<?php if (isset($success)): ?>
    		<div class="alert alert-success alert-dismissible mb-4">
			    <button type="button" class="close" data-dismiss="alert">&times;</button>
			    <?php echo $success; ?>
			</div>
    	<?php endif; ?>

    	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    		<div class="form-group">
    			<label>Upload CSV file</label>
    			<input type="file" name="csv_file">
    		</div>
    		<button type="submit" class="btn btn-primary">Upload</button>
    	</form>
    </div>
</div>
