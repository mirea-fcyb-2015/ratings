<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------------------------------        
	function deleteReportFromGrDisc ($id = '')
	{
		foreach ($_POST['index_of_report'] as $id) 
		{
			$data = pg_query("DELETE FROM gr_disc_rep WHERE report_id=".$id);
		}
	}
	deleteReportFromGrDisc($id);

	function deleteReportFromReport ($id = '')
	{
		foreach ($_POST['index_of_report'] as $id) 
		{
			$data = pg_query("DELETE FROM report WHERE id=".$id);
		}

	}    
	deleteReportFromReport($id);
//----------------------------------------------------------------------------------------------------------------------    
	pg_close($dbconnect);
//----------------------------------------------------------------------------------------------------------------------
	header('Location:selectReportForm.php');
?>
