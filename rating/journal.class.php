<?php
	require_once('element.class.php');
	include_once('variables_disc.php');

	class journal extends element
	{
		private $dbPath;
		private $notices;
		protected $type = 'journal';

//----------------------------------------------------------------------------------------------

		public function printAddForm($elementId = 0, $pageId = 0, $hasGroups = array()) //Добавление форм начиная с группы
		{
?>
			<link rel="stylesheet" type="text/css" href="colorPicker.v1.2.css" media="screen">
			<div class="no-border">
			<script type="text/javascript">
			function showSum() //отображение яцейки суммы и цвета ячейки
			{
				if(document.getElementById('typeSelect').value == 3)
				{
					document.getElementById('inputSum').style.display = "table-row";
					document.getElementById('color_picker_two').style.display = "table-row";
				} else
					{
						document.getElementById('inputSum').style.display = "none";
						document.getElementById('color_picker_two').style.display = "none";
					}
			}
			</script>
			<script type="text/javascript" src="colorPicker.v1.2.js"></script>    
			<form class="no-border"  style="margin: 0 0 5px 0 !important;" method="post" id="addColumn" action="<?=$this->actionPage?>">
			<table>
			<tr>
			<td style="padding-top: 1em;">Новый столбец</td>
			<td></td>
			</tr>
			<tr>
			<td align="right">Тип</td>
			<td>
			<select name="col_type" id="typeSelect" style="width: 100%;" onchange="showSum()">
			<option value="1">Оценка</option>
			<option value="3">Вычисляемый</option>
			</select>
			</td>
			</tr>
			<tr>
			<td align="right">Название</td>
			<td><input type="text" size="33" name="col_name"></td>
			</tr>
			<tr id="inputSum" style="display: none;">
			<td>Суммируемые столбцы<br>через плюс</td>
			<td valign="top"><input type="text" name="sum" style="width: 100%;" id="formula<?=$elementId?>"></td>
			</tr>
			<tr id="color_picker_two" style="display: none;">
			<td>Цвет колонки</td>
			<td valign="top"><div id="picker-container"><span id="color_picker_two"></span></div></td>
			</tr>              
			</table>
			<input type="hidden" name="page_id" value="<?=$pageId?>">
			<input type="hidden" name="action" value="addElementData">
			<input type="hidden" name="element_id" value="<?=$elementId?>">
			<input type="hidden" name="element_type" value="<?=$this->type?>">
			<input type="submit" class="flat-button" value="Добавить столбец" onclick="setColumnId(<?=$elementId?>)">
			</form>
			<script type="text/javascript">
// Базовые вэб цвета + град. серого:
			ColorPicker.setPallete(["#FFFF3C", "#FE646A", "#64FE6A", "#B1FE64", "#3CFFFF", 
			"#79BCFF", "#FF9E3C", "#FF3CFF", "#DBDBDB", "#FFD23C"]);                                    
// Так как цветов меньше - нужно скорректировать стили:                                    
			ColorPicker.setClasses(
			"col-safe-picker",  /* Имя класса для значка выбора цвета, по-умолчанию: "col-pic-picker" */
			"col-safe-palette", /* Имя класса для появляющейся палитры, по-умолчанию: "col-pic-palette" */
			"col-safe-item",    /* Имя класса элементов - образцов цвета в палитре, для  по-умолчанию: "col-pic-item" */
			"col-safe-closer"   /* Имя класса для значка "закрыть": "col-pic-closer" */
			);            
// Добавляем на страницу:
			ColorPicker.insertInto(document.getElementById("picker-container"), document.getElementById("color_picker_two"), "base_color", "#64FE6A", 5, 5 );            
			</script>
			</div>
<?php
			return true;
		}

//----------------------------------------------------------------------------------------------

		public function printElement($elementId = 0, $edit = false, $pageId = 0) //вывод элементов для студента
		{
			if($edit)
			{
				$this->printEditElement($elementId, $pageId);
				return true;
			}

			if(!$elementId)
			{
				$this->printErrLog('Don\'t have a news block id');
				return false;
			} 
//$this->printElementName($elementId, $edit, $pageId);
			$table = $this->getTable($elementId);//формирование таблицы для вывода
			if(empty($table[0][1]) && empty($table[1][0]))
			{
				print '<br>В данный момент таблица пуста.';
				return true;
			}
?>
			<center>
			<div>
			<table style="border-collapse: collapse; border-width: 0; padding: 0; margin: 0;">
			<tr>
			<td valign="top" align="right" style="padding: 0; margin: 0;">
			<table style="border-collapse: collapse; background-color: #FFE6C6;"> <!--Выводится таблица с ФИО студентов-->
<?php

			for($row_num = 0; $row_num < count($table); ++$row_num)
			{
			
?>
				<tr class="table-row">
				<td style="border-bottom: solid #999999 1px; border-right: solid #999999 1px;">
<?php
				print @$table[$row_num][0][1];
?>
				&nbsp;
				</td>
				</tr>
<?php
			}
?>
			</table>
			</td>
			<td style="padding: 0;" valign="top"> 
			<div class="scrollTable">           
			<table style="border-collapse: collapse; background-color: #FFE6C6;"> <!--Выводится таблица с оценками студентов-->
<?php
			$color = array("#FFFF3C" => "#FFFF73", "#FE646A" => "#FF8B8F", "#64FE6A" => "#92FF95", 
			"#B1FE64" => "#C5FF8A", "#3CFFFF" => "#8AFFFF", "#79BCFF" => "#A2D1FF",
			"#FF9E3C" => "#FFBD7A", "#FF3CFF" => "#FF7FFF", "#DBDBDB" => "#ECECEC",
			"#FFD23C" => "#FFDD6C");

			for($row_num = 0; $row_num < count($table); ++$row_num)
			{
?>      
				<tr class="table-row">
<?php
				for($col_num = 1; $col_num < count($table[0]); ++$col_num)
				{
?>
					<td style="background-color: <?=($row_num != 0 ? ($row_num % 2 ? $color[$table[0][$col_num][3]] : $table[0][$col_num][3]) : $table[0][$col_num][3])?>; border-bottom: solid black 1px; border-right: solid black 1px;">
<?php
					if($row_num == 0 || $col_num == 0)
					{
						print @$table[$row_num][$col_num][1];
						continue;
					}
					print '<center>'.@$table[$row_num][$col_num][0].'</center>';
?>
					</td>
<?php
				}
?>
				</tr>
<?php
			}
?>
			</table>
			</div>
			</td>
			</tr>
			</table>  
			</div>
			</center>
<?php
			return true;
		}

//----------------------------------------------------------------------------------------------

		private function getTable($elementId)//Формирует таблицу с данными
		{
			$table = array();
			$table[0][0] = '';
			$rows = array();
			$rows[0] = '';
			$cols = array();
			$cols[0] = '';
			$this->DBConnect();
//$students = pg_query("SELECT * FROM j_students WHERE element_id=$elementId");
			$students = pg_query("SELECT s.last_name, s.first_name, s.id FROM j_groups AS jg JOIN students AS s ON jg.group_id=s.group_id WHERE element_id=$elementId");
			if($students)
			{
				$students = pg_fetch_all($students);
				foreach($students as $s)
				{
//$data = pg_query("SELECT * FROM students WHERE id=".$s['db_student_id']);
//$data = pg_fetch_assoc($data);
					$rows[] = array($s['id'], trim($s['last_name']).'&nbsp;'.trim($s['first_name']), @$s['dismissed']);
				}
			}

			$columns = pg_query("SELECT * FROM j_columns WHERE element_id=$elementId ORDER BY id");
			if($columns)
			{
				$columns = pg_fetch_all($columns);
				$ordnum = 0;
				if($columns)
				foreach($columns as $c)
				{
					$cols[] = array($c['id'], $c['name'], $c['type'], $c['color']);   
				}
			}
			foreach($rows as $row_num=>$row)//для пересечения строки и столбца берется значение
			{
				foreach($cols as $col_num=>$col)
				{
					if($row_num == 0 && $col_num != 0)
					{
						$table[$row_num][$col_num] = $col;
						continue;
					}
					elseif($col_num == 0 && $row_num != 0)
					{
						$table[$row_num][$col_num] = $row;
						continue;
					}
					elseif($row_num == 0 && $col_num == 0)
					continue;

					$table[$row_num][$col_num] = array();
					$tmp = pg_query("SELECT j.value, jc.type FROM journal AS j JOIN j_columns AS jc ON j.column_id=jc.id WHERE j.element_id=$elementId AND j.student_id=".$row[0]." AND j.column_id=".$col[0]);

					if(!$tmp)
					continue;
					$tmp = pg_fetch_assoc($tmp);

					if($tmp['type'] == 1)
					{
						$table[$row_num][$col_num] = array($tmp['value'], $tmp['type']);
					}
					if($col[2] == 3)
					{
						$this->DBDisconnect();
						$sum = $this->calcSum($elementId, $col[0], $row[0]);
						$this->DBConnect();
						$table[$row_num][$col_num] = array($sum, 3);
					}
				}
			}
			$this->DBDisconnect();
			return $table;
		}
	//----------------------------------------------------------------------------------------------    
		private function calcSum($elementId, $col_ID, $studentId)
		{
			$this->DBConnect();                                                                   
			$content = pg_query("SELECT * FROM j_calc_cols WHERE element_id=$elementId AND base_col_id=".$col_ID);
			$content = pg_fetch_all($content);

			$sum = 0;
			foreach ($content as $c)
			{
				$colType = pg_query("SELECT * FROM j_columns WHERE id=".$c['col_id']);
				$colType = pg_fetch_assoc($colType);
				$colType = $colType['type'];            
				if($colType == 3)
				{
					$sum += $this->calcSum($elementId, $c['col_id'], $studentId);
					continue; 
				}
				$sum_cell_value = pg_query("SELECT * FROM journal WHERE element_id=$elementId AND student_id=".$studentId." AND column_id=".$c['col_id']);//получаем значение для конкретной ячейки входящей в суммируемый столбец

				if($sum_cell_value)
				{
					$sum_cell_value = pg_fetch_assoc($sum_cell_value);
				} 
				$sum += $sum_cell_value['value'];
			}
			$this->DBDisconnect();
			return $sum;           
		}    
	//----------------------------------------------------------------------------------------------

		private function printEditElement($elementId = 0, $pageId = 0)//выводит элементы редактирования для преподавателя
		{      
			if(!$elementId)
			{
				$this->printErrLog('Don\'t have a news block id');
				return false;
			} 

//$this->printElementName($elementId, true, $pageId);
			$hasGroups = array();
			$this->DBConnect();
//krumo("SELECT g.id, g.name FROM j_students AS js JOIN students AS s ON js.db_student_id=s.id JOIN groups AS g ON s.s_group=g.id WHERE js.element_id=$elementId GROUP BY g.id");
//$tmp = pg_query("SELECT g.id, g.name FROM j_students AS js JOIN students AS s ON js.db_student_id=s.id JOIN groups AS g ON s.s_group=g.id WHERE js.element_id=$elementId GROUP BY g.id");
			$tmp = pg_query("SELECT g.id, g.name FROM j_groups AS jg JOIN groups AS g ON jg.group_id=g.id WHERE jg.element_id=$elementId");
//krumo(pg_last_error());
			if($tmp)
			{
				$tmp = pg_fetch_all($tmp);
				foreach($tmp as $t)
				{
					$hasGroups[$t['id']] = $t['name'];
				}
			}
			$this->DBDisconnect();
			$table = $this->getTable($elementId);
			if(empty($table[0][1]) && empty($table[1][0]))
			{
				print '<br>В данный момент таблица пуста. Добавьте строки или столбцы';
				$this->printAddForm($elementId, $pageId, $hasGroups);
				$this->DBDisconnect();
				return true;
			}        
?>
			<div class="edit-element-body"> 
			<script type="text/javascript">
			function createJournalCSV(id)
			{
				var tmp = 'id=' + id + '&action=makeJournalCSV';
				$.ajax({
					url:'aj_action.php',
					data:tmp,
					success: function(data)
					{
						window.location = data;
					}    
				});
			}
			</script>
			<input type="button" class="flat-button" value="Экспорт в CSV" onclick="createJournalCSV(<?=$elementId?>)">
			<script type="text/javascript">
			function setJournalMark(elementId, student, column)
			{
				var mark = document.getElementById('input' + elementId + student + column).value.replace(",",".");
				document.getElementById('input' + elementId + student + column).value = mark;                                                                                               
				var tmp = 'action=setJournalMark&element_id=' + elementId  + '&student_id=' + student + '&column_id=' + column + '&mark=' + encodeURIComponent(mark);
				//alert(tmp);
				$.ajax({
					url:'aj_action.php',
					data:tmp
				});  
			}
			function setColumnId(elId)
			{
				var formula = document.getElementById('formula'+elId).value;
//alert(formula);
				var arr = formula.split('+');
				for(i=0; i < arr.length; i++)
				{
					arr[i] = document.getElementById('J'+arr[i]+elId).value;        
				}
				formula = arr.join('+');
				document.getElementById('formula'+elId).value = formula;
//alert(formula);
			}
			</script>
			<table style="border-collapse: collapse; border-width: 0; padding: 0; margin: 0;">
			<tr style="height: 100%;">
			<td valign="top" align="right" style="padding: 0; height: 100%;">
			<table style="border-collapse: collapse; background-color: #FFE6C6;"> <!--Выводится таблица с ФИО студентов-->
<?php
			for($row_num = 0; $row_num < count($table); ++$row_num)
			{
?>
				<tr class="table-row">
				<td height="25px" style="border-bottom: solid #999999 1px; border-right: solid #999999 1px; padding: 2px;">
<?php
				print @$table[$row_num][0][1];
?>
				&nbsp;
				</td>
				</tr>
<?php
			}
?>
			<tr style="border-bottom: solid #999999 1px; border-right: solid #999999 1px;"><td style="font-size: 8pt">&nbsp;</td></tr>   
			<tr style="border-right: solid #999999 1px; padding: 2px;"><td>&nbsp;</td></tr>
			</table>
			</td>
			<td style="padding: 0;"> 
			<div class="scrollTable">           
			<table style="border-collapse: collapse; background-color: #FFE6C6;"> <!--Выводится таблица с оценками студентов-->
<?php            
			$color = array("#FFFF3C" => "#FFFF73", "#FE646A" => "#FF8B8F", "#64FE6A" => "#92FF95", 
			"#B1FE64" => "#C5FF8A", "#3CFFFF" => "#8AFFFF", "#79BCFF" => "#A2D1FF",
			"#FF9E3C" => "#FFBD7A", "#FF3CFF" => "#FF7FFF", "#DBDBDB" => "#ECECEC",
			"#FFD23C" => "#FFDD6C");
			for($row_num = 0; $row_num < count($table); ++$row_num)
			{
?>
				<tr class="table-row">
<?php       
				for($col_num = 1; $col_num < count($table[0]); ++$col_num)
				{
?>
					<td height="25px" style="background-color: <?=($row_num != 0 ? ($row_num % 2 ? $color[$table[0][$col_num][3]] : $table[0][$col_num][3]) : $table[0][$col_num][3])?>; border-bottom: solid #999999 1px; border-right: solid #999999 1px; padding: 2px;" <?=($col_num > 0 ? 'align="center"' : '')?>>
<?php
					if($row_num == 0)
					{
						print str_ireplace(' ', '<br>', @$table[$row_num][$col_num][1]);                   
						continue;
					}
					if($col_num == 0)
					{
						print @$table[$row_num][$col_num][1];
						continue;
					}

					if(@$table[0][$col_num][2] == 1)
					{
?>
						<input type="text" style="border: none;  margin: 0; padding: 0; height: 20px;" value="<?=@$table[$row_num][$col_num][0]?>" id="input<?=($elementId.$table[$row_num][0][0].$table[0][$col_num][0])?>" size="2" onchange="setJournalMark(<?=$elementId?>, <?=$table[$row_num][0][0]?>, <?=$table[0][$col_num][0]?>)">
<?php
					}
					elseif(@$table[0][$col_num][2] == 3)
					{
						print '<center>'.@$table[$row_num][$col_num][0].'</center>';
					}
?>
					</td>
<?php
				}
?>
				</tr>
<?php
			}
?>      
				<tr>
<?php
				for($col_num = 1; $col_num < count($table[0]); ++$col_num)
				{
?>
					<td align="center" style="background-color: <?=$table[0][$col_num][3]?>; border-bottom: solid #999999 1px; border-right: solid #999999 1px; font-size: 8pt;">
<?=$col_num?>
					<input type="hidden" value="<?=$table[0][$col_num][0]?>" id="J<?=$col_num.$elementId?>">
					</td>
<?php
				}
?>
				</tr>
				<tr>
<?php 
				for ($col_num = 1; $col_num < count($table[0]); ++$col_num)
				{
?>
					<td style="background-color: <?=$table[0][$col_num][3]?>; border-right: solid #999999 1px; padding: 2px;" align="center">
					<form method="post" action="<?=$this->actionPage?>" class="simple-button">
					<input type="submit" class="delete-button" value="">
					<input type="hidden" name="action" value="deleteElementData">
					<input type="hidden" name="element_id" value="<?=$elementId?>"> 
					<input type="hidden" name="page_id" value="<?=$pageId?>">
					<input type="hidden" name="element_type" value="<?=$this->type?>">
					<input type="hidden" name="column_id" value="<?=$table[0][$col_num][0]?>">
					</form>
					</td>
<?php   
				}
?>
				</tr>

			?>
				</table>
				</div>
				</td>
				</tr>
				</table>  
				</div>
<?php
				$this->printAddForm($elementId, $pageId, $hasGroups);
				return true;
		}

//----------------------------------------------------------------------------------------------

		public function DBAddElementData($post = '', $edit = false)//дбавление в БД данных элемента
		{
//krumo($post);
			$elementId = @$post['element_id'];
			$pageId = @$post['page_id'];
			$group = @$post['group'];
			$col_type = @$post['col_type'];
			$col_name = @$post['col_name'];
			$sum = @$post['sum'];
			$student = @$post['student'];
			$color = @$post['base_color'];
			$this->DBConnect();
			if($group)
			{
				$this->DBAddGroup($group, $elementId);
				return true;
			}
			if($student)
			{
				$this->DBAddStudent($elementId, $student);
				return true;
			}
			if($col_type)
			{
				$this->DBAddColumn($elementId, $col_type, $col_name, $sum, $color);
			}
			return true;
		}

//----------------------------------------------------------------------------------------------

		private function DBAddColumn($elementId, $col_type, $col_name, $sumElements, $color)
		{
			$this->DBConnect();
			if($col_type == 1)
			{
				pg_query("INSERT INTO j_columns (element_id, name, type) VALUES ($elementId, '$col_name', $col_type)");
				$this->DBDisconnect();
				return true;
			}
			if($col_type == 3)
			{
				$sumElements = explode('+', $sumElements);
				$id = pg_query("INSERT INTO j_columns (element_id, name, type, color) VALUES ($elementId, '$col_name', $col_type, '$color') RETURNING id");
//$id = pg_query("SELECT MAX(id) FROM j_columns WHERE type=3");
				$id = pg_fetch_assoc($id);
				$id = $id['id'];
				foreach($sumElements as $s)
				pg_query("INSERT INTO j_calc_cols (element_id, base_col_id, col_id, operation) VALUES ($elementId, $id, ".trim($s).", 1)");
				$this->DBDisconnect();
			}
		}

//----------------------------------------------------------------------------------------------

		private function DBAddGroup($group, $elementId)
		{
			if(!$group)
			{
				$this->printErrLog('No group id');
				return false;
			}
			require_once('../students/students.class.php');
			$st = new students();
			$students = $st->dbGetStudents($group);
			foreach($students as $s)
			{
				$this->DBAddStudent($elementId, $s['id']);
			}
			return true;
		}

//----------------------------------------------------------------------------------------------

		private function DBAddStudent($elementId, $studentId)
		{
			$this->DBConnect();
			pg_query("INSERT INTO j_students (element_id, db_student_id) VALUES ($elementId, $studentId)");
			$this->DBDisconnect();
			return true;
		}
	
//----------------------------------------------------------------------------------------------

		public function DBSetMark($elementId = 0, $columnId = 0, $studentId = 0, $status = '')
		{
			if(!$elementId || !$columnId || !$studentId)
			return false;
			$elementId = pg_escape_string($elementId); 
			$columnId = pg_escape_string($columnId);
			$studentId = pg_escape_string($studentId);
			$status = pg_escape_string(trim($status));
			$this->DBConnect();                   
			try
			{
				$exist = pg_query("SELECT * FROM journal WHERE column_id=$columnId AND student_id=$studentId");
				$exist = pg_fetch_assoc($exist);
			}
			catch (myException $ex)
			{
				$this->printErrLog('Failed in query '."SELECT * FROM journal WHERE column_id=$columnId AND student_id=$studentId");
				$this->DBDisconnect();
				return false;
			}
			if($exist)
			{
				try
				{
					pg_query("UPDATE journal SET value='$status' WHERE column_id=$columnId AND student_id=$studentId");
				}
				catch (myException $ex)
				{
					$this->printErrLog('Failed in query '."UPDATE journal SET value='$status' WHERE column_id=$columnId AND student_id=$studentId");
					$this->DBDisconnect();
					return false;
				}
				$this->DBDisconnect();
				return true;
			}
			try
			{
				pg_query("INSERT INTO journal (element_id, column_id, student_id, value) VALUES ($elementId, $columnId, $studentId, '$status')");
			}
			catch (myException $ex)
			{
				$this->printErrLog('Failed in query '."INSERT INTO journal (element_id, column_id, student_id, value) VALUES ($elementId, $columnId, $studentId, '$status')");
				$this->DBDisconnect();
				return false;
			}
			$this->DBDisconnect();
			return true;
		}

//----------------------------------------------------------------------------------------------

		public function createCSV($elementId)
		{
			$table = $this->getTable($elementId);
			$name = date('d.m.Y-H.i.s', time());
			$f = fopen('csv/'.$name.'.csv', 'w');

			for($row_num = 0; $row_num < count($table); ++$row_num)
			{
				for($col_num = 0; $col_num < count($table[0]); ++$col_num)
				{
					if($row_num == 0 || $col_num == 0)
					{
						fwrite($f, ($col_num > 0 ? ';' : '').'"'.str_ireplace('&nbsp;', ' ', iconv('utf-8', 'windows-1251', @$table[$row_num][$col_num][1])).'"');
						continue;
					}
					fwrite($f, ($col_num > 0 ? ';' : '').'"'.str_ireplace('.', ',', iconv('utf-8', 'windows-1251', @$table[$row_num][$col_num][0]).'"'));    
				}
				fwrite($f, "\x0D\x0A");
			}
			$colors = array('#FFFF3C' => 'Лимонно желтый', '#FE646A' => 'Красный', '#64FE6A' => 'Зеленый', '#B1FE64' => 'Фисташковый', '#3CFFFF' => 'Циан', '#79BCFF' => 'Синий', '#FF9E3C' => 'Коричневый', '#FF3CFF' => 'Фиолетовый', '#DBDBDB' => 'Серый', '#FFD23C' => 'Желтый');
			for($col_num = 0; $col_num < count($table[0]); ++$col_num)
			{
				if($col_num == 0)
				{
					fwrite($f, ($col_num > 0 ? ';' : '').'"'.iconv('utf-8', 'windows-1251', "").'"');
					continue;
				}
				fwrite($f, ($col_num > 0 ? ';' : '').'"'.iconv('utf-8', 'windows-1251', @$colors[@$table[0][$col_num][3]]).'"');    
			}
			fwrite($f, "\x0D\x0A");
			fclose($f);
			return 'csv/'.$name.'.csv';
		}

//----------------------------------------------------------------------------------------------

		public function getElementData($elementId = 0)
		{
		}

//----------------------------------------------------------------------------------------------

		public function DBDeleteElementData($unused = '', $post = '')
		{
			$elementId = $post['element_id'];
			$pageId = $post['page_id'];

			$groupId = @$post['group_id'];
			$columnId = @$post['column_id'];
			if($groupId)
			{
				$this->DBDeleteGroup($elementId, $groupId);
			}
			if($columnId)
			{
				$this->dbDeleteColumn($elementId, $columnId);
			}
			return true;
		}

//----------------------------------------------------------------------------------------------

		private function DBDeleteGroup($elementId, $groupId)
		{
			$this->DBConnect();
			$students = pg_query("SELECT js.id FROM j_students AS js JOIN students AS s ON js.db_student_id=s.id JOIN groups AS g ON s.s_group=g.id WHERE g.id=$groupId");
			if($students)
			{
				$students = pg_fetch_all($students);
				foreach($students as $s)
				{
					pg_query("DELETE FROM journal WHERE element_id=$elementId AND student_id=".$s['id']);
					pg_query("DELETE FROM j_students WHERE element_id=$elementId AND id=".$s['id']);
				}
			}
			$this->DBDisconnect();
			return true;
		}

//----------------------------------------------------------------------------------------------

		private function DBDeleteStudent($elementId, $studentId)
		{
			$this->DBConnect();
			pg_query("DELETE FROM journal WHERE element_id=$elementId AND student_id=".$s['id']);
			$this->DBDisconnect();
		}

//----------------------------------------------------------------------------------------------

		private function DBDeleteColumn($elementId, $columnId)
		{
			$this->DBConnect();
			$column = pg_query("SELECT * FROM j_columns WHERE element_id=$elementId AND id=$columnId");
			$column = pg_fetch_assoc($column);
			if($column['type'] == 1)
			{
				pg_query("DELETE FROM j_calc_cols WHERE col_id=$columnId AND element_id=$elementId");
				pg_query("DELETE FROM journal WHERE column_id=$columnId AND element_id=$elementId");
				pg_query("DELETE FROM j_columns WHERE id=$columnId AND element_id=$elementId");
				$this->DBDisconnect();
				return true;
			}
			if($column['type'] == 3)
			{
				pg_query("DELETE FROM j_calc_cols WHERE base_col_id=$columnId AND element_id=$elementId");
				pg_query("DELETE FROM journal WHERE column_id=$columnId AND element_id=$elementId");
				pg_query("DELETE FROM j_columns WHERE id=$columnId AND element_id=$elementId");
				$this->DBDisconnect();
				return true;
			}
			$this->DBDisconnect();
			return true;
		}

//----------------------------------------------------------------------------------------------

	}
?>
