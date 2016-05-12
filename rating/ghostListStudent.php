<?php
	include_once'../utils.php';
	printHeader('', '', '', array('jquery.js'));

	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die ('Could not connect: ' . pg_last_error());
	$students = pg_query("SELECT students.id, name, first_name, middle_name, last_name, group_id FROM students JOIN groups ON (groups.id = students.group_id) WHERE students.id=".$_POST['student']) or die('Ошибка запроса: ' . pg_last_error());
	$students = pg_fetch_all($students);
	$groups = pg_query("SELECT id, name FROM groups");
	$groups = pg_fetch_all($groups);

	echo "<form action='ghostList.php' method='POST'>";
	
	foreach ($students as $s) 
	{
		echo "<input name='student_id' type='hidden' value='".$s['id']."'></input>";
		echo "<table>";
		echo "<tr><td>Фамилия:</td><td><input name='lastName' style='width:200px' type='text' value='".$s['last_name']."'></input></td></tr>";
		echo "<tr><td>Имя:</td><td><input name='firstName' style='width:200px' type='text' value='".$s['first_name']."'></input></td></tr>";
		echo "<tr><td>Отчество:</td><td><input name='middleName' style='width:200px' type='text' value='".$s['middle_name']."'></input></td></tr>";
		echo "<tr><td>Группа:</td><td><select style='width:200px' name='group'>";

		foreach ($groups as $g)
		{
			echo "<option value='".$g['id']."' ".($g['id'] == $s['group_id'] ? 'selected' : '').">".$g['name']."</option>";
		}
		echo "</select></td></tr>";
		echo "<tr><td><button>Сохранить</button></td></tr>";
		echo "</table></form>";
	}
	printFooter();
?>
