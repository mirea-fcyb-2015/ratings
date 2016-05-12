<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	$report_index = pg_query("SELECT report_id FROM gr_disc_rep WHERE gr_disc_rep.disc_id=".$_POST['disc_id']);
	$report_index = pg_fetch_all($report_index);
//----------------------------------------------------------------------------------------------
	function checked_1($report_index)
	{
		foreach ($report_index as $id) 
		{
			pg_query("DELETE FROM report WHERE id =".$id["report_id"]);    
		}
		pg_query("DELETE FROM gr_disc_rep WHERE disc_id =".$_POST['disc_id']);

		foreach ($_POST['report'] as $rep) 
		{
			$data = pg_query("INSERT INTO report (type) VALUES ('".$rep."') RETURNING id");
			$data = pg_fetch_assoc($data);
			pg_query("INSERT INTO gr_disc_rep (group_id, disc_id, report_id) VALUES ('".$_POST['reportGroup']."', '".$_POST['disc_id']."', '".$data['id']."')");
		}
	}
	checked_1($report_index);
	header('Location:selectReportForm.php');
?>
