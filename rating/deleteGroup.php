<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	//----------------------------------------------------------------------------------------------
	function deleteGroup()
	{
		$data = pg_query("DELETE FROM gr_disc_tech WHERE group_id=".$_POST['group_id']);
	}
	deleteGroup();
	header('Location:ghostListDisc.php');
?>
