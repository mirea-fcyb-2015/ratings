<?php
	include_once'../utils.php';
	printHeader('', '', '', array('jquery.js'));
	//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres") or die('Could not connect: ' . pg_last_error());    
	$groups = pg_query("SELECT id, name FROM groups") or die('Ошибка запроса: ' . pg_last_error());
	$groups = pg_fetch_all($groups);
	//----------------------------------------------------------------------------------------------------------------------
?>
	<html>
		<table>
			<tr><td>
				<form action='deleteStudent.php' method="get">
					<table>
						<tr>
							<td><h2>Удаление данных о студенте</h2></td>
						</tr>

						<script type="text/javascript">
							function selectGroup(st_index)
							{
								var group_select = 'group=' + document.getElementById('group').value + '&action=group_select';
								document.getElementById('student').innerHTML = group_select;
								$.ajax({
									url:'aj_action.php',
									data:group_select,
									success: function(t)
									{
										document.getElementById('student').innerHTML = t;
									}
								});    
							}
						</script>
					
						<tr>
							<td>Группа:</td>
								<td>
									<select style='width:200px' id='group' onchange='selectGroup(this.value)'>
										<option value=''>Выберете группу</option>
<?php 
										foreach ($groups as $g)
										{
											echo "<option value=".$g['id'].">".$g['name']."</option>";
										}
?>
									</select>
								</td>
						</tr>

						<tr>
							<td>Студент:</td>
								<td>
									<select style='width:200px' id='student' name='student'>
										<option value=''>Выберете студента</option>

									</select>
								</td>
						</tr>
					</table>
					<button type='submit'>Удалить</button>
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
