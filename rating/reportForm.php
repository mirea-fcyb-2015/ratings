<?php
	include_once '../utils.php';
	printHeader('', '', '', array('jquery.js'));
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------------------------------
?>
	<table>
		<tr><td>
			<tr><h2>Отчетность</h2></tr>
		</td></tr>

		<tr><td>
			<form action='addReportForm.php'>
				<button type='submit'>Добавить отчетность</button>
			</form>
		</td></tr>

		<tr><td>
			<form action='selectReportForm.php'>
				<button type='submit'>Редактировать отчетность</button>
			</form>
		</td></tr>

		<tr><td>
			<form action='index.php'>
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
