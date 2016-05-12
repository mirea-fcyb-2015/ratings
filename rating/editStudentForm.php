<?php
	include_once'../utils.php';
	printHeader('', '', '', array('jquery.js'));
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
				<table>
					<tr>
						<td><h2>Редактирование данных о студенте</h2></td>
					</tr>

					<script type="text/javascript">
						function selectGroup_1(st_index)
						{
							var selectStudentForEdit = 'group=' + document.getElementById('group').value + '&action=selectStudentForEdit';
							document.getElementById('tableOfStudents').innerHTML = "selectStudentForEdit";
							$.ajax({
								url:'aj_action.php',
								data:selectStudentForEdit,
								success: function(t)
								{
									document.getElementById('tableOfStudents').innerHTML = t;
								}
							});    
						}
					</script>

					<tr>
						<td>Группа:</td>
						<td>
							<select style='width:200px' id='group' onchange='selectGroup_1(this.value)'>
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
						<td>
							<table id="tableOfStudents" border="1">
							</table>
						</td>
					</tr>
				</table>
				<form action='selectGroup.php' method="post">    
					<button>Назад</button>
				</form>
			</td></tr>
		</table>
	</html>
<?php
	printFooter();
?>
