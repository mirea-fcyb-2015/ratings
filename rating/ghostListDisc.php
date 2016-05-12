<?php
	include_once'../utils.php';
	printHeader('', '', '', array('jquery.js'));

	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die ('Could not connect: ' . pg_last_error());
	$disc = pg_query("SELECT id, name FROM disc WHERE id=".$_POST['disc_id']);
	$disc = pg_fetch_all($disc);
	$groups1 = pg_query("SELECT groups.id id, groups.name FROM gr_disc_tech JOIN groups ON (groups.id = gr_disc_tech.group_id) WHERE disc_id=".$_POST['disc_id']);
	$groups1 = pg_fetch_all($groups1);

	if(!$disc) {
		print("Table is empty");
	}
	echo "<form action='editDisc.php' method='POST'>";
	
	foreach ($disc as $d) 
	{
		echo "<input name='disc_id' type='hidden' value='".$d['id']."'></input>";
		echo "<table>";
		echo "<tr><td>Название:</td><td><input name='discName' style='width:200px' type='text' value='".$d['name']."'><button>Переименовать</button></input></td></tr>";
		echo "</table></form>";

		echo "<table border=1>";
		echo "<tr><td>Группа</td><td>Удаление</td></tr>";

		foreach ($groups1 as $k)
		{
			echo "<tr><td>".$k['name']."</td>";
			echo "<td><form action='deleteGroup.php' method='POST'>";
			echo "<input type='hidden' name='group_id' value='".$k['id']."'></input>";
			echo "<input type='submit' value='Удаление'></input>";
			echo "</td></tr>";
		}
		echo "</table></form>";
	}
	printFooter();
?>
