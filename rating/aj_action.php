<?php
	include_once ('journal.class.php');
	include_once ('element.class.php');
	include_once ('variables_disc.php');

	switch($_GET['action'])
	{
		case 'groupnum': 
		if (isset($_GET['grnum']) && $_GET['grnum'] != '' && $_GET['grnum'] > 0) {
			$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
			$stud_info = pg_query("SELECT last_name, first_name, group_id, round(AVG(CAST(value AS integer)), 1) d, student_id FROM students INNER JOIN mark ON (students.id = mark.student_id) WHERE group_id=".$_GET['grnum']." GROUP BY last_name, first_name, student_id, group_id ORDER BY d DESC") or die('Ошибка запроса: ' . pg_last_error());
			$stud_info = pg_fetch_all($stud_info);

			echo "<table id='studList' border=1>\n";
			echo "<tr><td>Фамилия</td><td>Имя</td><td>Средний балл</td></tr>";

			if (!$stud_info) {
			print("ПУСТО");
			} else {
				foreach ($stud_info as $col_value)
				{
					echo "\t\t<tr><td>".$col_value['last_name']."</td>\n";
					echo "\t\t<td>".$col_value['first_name']."</td>\n";
					echo "\t\t<td>".$col_value['d']."</td></tr>\n";
				}
			}    
				echo "</table>\n";
		} else {
			print('выберите группу');
			}
		break;

	////////////////////////////////////////////////////////////////////////////////////

		case 'selectStudentForEdit':
		if (isset($_GET['group']) && $_GET['group'] != '' && $_GET['group'] > 0) {
			$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
			$stud_info = pg_query("SELECT id, last_name, first_name, group_id FROM students WHERE group_id=".$_GET['group']."") or die ('Ошибка запроса: ' . pg_last_error());
			$stud_info = pg_fetch_all($stud_info);
			$groups = pg_query("SELECT id, name FROM groups") or die('Ошибка запроса: ' . pg_last_error());
			$groups = pg_fetch_all($groups);

			if(!$stud_info) {
			print("Table is empty");
			} else {
				echo "<table id='tableOfStudents' border=1>";
				echo "<tr><td>Фамилия</td><td>Имя</td><td>Редактировать</td></tr>";
				foreach ($stud_info as $s)
				{
					echo "<tr><td>".$s['last_name']."</td>";
					echo "<td>".$s['first_name']."</td>";
					echo "<td><form action='ghostListStudent.php' method='POST'>";
					echo "<input type='hidden' name='student' value='".$s['id']."'></input>";
					echo "<input type='hidden' name='group' value='".$groups['id']."'></input>";
					echo "<input type='submit' value='Редактировать'></input>";
					echo "</form></td></tr>";
				}
				echo "</table>";    
			}
		}
		break;

	////////////////////////////////////////////////////////////////////////////////////

		case 'disciplina':
		if (isset($_GET['groups']) && $_GET['groups'] != '' && $_GET['groups'] > 0) {
			$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
			$disc = pg_query("SELECT DISTINCT disc.id, disc.name FROM disc JOIN gr_disc_rep ON (disc.id = gr_disc_rep.disc_id) WHERE gr_disc_rep.group_id=".$_GET['groups']) or die ('Ошибка запроса: ' . pg_last_error());
      $disc = pg_fetch_all($disc);

      var_dump($disc);

			echo "<select style='width:250px' id='disc' name='disc'>";
        echo "<option value=''>Выберете дисциплину</option>";
        foreach ($disc as $d){
            echo "<option value=".$d['id'].">".$d['name']."</option>";
            }
        echo "</select>";
		}
		break;

	////////////////////////////////////////////////////////////////////////////////////

	   case 'setJournalMark':
        $j = new journal();
        $j->DBSetMark($_GET['element_id'], $_GET['column_id'], $_GET['student_id'], urldecode($_GET['mark']));
        return;
    break;

    ////////////////////////////////////////////////////////////////////////////////////

		case 'journal':
			if (isset($_GET['disc']) && $_GET['disc'] != '' && $_GET['disc'] > 0) {
				$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
				$element_id = pg_query("SELECT element_id FROM j_groups WHERE group_id=".$_GET['groups']);
				$element_id = pg_fetch_assoc($element_id);

				//var_dump($element_id);
				echo "<table name='journal' id='journal'>";
				echo "<tr><td>";
				$j = new journal();
				$j->printElement($element_id['element_id'], false, $_GET['disc']);
				echo "</td></tr>";
				echo "</table>";
			}
		break;

	////////////////////////////////////////////////////////////////////////////////////

		case 'editJournal':
			if (isset($_GET['disc']) && $_GET['disc'] != '' && $_GET['disc'] > 0) {
				$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
				$element_id = pg_query("SELECT element_id FROM j_groups WHERE group_id=".$_GET['groups']);
				$element_id = pg_fetch_assoc($element_id);

				//var_dump($element_id);
				echo "<table name='journal' id='journal'>";
				echo "<tr><td>";
				$j = new journal();
				$j->printElement($element_id['element_id'], true, $_GET['disc']);
				echo "</td></tr>";
				echo "</table>";
			}
		break;

	////////////////////////////////////////////////////////////////////////////////////

		case 'disc_select':

		if (isset($_GET['discs']) && $_GET['discs'] != '' && $_GET['discs'] > 0) {
			$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
			$proff_info = pg_query("SELECT id, name, teacher_id FROM disc WHERE teacher_id=".$_GET['discs']."") or die ('Ошибка запроса: ' . pg_last_error());
			$proff_info = pg_fetch_all($proff_info);

			echo "<select style='width:200px' id='discList' name='discList'>";
			echo "<option value=''>Выберите дисциплину</option>";
			
			foreach ($proff_info as $s)
			{
				echo "<option value=".$s['id'].">".$s['name']."</option>";
			}
			echo "</select>";
		}        
		break;

	////////////////////////////////////////////////////////////////////////////////////

		case 'tableOfReports':
		
		if(isset($_GET['selectGroup']) && $_GET['selectGroup'] != '' && $_GET['selectGroup'] > 0) {
			$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
			$rep_disc = pg_query("SELECT DISTINCT disc.id id, name FROM disc JOIN gr_disc_rep ON (disc.id = gr_disc_rep.disc_id) JOIN report ON (gr_disc_rep.report_id = report.id) WHERE group_id=".$_GET['selectGroup']);
			$rep_disc = pg_fetch_all($rep_disc);   
			$group_id = $_GET['selectGroup'];

			if(!$rep_disc) {
				print("Table is empty");
			} else {   
				echo "<table id='tableOfReport' border=1>";
				echo "<tr><td>Дисциплина</td><td>Текущая отчетность</td><td>Редактировать</td><td>Удалить</td></tr>";
				
				$n = 0;
				$k = 0;
				
				foreach ($rep_disc as $rd)
				{
					$report_name = pg_query("SELECT name FROM rep_type_name JOIN report ON (rep_type_name.type = report.type) JOIN gr_disc_rep ON (report.id = gr_disc_rep.report_id) WHERE gr_disc_rep.disc_id=".$rd['id']);
					$report_name = pg_fetch_all($report_name);

					foreach ($report_name as $rname) 
					{
						$name_of_report[] = $rname['name'];
					}

					$report_id = pg_query("SELECT report.id id FROM report JOIN gr_disc_rep ON (report.id = gr_disc_rep.report_id) WHERE gr_disc_rep.disc_id=".$rd['id']);
					$report_id = pg_fetch_all($report_id); 

					foreach($report_id as $rid)
					{    
						$index_of_report[] = $rid['id'];
					}

					echo "<tr><td>".$rd['name']."</td>";
					echo "<td>".implode("<br>", $name_of_report)."</td>";
					echo "<td><form action='editReportForm.php' method='POST'>";
					
					foreach ($index_of_report as $idrep) 
					{
						echo "<input type='hidden' name='index_of_report[".$n."]' value='".$idrep."'></input>";
					$n++;
					}
					
					echo "<input type='hidden' name='index_of_group' value='".$group_id."'></input>";
					echo "<input type='hidden' name='index_of_disc' value='".$rd['id']."'></input>";
					echo "<input type='submit' value='Редактировать'></input>";
					echo "</form></td>";
					echo "<td><form action='deleteReport.php' method='POST'>";
					
					foreach ($index_of_report as $idrep) 
					{
						echo "<input type='hidden' name='index_of_report[".$k."]' value='".$idrep."'></input>";
						$k++;
					}
					
					echo "<input type='hidden' name='index_of_group' value='".$group_id."'></input>";
					echo "<input type='hidden' name='index_of_disc' value='".$rd['id']."'></input>";
					echo "<input type='submit' value='Удалить'></input>";
					echo "</form></td></tr>";

					$name_of_report = array();
					$index_of_report = array();
				}
				echo "</table>";    
			}
		}
		break;

	////////////////////////////////////////////////////////////////////////////////////

		case 'selectDisc':
		if (isset($_GET['groups']) && $_GET['groups'] != '' && $_GET['groups'] > 0) {
			$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
			$disc = pg_query("SELECT disc.id id, name FROM disc INNER JOIN gr_disc_tech ON (disc.id = gr_disc_tech.disc_id) WHERE gr_disc_tech.group_id=".$_GET['groups']."") or die ('Ошибка запроса: ' . pg_last_error());
			$disc = pg_fetch_all($disc);

			if(!$disc) {
				print("Table is empty");
			} else {
				echo "<table id='tableOfDisc' border=1>";
				echo "<tr><td>Дисциплина</td><td>Редактировать</td><td>Удалить</td></tr>";
				foreach ($disc as $d)
				{
					echo "<tr><td>".$d['name']."</td>";
					echo "<td><form action='ghostListDisc.php' method='POST'>";
					echo "<input type='hidden' name='disc_id' value='".$d['id']."'></input>";
					echo "<input type='submit' value='Редактировать'></input>";
					echo "</form></td>";
					echo "<td><form action='deleteDisc.php' method='POST'>";
					echo "<input type='hidden' name='disc_id' value='".$d['id']."'></input>";
					echo "<input type='submit' value='Удалить'></input>";
					echo "</form></td></tr>";
				}
				echo "</table>";    
			}
		}

		break;

	////////////////////////////////////////////////////////////////////////////////////
		case 'group_select':

		if (isset($_GET['group']) && $_GET['group'] != '' && $_GET['group'] > 0) {
			$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
			$stud_info = pg_query("SELECT id, last_name, first_name, group_id FROM students WHERE group_id=".$_GET['group']."") or die ('Ошибка запроса: ' . pg_last_error());
			$stud_info = pg_fetch_all($stud_info);

			echo "<select style='width:200px' id='student' name='student'>";
			echo "<option value=''>Выберете студента</option>";
			foreach ($stud_info as $s)
			{
				echo "<option value=".$s['id'].">".$s['last_name']."&nbsp;".$s['first_name']."</option>";
			}
			echo "</select>";
		}        
		break;

	////////////////////////////////////////////////////////////////////////////////////

		case 'makeJournalCSV':
	    $j = new journal();
	    $name = $j->createCSV($_GET['id']);
	    print $name;
	    return;
    break;
	}
?>
