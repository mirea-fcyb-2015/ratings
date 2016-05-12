<?php
	include_once '../utils.php';
	printHeader('', '', '', array('jquery.js'));
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres") or die('Could not connect: ' . pg_last_error());
	$groups = pg_query("SELECT * FROM groups");
	$groups = pg_fetch_all($groups);
//----------------------------------------------------------------------------------------------------------------------
?>
	<table>
		<tr><td>
			<tr><h2>Редактировать отчетность</h2></tr>
		</td></tr>

		<tr><td>          
			<script type="text/javascript">
				function tableOfReports(st_index)
				{
					var reports = 'selectGroup=' + document.getElementById('selectGroup').value + '&action=tableOfReports';
					document.getElementById('tableOfReport').innerHTML = "";
					$.ajax({
						url:'aj_action.php',
						data:reports,
						success: function(d)
						{
							document.getElementById('tableOfReport').innerHTML = d;
						}
					});    
				}
			</script>

			<table>
				<tr>
					<td>Группа:</td>
					<td>
						<select style='width:250px' id="selectGroup" name="selectGroup" onchange="tableOfReports(this.value)">
							<option value=''>Выберите группу</option>
<?php
							foreach ($groups as $g) 
							{
								echo "<option value='".$g['id']."'>".$g['name']."</option>";
							}
?>
						</select>
					</td>
				</tr>

				<tr>
					<td>
						<table id="tableOfReport" border="1"></table>
					</td>
				</tr>
			</table>
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
