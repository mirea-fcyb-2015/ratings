<?php
	include_once '../utils.php';
	printHeader('', '', '', array('jquery.js'));
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres") or die('Could not connect: ' . pg_last_error());
	$teachers = pg_query("SELECT * FROM teachers");
	$teachers = pg_fetch_all($teachers);
	$report_id = $_POST['index_of_report'];
	
	foreach ($report_id as $rep_id) 
	{
		$index_of_report[] = $rep_id;
	}

	$groups = pg_query("SELECT * FROM groups");
	$groups = pg_fetch_all($groups);
	$disc = pg_query("SELECT * FROM disc WHERE id =".$_POST['index_of_disc']);
	$disc = pg_fetch_all($disc);
	$type = pg_query("SELECT type FROM report JOIN gr_disc_rep ON (report.id = gr_disc_rep.report_id) WHERE gr_disc_rep.disc_id =".$_POST['index_of_disc']);
	$type = pg_fetch_all($type);

	foreach ($type as $t) 
	{
		$type_array[] = $t['type'];
	}
//----------------------------------------------------------------------------------------------------------------------
?>
	<table>
		<tr><td>
			<tr><h2>Редактировать отчетность</h2></tr>
		</td></tr>

		<tr><td>
			<form method="post" action="editReport.php">
				<table>

					<script type="text/javascript">
						function checked_1(attr)
						{
							document.getElementById('zach').checked = false;
							document.getElementById('d_zach').checked = false;
							if(attr == 1) {
								document.getElementById('zach').checked = true;
								document.getElementById('d_zach').checked = false;
							}
							
							if(attr == 2) {
								document.getElementById('zach').checked = false;
								document.getElementById('d_zach').checked = true;
							}
						}
						
						function checked_2(attr)
						{
							if(attr == 3) {
								document.getElementById('course_work').checked = true;
								document.getElementById('course_project').checked = false;
							}
							if(attr == 4) {
								document.getElementById('course_work').checked = false;
								document.getElementById('course_project').checked = true;
							}
						}
					</script>
					
					<tr>
						<td>Группа:</td>
						<td>
							<select style='width:250px' id="reportGroup" name="reportGroup">
<?php
							foreach ($groups as $g) 
							{
								echo "<option value='".$g['id']."' ".($g['id'] == $_POST['index_of_group'] ? 'selected' : '').">".$g['name']."</option>";
							}
?>
							</select>
						</td>
					</tr>

					<tr>
						<td>Дисциплина:</td>
						<td><?= $disc[0]['name'] ?></td>
					</tr>

					<tr>
					<td>Отчетность:z</td>
						<td>
<?php
							echo "<input type='checkbox' value='1' id='exam'".(in_array("1", $type_array) ? 'checked' : '')." name='report[1]'>Экзамен</input><br>";
							echo "<input type='checkbox' value='2' id='zach'".(in_array("2", $type_array) ? 'checked' : '')." onchange='checked_1(1)' name='report[2]'>Зачет</input><br>";
							echo "<input type='checkbox' value='3' id='d_zach'".(in_array("3", $type_array) ? 'checked' : '')." onchange='checked_1(2)' name='report[3]'>Дифференцированный зачет</input><br>";
							echo "<input type='checkbox' value='4' id='course_work'".(in_array("4", $type_array) ? 'checked' : '')." onchange='checked_2(3)' name='report[4]'>Курсовая работа</input><br>";
							echo "<input type='checkbox' value='5' id='course_project'".(in_array("5", $type_array) ? 'checked' : '')." onchange='checked_2(4)' name='report[5]'>Курсовой проект</input><br>";
?>
						</td>
					</tr>

					<tr>
						<td>
							<input type="hidden" name="disc_id" value="<?=$disc[0]['id']?>">
							<button type='submit'>Сохранить</button>
						</td>
					</tr>
				</table>
			</form>
		</td></tr>

		<tr><td>
			<form action='selectReportForm.php'>
				<button type='submit'>Назад</button>
			</form>
		</td></tr>
	</table>

<?php    
	//----------------------------------------------------------------------------------------------------------------------
	pg_close($dbconnect);
	//----------------------------------------------------------------------------------------------------------------------
	printFooter();
?>
