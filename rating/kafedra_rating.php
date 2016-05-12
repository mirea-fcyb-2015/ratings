<?php
	include_once '../utils.php';
	printHeader();
	//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres") or die ('Could not connect: ' . pg_last_error());
	//----------------------------------------------------------------------------------------------------------------------
  $stud_info = pg_query("SELECT last_name, first_name, round(AVG(CAST(value AS integer)), 1) d, student_id, name FROM students INNER JOIN mark ON (students.id = mark.student_id) INNER JOIN groups ON (groups.id = students.group_id) GROUP BY last_name, first_name, student_id, name ORDER BY d DESC") or die('Ошибка запроса: ' . pg_last_error());
	$stud_info = pg_fetch_all($stud_info);
?>
	<html>    
		<table border=1>
			<tr><td>Фамилия</td><td>Имя</td><td>Группа</td><td>Средний балл</td></tr>
<?php
			foreach ($stud_info as $col_value)
			{
				echo "\t\t<tr><td>".$col_value['last_name']."</td>\n";
				echo "\t\t<td>".$col_value['first_name']."</td>\n";
				echo "\t\t<td>".$col_value['name']."</td>\n";
				echo "\t\t<td>".$col_value['d']."</td></tr>\n";
			}
?>
		</table>

		<table>
			<tr><td>
				<form action='index.php'>
					<button type='submit'>Назад</button>
				</form>
			</td></tr>
		</table>
	</html>

<?php    
	pg_close($dbconnect);
	printFooter();
?>
