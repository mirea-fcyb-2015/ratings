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
					<td><h2>Редактирование данных о дисциплине</h2></td>
					</tr>

					<script type="text/javascript">
						function selectDisc(st_index)
						{
						var selectDisc = 'groups=' + document.getElementById('groups').value + '&action=selectDisc';
						document.getElementById('tableOfDisc').innerHTML = "";
							$.ajax({
								url:'aj_action.php',
								data:selectDisc,
								success: function(t)
								{
									document.getElementById('tableOfDisc').innerHTML = t;
								}
							});    
						}
					</script>

					<tr>
						<td>Группа:</td>
						<td>
							<select style='width:250px' name='groups' id='groups' onchange='selectDisc(this.value)'>
								<option value=''>Выберете группу</option>
<?php 
								foreach ($groups as $t)
								{
									echo "<option value=".$t['id'].">".$t['name']."</option>";
								}
?>
							</select>
						</td>
					</tr>

					<tr>
						<td>
							<table id="tableOfDisc" border="1">
							</table>
						</td>
					</tr>
				</table>
				
			<form action='discForm.php' method="post">    
			<button>Добавить</button>
			</form>
			
			<form action='index.php' method="post">    
			<button>Назад</button>
			</form>
		</table>
	</html>

<?php
	printFooter();
?>
