<?php
	include_once '../utils.php';
	include_once 'journal.class.php';
	include_once 'element.class.php';
	include_once 'variables_disc.php';
	printHeader('', '', '', array('jquery.js'));

	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	//----------------------------------------------------------------------------------------------------------------------
	$element_id = pg_query("SELECT element_id FROM j_groups WHERE group_id=".$_GET['group_id']);
	$element_id = pg_fetch_assoc($element_id);

	?>

	<html>
		<table>
			<tr><td>
				<table>
					<tr>
					<td><h2>Редактирование данных об оценке</h2></td>
					</tr>

					<tr>
						<td>
<?php
				$j = new journal();
				$j->printElement($element_id['element_id'], true, $_GET['disc_id']);
?>
						</td>
					</tr>
				</table>
			
			<form action='markForm.php' method="post">    
			<button>Назад</button>
			</form>
		</table>
	</html>

<?php
	printFooter();
?>
