<?php
	include_once'../utils.php';
	printHeader();
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------------------------------    
	$groups = pg_query("SELECT id, name FROM groups") or die('Ошибка запроса: ' . pg_last_error());
	$groups = pg_fetch_all($groups);
//----------------------------------------------------------------------------------------------------------------------
?>
	<html>
		<table>
			<tr><td>
				<form action='addStudent.php' method="post">
					<table>
						<tr>
							<td><h2>Добавление студента</h2></td>
						</tr>

						<tr>
							<td>Фамилия:</td>
							<td>
								<input style='width:200px' type='text' name='studentLastName'></input>
							</td>
						</tr>

						<tr>
							<td>Имя:</td>
							<td>
								<input style='width:200px' type='text' name='studentFirstName'></input>
							</td>
						</tr>

						<tr>
							<td>Отчество:</td>
							<td>
								<input style='width:200px' type='text' name='studentMiddleName'></input>
							</td>
						</tr>

						<tr>
							<td>Группа:</td>
							<td>
								<select style='width:200px' name='studentGroup'>
									<option value=''></option>
<?php 
									foreach ($groups as $g){
									echo "<option value=".$g['id'].">".$g['name']."</option>";
									}
?>
								</select>
							</td>
						</tr>
					</table>
					<button type='submit'>Добавить</button>
				</form>
				<form action='selectGroup.php' method="post">    
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
