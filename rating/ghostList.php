<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	//----------------------------------------------------------------------------------------------
	function updateStudent()
	{
		$first_name = $_POST['firstName'];
		$middle_name = $_POST['middleName'];
		$last_name = $_POST['lastName'];
		$group_id = $_POST['group'];
		$data = pg_query("UPDATE students SET first_name='$first_name', middle_name='$middle_name', last_name='$last_name', group_id=$group_id WHERE id=".$_POST['student_id']);
	}
	updateStudent();
	header('Location:editStudentForm.php');
?>
