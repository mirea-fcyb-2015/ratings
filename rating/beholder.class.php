<?php
	class beholder
	{
		private $dbPath; 
		public function __construct()
		{
			require('variables_disc.php');
			$this->dbPath = $dbPath;
		}

//----------------------------------------------------------------------------------------------

		public function fixAction($element = '', $action = '', $pageId = '')
		{                                          
			$element = pg_escape_string($element);
			$action = pg_escape_string($action);
			$pageId = pg_escape_string(($pageId ? $pageId : 'NULL'));
			$now = time();
			$db = $this->DBConnect();
			$db->exec("INSERT INTO log (element, action, page_id, time) VALUES ('$element', '$action', $pageId, $now)");
		}

	//----------------------------------------------------------------------------------------------

		protected function DBConnect()
		{
			$db = new PDO($this->dbPath);
			return $db;
		}

	//----------------------------------------------------------------------------------------------

		public function showStatisctics()
		{
			$db = $this->DBConnect();
			$stats = $db->query("SELECT * FROM log")->fetchAll();
			$tmp = array();

			foreach($stats as $s)
			$tmp[$s['element']][$s['action']] += 1;

?>
			<table style="border-collapse: collapse; border-width: 1px; border-color: black; border-style: solid;">
<?php

			foreach($tmp as $e=>$tm)
			{
?>
				<tr>
				<td colspan="2" style="border-collapse: collapse; border-width: 1px; border-color: black; border-style: solid;"><b><?=$e?></b></td>
				</tr>
<?php
				foreach($tm as $t=>$m)
				{
?>
					<tr>
					<td style="border-collapse: collapse; border-width: 1px; border-color: black; border-style: solid;"><?=$t?></td>
					<td style="border-collapse: collapse; border-width: 1px; border-color: black; border-style: solid;"><?=$m?></td>
					</tr>
<?php
				}
			}
?>
			</table>
<?php
}
?>