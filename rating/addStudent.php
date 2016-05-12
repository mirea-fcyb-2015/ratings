<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------------------------------        
	function addData($last_name, $middle_name, $first_name, $group_id) {
		$data = pg_query("INSERT INTO students (first_name, middle_name, last_name, group_id) VALUES ('".$first_name."','".$middle_name."','".$last_name."','".$group_id."')");
		//$data = pg_fetch_all($data);
	}

	addData($_POST['studentLastName'], $_POST['studentMiddleName'], $_POST['studentFirstName'], $_POST['studentGroup']);
//----------------------------------------------------------------------------------------------------------------------    
	pg_close($dbconnect);
//----------------------------------------------------------------------------------------------------------------------
	header('Location:addStudentForm.php');
?>
