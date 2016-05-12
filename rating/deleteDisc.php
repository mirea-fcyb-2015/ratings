<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------
	function deleteDisc()
	{
		$data = pg_query("DELETE FROM disc WHERE id=".$_POST['disc_id']);
	}
	deleteDisc();
	header('Location:ghostListDisc.php');
?>
