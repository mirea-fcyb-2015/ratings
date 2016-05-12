<?php
	include_once '../utils.php';
	include_once 'journal.class.php';
	include_once 'element.class.php';
	include_once 'variables_disc.php';
	printHeader('', '', '', array('jquery.js'));

	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	//----------------------------------------------------------------------------------------------------------------------
	$groups = pg_query("SELECT id, name FROM groups") or die('Ошибка запроса: ' . pg_last_error());
	$groups = pg_fetch_all($groups);
	$loggedUserId = 6;
	$users_stud = pg_query("SELECT user_type FROM users WHERE id=".$loggedUserId);
	$users_stud = pg_fetch_assoc($users_stud);

	?>

	<html>
		<table>
			<tr><td>
				<table>
					<tr>
					<td><h2>Работа с электронным журналом</h2></td>
					</tr>

					<script type="text/javascript">
						function disciplina(st_index)
						{
						var disciplina = 'groups=' + document.getElementById('groups').value + '&action=disciplina';
						document.getElementById('disc').innerHTML = "";
<?php
						if($users_stud['user_type'] == 1) {
?>
						document.getElementById('group_id').value = document.getElementById('groups').value;
<?php 
						}
?>
							$.ajax({
								url:'aj_action.php',
								data:disciplina,
								success: function(t)
								{
									document.getElementById('disc').innerHTML = t;
								}
							});    
						}
					</script>

					<script type="text/javascript">
						function journal(st_index)
						{
						var journal = 'groups=' + document.getElementById('groups').value + '&disc=' + document.getElementById('disc').value + '&action=journal';
						document.getElementById('journal').innerHTML = "";
<?php
						if($users_stud['user_type'] == 1) {
?>
						document.getElementById('disc_id').value = document.getElementById('disc').value;
<?php 
						}
?>
							$.ajax({
								url:'aj_action.php',
								data:journal,
								success: function(d)
								{
									document.getElementById('journal').innerHTML = d;
								}
							});    
						}
					</script>

					<tr>
						<td>Группа:</td>
						<td>
							<select style='width:250px' name='groups' id='groups' onchange='disciplina(this.value)'>
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
						<td>Дисциплина:</td>
						<td>
							<select style='width:250px' name='disc' id='disc' onchange='journal(this.value)'>
								<option value=''>Выберете группу</option>
							</select>
						</td>
					</tr>

					<tr>
						<td>
							<table name="journal" id="journal">
							</table>
						</td>
					</tr>
				</table>
<?php
			if($users_stud['user_type'] == 1) {
?>
				<table>
					<tr><td>
						<form action='editMarkForm.php'>
							<input type="hidden" name="group_id" id="group_id" value=""></input>
							<input type="hidden" name="disc_id" id="disc_id" value=""></input>
							<button type='submit'>Работа с оценками</button>
						</form>
					</td></tr>
				</table>
<?php
				}
?>
		<table>
			<form action='index.php' method="post">    
			<button>Назад</button>
			</form>
			</table>
		</table>
	</html>

<?php
	printFooter();
?>
