<?php
	include_once '../utils.php';
	printHeader('', '', '', array('jquery.js'));
//----------------------------------------------------------------------------------------------------------------------
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres") or die('Could not connect: ' . pg_last_error());
//----------------------------------------------------------------------------------------------------------------------
?>
	<table>
		<tr><td>
			<tr><h3>Работа с данными о студентах</h3></tr>
		</td></tr>
		<tr><td>
			<form action='addStudentForm.php'>
				<button type='submit'>Добавить данные о суденте</button>
			</form>
		</td></tr>
		<tr><td>
			<form action='deleteStudentForm.php'>
				<button type='submit'>Удалить данные о суденте</button>
			</form>
		</td></tr>
		<tr><td>
			<form action='editStudentForm.php'>
				<button type='submit'>Редактировать данные о суденте</button>
			</form>
		</td></tr>
		<tr><td>
			<form action='index.php' method="post">    
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
