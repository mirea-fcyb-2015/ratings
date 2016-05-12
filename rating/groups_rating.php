<?php
	include_once '../utils.php';
	printHeader('', '', '', array('jquery.js'));
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	$groups_info = pg_query("SELECT id, name FROM groups");
	$groups_info = pg_fetch_all($groups_info);

	$stud_info = pg_query("SELECT last_name, first_name, group_id, round(AVG(CAST(value AS integer)), 1) d, student_id FROM students INNER JOIN mark ON (students.id = mark.student_id) GROUP BY last_name, first_name, student_id, group_id ORDER BY d DESC") or die('Ошибка запроса: ' . pg_last_error());
	$stud_info = pg_fetch_assoc($stud_info);
?>

	<script type="text/javascript">
		function fillByGroup(grindex)
		{
			var groupnum = 'grnum=' + document.getElementById('grnum').value + '&action=groupnum' + '&last_name=' + '<?=urlencode($stud_info['last_name'])?>' + '&first_name=' + '<?=urlencode($stud_info['first_name'])?>';
			document.getElementById('studList').innerHTML = "<table><tr><td>Фамилия</td><td>Имя</td><td>Курс</td><td>Группа</td><td>Средний балл</td></tr>";
			$.ajax({
				url:'aj_action.php',
				data:groupnum,
				success: function(t)
				{
					document.getElementById('studList').innerHTML = t;
				}
			});    
		}
	</script>

<?php
	echo "Выбор группы: <select id='grnum' onchange='fillByGroup(this.value)'>";
	echo "<option value=''></option>";
	
	foreach($groups_info as $g_info)
	{
		echo "<option name='groups' value='".$g_info['id']."'>".$g_info['name']."</option>";
	}
	echo "</select>";

	echo "<table id='studList' border=1>\n";
	echo "</table>\n";
?>

	<html>
		<table>
			<tr><td>
				<form action='index.php'>
					<button type='submit'>Назад</button>
				</form>
			</td></tr>
		</table>
	</html>

<?php
	printFooter();
?>
