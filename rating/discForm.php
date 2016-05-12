<?php
	include_once'../utils.php';
	printHeader();
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------------------------------    
	$teachers = pg_query("SELECT last_name, first_name, middle_name, id FROM teachers") or die('Ошибка запроса: ' . pg_last_error());
	$teachers = pg_fetch_all($teachers);
	$groups = pg_query("SELECT id, name FROM groups");
	$groups = pg_fetch_all($groups);
//----------------------------------------------------------------------------------------------------------------------
?>
	<html>
		<table>
			<tr><td>
				<form action='addDisc.php' method="post">
					<table>
						<tr>
						<td><h2>Добавить данные о дисциплине</h2></td>
						</tr>

						<tr>
							<td>Название:</td>
							<td>
								<input style='width:250px' type='text' name='discName'></input>
							</td>
						</tr>

						<tr>
							<td>Преподаватель:</td>
							<td>
								<select id="teacher" style='width:250px' name="discTeacher">
									<option value=''></option>
<?php
									foreach ($teachers as $t)
									{
										echo "<option value=".$t['id'].">".$t['last_name']."&nbsp;".$t['first_name']."&nbsp;".$t['middle_name']."</option>";
									}
?>
								</select>
							</td>
						</tr>

						<tr>
						<td>Группы:</td>
						<td>
							<table>
<?php
								foreach ($groups as $n=>$g)
								{
									echo "<tr><td><input type='checkbox' name='discGroup[$n]' value=".$g['id'].">".$g['name']."</td></tr>"; 
									if (isset($_POST['discGroup'])) echo $_POST['discGroup'];   
								}
?>
							</table>
						</td>
						</tr>

					</table>
					<button type='submit'>Добавить</button>
				</form>
			</td></tr>
			<tr><td>
				<form action='editDiscForm.php' method="post">    
					<button type='submit'>Назад</button>
				</form>
			</td></tr>
		</table>
	</html>

<?php
//----------------------------------------------------------------------------------------------------------------------    
	pg_close($dbconnect);
//----------------------------------------------------------------------------------------------------------------------
	printFooter();
?>
