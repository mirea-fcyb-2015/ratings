<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------
	function updateDisc()
	{
		$name = $_POST['discName'];
		$data = pg_query("UPDATE disc SET name='$name' WHERE id=".$_POST['disc_id']);
	}
	updateDisc();
	header('Location:editDiscForm.php');
?>
