<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	$index = pg_query("SELECT nextval('report_id_seq') new_id");
	$index = pg_fetch_all($index);
//----------------------------------------------------------------------------------------------------------------------        
	function addDataReport($type)
	{
		$data = pg_query("INSERT INTO report (type) VALUES ('".$type."') RETURNING id");
		$data = pg_fetch_assoc($data);
		return $data['id'];
	}

	foreach($_POST['report'] as $t) {
		$ids[] = addDataReport($t);
	}

	function addDataGr_disc($group, $disc, $new_rep_id)
	{
		$data = pg_query("INSERT INTO gr_disc_rep (group_id, disc_id, report_id) VALUES ('".$group."', '".$disc."', '".$new_rep_id."')");
	}

	foreach ($ids as $id) {
		addDataGr_disc($_POST['reportGroup'], $_POST['reportDisc'], $id);    
	}
//----------------------------------------------------------------------------------------------------------------------    
	pg_close($dbconnect);
//----------------------------------------------------------------------------------------------------------------------
	header('Location:addReportForm.php');
?>
