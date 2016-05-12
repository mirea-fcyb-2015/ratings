<?php
	include_once '../utils.php';

	printHeader('', '', '', array('jquery.js'));
	//----------------------------------------------------------------------------------------------------------------------
	//Соединение с БД
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres") or die('Could not connect: ' . pg_last_error());
	$groups = pg_query("SELECT * FROM groups") or die('Ошибка запроса: ' . pg_last_error());
	$groups = pg_fetch_all($groups);
	$disc = pg_query("SELECT * FROM disc") or die('Ошибка запроса: ' . pg_last_error());
	$disc = pg_fetch_all($disc);
	//----------------------------------------------------------------------------------------------------------------------
	?>
	<table>
		<tr><td>
			<tr><h2>Добавить отчетность</h2></tr>
		</td></tr>

		<tr><td>
			<form method="post" action="addReport.php">
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
							<select style='width:250px' id="reportGroup" name="reportGroup" onchange="selectDisc(this.value)">
								<option value=''>Выберите группу</option>
<?php
									foreach ($groups as $g) {
										echo "<option value='".$g['id']."'>".$g['name']."</option>";
									}
?>
							</select>
						</td>
					</tr>

					<tr>
						<td>Дисциплина:</td>
						<td>
							<select style='width:250px' id="reportDisc" name="reportDisc">
								<option value=''>Выберите дисциплину</option>
<?php
									foreach ($disc as $d) {
										echo "<option value='".$d['id']."'>".$d['name']."</option>";
									}
?>
							</select>
						</td>
					</tr>

					<tr>
						<td>Отчетность:</td>
						<td>
							<input type="checkbox" value="1" id="exam" name="report[1]">Экзамен</input><br>
							<input type="checkbox" value="2" id="zach" onchange="checked_1(1)" name="report[2]">Зачет</input><br>
							<input type="checkbox" value="3" id="d_zach" onchange="checked_1(2)" name="report[3]">Дифференцируемый зачет</input><br>
							<input type="checkbox" value="4" id="course_work" onchange="checked_2(3)" name="report[4]">Курсовая работа</input><br>
							<input type="checkbox" value="5" id="course_project" onchange="checked_2(4)" name="report[5]">Курсовой проект</input>
						</td>
					</tr>

					<tr>
						<td>
							<button type='submit'>Добавить</button>
						</td>
					</tr>
				</table>
			</form>
		</td></tr>

		<tr><td>
			<form action='reportForm.php'>
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
