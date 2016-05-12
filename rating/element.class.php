<?php
	abstract class element
	{
		protected $host;
		protected $port;
		protected $dbname;
		protected $user;
		protected $password;
		protected $dbConn;
		private $notices;  //Сообщения
		protected $type;  //Тип элемента
		protected $pageId;
		protected $actionPage;  //Адрес страницы, обрабатывающей действия
		protected $editPage;  //Адрес страницы редактирования
		protected $discPage;  //Адрес страницы по дисциплине

//----------------------------------------------------------------------------------------------
		public function __construct($pageId = '')
		{
			require('variables_disc.php');

			$this->host = $host;
			$this->port = $port;
			$this->dbname = $dbname;
			$this->user = $user;
			$this->password = $password;
			$this->actionPage = $actionPage;
			$this->editPage = $editPage;
			$this->discPage = $discPage;
			$this->pageId = $pageId;
		}

//----------------------------------------------------------------------------------------------
//Вывод формы добавления данных в элемент
//elementId - id элемента, whereReturn - TODO: выпилить, ибо всёравно игнорируем
		abstract public function printAddForm($elementId = 0, $whereReturn = 'index.php');

//----------------------------------------------------------------------------------------------

//Вывод элемента на страницу
//elementId - id элемента, edit - выводить ли средства редактирования элемента, pageId - id страницы, к которой привязан элемент
		abstract public function printElement($elementId = 0, $edit = false, $pageId = 0);

//----------------------------------------------------------------------------------------------

//Подключение к базе данных
		protected function DBConnect()
		{
			$this->dbConn = pg_pconnect("host='".$this->host."' port = ".$this->port." dbname ='".$this->dbname."' user = '".$this->user."' password='".$this->password."'");

			if(!$this->dbConn)
			{
				$this->printErrLog("Ошибка при подключении к базе данных pg: ".pg_last_error());
				return false;
			}
		return true;
		}

		//---------------------------------------------------------------------------------------------- 

		protected function DBDisconnect()
		{
			if($this->dbConn)
			pg_close($this->dbConn);
		}

//----------------------------------------------------------------------------------------------
//Добавление данных в элемент
		abstract public function DBAddElementData();

//----------------------------------------------------------------------------------------------
//Редактирование данных в элементе
//abstract public function DBEditElementData();

//----------------------------------------------------------------------------------------------
//Добавление данных в элемент
		abstract public function DBDeleteElementData();

//----------------------------------------------------------------------------------------------
//Добавление элемента на страницу
		public function DBAddElement($pageId = 0)
		{
			if(!$pageId)
			{
				$this->notices[] = 'Have no page id';
				return false;
			}

			if(!$this->DBConnect())
			return false;

//Новый элемент добавляется в самый низ страницы
			$position = pg_query($this->dbConn, "SELECT max(position) FROM page_content WHERE page_id=$pageId");
			$position = pg_fetch_assoc($position);
			$position = $position['max'] + 1;
			$content_type_id = pg_query($this->dbConn, 'SELECT * FROM content_type WHERE table_name=\''.$this->type.'\'');
			$content_type_id = pg_fetch_assoc($content_type_id);
			$content_type_id = $content_type_id['id'];
			$id = pg_query($this->dbConn, "INSERT INTO page_content (content_type_id, page_id, visible, position) VALUES ($content_type_id, $pageId, 'TRUE', $position) RETURNING id");
			$id = pg_fetch_assoc($id);
			$id = $id['id'];

			return $id;
		}

//----------------------------------------------------------------------------------------------
//Получение данных элемента
		abstract public function getElementData($elementId = 0);

//----------------------------------------------------------------------------------------------
//Вывод сообщений
		public function showNotices()
		{
			if(!$this->notices)
			return;
			foreach($this->notices as $n)
			{
				if(strpos($n, 'FAILED') === FALSE)
				print $n.'<br>';
				else
				print '<b>'.$n.'</b><br>';
			}
			$this->notices = '';
		}

//----------------------------------------------------------------------------------------------
//Удаление элемента
		public function DBDeleteElement($id)
		{
			if(!$id)
			{
				return false;
			}

			$this->DBDeleteExtraData($id);

			if(!$this->DBConnect())
			return false;

			$tmp = pg_query($this->dbConn, "SELECT * FROM page_content WHERE id=$id");
			$tmp = pg_fetch_assoc($tmp);
			$pos = $tmp['position'];
			$pageId = $tmp['page_id'];
//Получаем список элементов ниже удаляемого, их надо будет подвинуть
			$toMove = pg_query($this->dbConn, "SELECT * FROM page_content WHERE page_id=$pageId AND position>$pos");
			$toMove = pg_fetch_all($toMove);

			pg_query($this->dbConn, "DELETE FROM page_content WHERE id=$id");
			pg_query($this->dbConn, 'DELETE FROM '.$this->type." WHERE id=$id");

//Двигаем на один вверх все элементы после удалённого
			if($toMove)
			foreach($toMove as $t)
			{
				pg_query($this->dbConn, 'UPDATE page_content SET position='.($t['position'] - 1).' WHERE id='.$t['id']);
			}
		}

//----------------------------------------------------------------------------------------------

		protected function DBDeleteExtraData($elementId)
		{}

//----------------------------------------------------------------------------------------------
//Перемещение элемента по странице
//id элемента, how - направление перемещения
		public function DBChangePosition($id, $how)
		{
			if(!$id || !$how)
			return false;

			if(!$this->DBConnect())
			return false;

			$tmp = pg_query($this->dbConn, "SELECT * FROM page_content WHERE id=$id");
			$tmp = pg_fetch_assoc($tmp);
			$pageId = $tmp['page_id'];
			$currentPos = $tmp['position'];

			switch($how)
			{
				case 'up':
				$tmp = pg_query($this->dbConn, "SELECT id, position FROM page_content WHERE page_id=$pageId AND position<$currentPos ORDER BY position DESC LIMIT 1");
				$tmp = pg_fetch_all($tmp);
				break;
				case 'down':
				$tmp = pg_query($this->dbConn, "SELECT id, position FROM page_content WHERE page_id=$pageId AND position>$currentPos ORDER BY position LIMIT 1");
				$tmp = pg_fetch_all($tmp);
				break;
				case 'first':
//$tmp = $db->query("SELECT element_id, position FROM page_content WHERE page_id=$pageId AND position<$currentPos ORDER BY position LIMIT 1")->fetchAll();
				$tmp = pg_query($this->dbConn, "SELECT id, position FROM page_content WHERE page_id=$pageId AND position<$currentPos ORDER BY position");
				$tmp = pg_fetch_all($tmp);
				foreach($tmp as $t)
				{
					pg_query($this->dbConn, 'UPDATE page_content SET position='.($t['position'] + 1).' WHERE id='.$t['id']);
				}
				pg_query($this->dbConn, "UPDATE page_content SET position=1 WHERE id=$id");
				$this->DBDisconnect();
				return true;
				break;
				case 'last':
//$tmp = $db->query("SELECT element_id, position FROM page_content WHERE page_id=$pageId AND position>$currentPos ORDER BY position DESC LIMIT 1")->fetchAll();
				$tmp = pg_query($this->dbConn, "SELECT id, position FROM page_content WHERE page_id=$pageId AND position>$currentPos ORDER BY position DESC");
				$tmp = pg_fetch_all($tmp);
				$max = pg_query($this->dbConn, "SELECT max(position) FROM page_content WHERE page_id=$pageId");
				$max = pg_fetch_assoc($max);
				$max = $max['max'];
				foreach($tmp as $t)
				{
					pg_query($this->dbConn, 'UPDATE page_content SET position='.($t['position'] - 1).' WHERE id='.$t['id']);
				}
				pg_query($this->dbConn, "UPDATE page_content SET position=$max WHERE id=$id");
				$this->DBDisconnect();
				return true;
				break;
			}    
				if(!$tmp)
				return true;
				$newPosition = $tmp[0]['position'];
				$otherElement = $tmp[0]['id'];
				pg_query($this->dbConn, "UPDATE page_content SET position=$newPosition WHERE id=$id");
				pg_query($this->dbConn, "UPDATE page_content SET position=$currentPos WHERE id=$otherElement");
				$this->DBDisconnect();
				return true;
		}

//----------------------------------------------------------------------------------------------

		public function DBChangeElementName($elementId = 0, $newName = '')
		{
			if(!$elementId)
			return false;

			if(!$this->DBConnect())
			return false;

			$elementId = pg_escape_string($this->dbConn, $elementId);
			$newName = pg_escape_string($this->dbConn, $newName);

			pg_query($this->dbConn, "UPDATE page_content SET custom_name='$newName' WHERE id=$elementId");
			$this->DBDisconnect();
			return true;
		}

//----------------------------------------------------------------------------------------------

		public function printErrLog($str = '')
		{
//if(!$str)
//return;
//krumo(debug_backtrace()); 
			$t = debug_backtrace();   
			$f = fopen('err_log.txt', 'a+');
			fwrite($f, date('d.m.Y-H:i:s', time()).' '.$this->type.', '.$t[1]['function'].', page '.$this->pageId.': '.$str."\n\r");
			fclose($f);
		}

//----------------------------------------------------------------------------------------------

		protected function printElementName($elementId = 0, $edit = false, $pageId = 0, $additional = '')
		{
			if(!$this->DBConnect())
			return false;

			$name = pg_query($this->dbConn, "SELECT * FROM page_content WHERE id=$elementId");
			$name = pg_fetch_assoc($name);
			$typeId = $name['content_type_id'];
			$name = $name['custom_name'];
			$defaultName = pg_query("SELECT * FROM content_type WHERE id=$typeId");
			$defaultName = pg_fetch_assoc($defaultName);
			$defaultName = $defaultName['default_header'];

			$this->DBDisconnect();

			if(!$edit)
			{
?>
				<div style="float: none; clear: both;">
				<a name="element<?=$elementId?>"></a>
				<label class="element-header"><?=(($name ? $name : $defaultName).($additional ? '&nbsp;&nbsp;&nbsp;'.$additional : ''))?></label>
				</div>
<?php
				if($this->type != 'curs_exchange')
				{
?>
					<br>
<?php
				}
			}
			else
			{
?>
				<a name="element<?=$elementId?>"></a>
				<div style="float: none; clear: both;">
				<div style="float: left;">
				<label class="element-header"><?=(($name ? $name : ($defaultName ? $defaultName : 'Без названия')).($additional ? '&nbsp;&nbsp;&nbsp;'.$additional : ''))?></label>
				</div>
				<div style="float: right;">
				<form method="post" action="<?=$this->actionPage?>">
				Новое название блока
				<input type="text" class="flat-text" name="new_name" size="42">
				<input type="hidden" name="element_id" value="<?=$elementId?>">
				<input type="hidden" name="action" value="changeElementName">
				<input type="hidden" name="element_type" value="<?=$this->type?>">
				<input type="hidden" name="page_id" value="<?=$pageId?>">
				<input type="submit" class="flat-button" value="Сменить">
				</form>
				</div>
				</div> 
<?php
			}
		}

//----------------------------------------------------------------------------------------------

		public function DBChangeVisibility($elementId = 0, $hide = false)
		{
			if(!$elementId)
			{
				return false;
			}
			if(!$this->DBConnect())
			return false;
			pg_query($this->dbConn, "UPDATE page_content SET visible='".($hide ? 'FALSE' : 'TRUE')."' WHERE id=$elementId");
			$this->DBDisconnect();
			return true;
		}

//----------------------------------------------------------------------------------------------

		public function DBDuplicate($elementId, $newPageId)
		{
			if(!$elementId || !$newPageId)
			return false;
			if(!$this->DBConnect())
			return false;

			$element = pg_query($this->dbConn, "SELECT * FROM page_content WHERE id=$elementId");
			$element = pg_fetch_assoc($element);
			$newElementId = pg_query($this->dbConn, "INSERT INTO page_content (content_type_id, page_id, visible, position, custom_name) VALUES (".$element['content_type_id'].", $newPageId, '".$element['visible']."', ".$element['position'].", '".$element['custom_name']."') RETURNING id");
			$newElementId = pg_fetch_assoc($newElementId);
			$newElementId = $newElementId['id'];
			$this->DBDisconnect();
			$this->DBDuplicateData($elementId, $newElementId, $newPageId);
			return true;
		}

//----------------------------------------------------------------------------------------------

		protected function DBDuplicateData($elementId, $newElementId, $newPageId)
		{
			if(!$elementId || !$newElementId || !$newPageId)
			return false;
			if(!$this->DBConnect())
			return false;

			$content = pg_query($this->dbConn, "SELECT * FROM ".$this->type." WHERE element_id=$elementId");
			$content = pg_fetch_all($content);
			if(!$content)
			{
				$this->DBDisconnect();
				return true;
			}   

			foreach($content as $c)
			{
				$query = "INSERT INTO ".$this->type;
				$keys = " (";
				$values = ") VALUES (";
				$t = false;
				foreach($c as $k=>$v)
				{   
					if($k == 'id')                   
					continue;
					if($t)
					{
						$keys .= ', ';
						$values .= ', ';
					}
					else
					$t = true;

					if($k == 'element_id')
					$v = $newElementId;

					$keys .= '"'."$k".'"';
					$values .= "'$v'";
				}
				$query = $query.$keys.$values.')';
				pg_query($this->dbConn, $query);
			}

			$this->DBDisconnect();
			return true;
		}

	//----------------------------------------------------------------------------------------------

	}
?>