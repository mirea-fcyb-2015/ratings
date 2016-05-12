<?php
	include_once '../utils.php';
	printHeader();
	//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	//----------------------------------------------------------------------------------------------------------------------
	$stud_info = pg_query("SELECT last_name, first_name, round(AVG(CAST(value AS integer)), 1) d, student_id FROM students INNER JOIN mark ON (students.id = mark.student_id) GROUP BY last_name, first_name, student_id ORDER BY d DESC") or die('Ошибка запроса: ' . pg_last_error());
	$stud_info = pg_fetch_all($stud_info);
	$loggedUserId = 6;
	$stud = pg_query("SELECT last_name, first_name, round(AVG(CAST(value AS integer)), 1) d, student_id FROM students INNER JOIN mark ON (students.id = mark.student_id) WHERE student_id = ".$loggedUserId." GROUP BY last_name, first_name, student_id") or die('Ошибка запроса: ' . pg_last_error());    
	$stud = pg_fetch_assoc($stud);
	$users_stud = pg_query("SELECT user_type FROM users WHERE id=$loggedUserId");
	$users_stud = pg_fetch_assoc($users_stud);
	//var_dump($users_stud);
?>

	<html>    
		<table border=1>
			<tr><td>№</td><td>Фамилия</td><td>Имя</td><td>Средний балл</td></tr>
			<tr>
<?php
			$inTable = false;
			foreach ($stud_info as $n => $s) {
				$num = 1;
				if($n <= 2){
				if($users_stud['user_type'] == 2 && $s['student_id'] == $loggedUserId) {
							$inTable = TRUE;
							echo "<tr style='background-color:#cccccc'><td>".($n + 1)."</td>";
							echo "\t\t<td>".$s['last_name']."</td>\n";
							echo "\t\t<td>".$s['first_name']."</td>\n";
							echo "\t\t<td>".$s['d']."</td></tr>\n";
						} else {
							echo "<tr><td>".($n + 1)."</td>";
							echo "\t\t<td>".$s['last_name']."</td>\n";
							echo "\t\t<td>".$s['first_name']."</td>\n";
							echo "\t\t<td>".$s['d']."</td></tr>\n";  
						}
					continue;	
				}

				if($inTable)
					break;

				if($s['student_id'] == $loggedUserId){
						echo "<tr><td colspan='4' align='center'>...</td></tr>";
						echo "<tr style='background-color:#cccccc'><td>".($n+1)."</td>";
						echo "\t\t<td>".$s['last_name']."</td>\n";
						echo "\t\t<td>".$s['first_name']."</td>\n";
						echo "\t\t<td>".$s['d']."</td></tr>\n";
						break;
				} 
				}

?>
			</tr>
		</table>

<?php    
		pg_close($dbconnect);
?>

</br>

	<table>
		<tr><td>
<?php
		if($users_stud['user_type'] == 1) {
?>		
			<table>
				<tr><td>
					<form action='selectGroup.php'>
						<button type='submit'>Работа со студентами</button>
					</form>
				</td></tr>

				<tr><td>
					<form action='editDiscForm.php'>
						<button type='submit'>Работа с дисциплинами</button>
					</form>
				</td></tr>

				<tr><td>
					<form action='reportForm.php'>
						<button type='submit'>Работа с отчетностью</button>
					</form>
				</td></tr>
			</table>
			<?php
		}
		?>
		</td><td>
		<table>
				<tr><td>
					<form action='groups_rating.php'>
						<button type='submit'>Рейтинг по группам</button>
					</form>
				</td></tr>

				<tr><td>
					<form action='kafedra_rating.php'>
						<button type='submit'>Рейтинг по кафедре</button>
					</form>
				</td></tr>

				<tr><td>
					<form action='markForm.php'>
						<button type='submit'>Просмотр электронного журнала</button>
					</form>
				</td></tr>
			</table>
		</td>
		</tr>
	</table>
	</html>
<?php
	printFooter();
?>
