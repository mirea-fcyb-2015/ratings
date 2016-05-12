<?php
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	$index = pg_query("SELECT nextval('disc_id_seq') new_id");
	$index = pg_fetch_all($index);

	foreach($index as $i) {
		$discid = $i['new_id']; 
	}

	//----------------------------------------------------------------------------------------------------------------------        
	function addDataDisc($discid, $name) 
	{
		$data = pg_query("INSERT INTO disc (id, name) VALUES ('".$discid."', '".$name."')");
		//$data = pg_fetch_all($data);
	}
	addDataDisc($discid, $_POST['discName']);

	function addDataGrDisc($group_id, $discid, $teacher_id)
	{
		$data = pg_query("INSERT INTO gr_disc_tech (group_id, disc_id, teacher_id) VALUES ('".$group_id."', '".$discid."', '".$teacher_id."')");
	}
	
	foreach ($_POST['discGroup'] as $id) {
		addDataGrDisc($id, $discid, $_POST['discTeacher']);    
	}    
	//----------------------------------------------------------------------------------------------------------------------    
	pg_close($dbconnect);
	//----------------------------------------------------------------------------------------------------------------------
	header('Location:discForm.php');
?>
