<?php
//include_once('beholder.class.php');
	include('variables_disc.php');
	include_once('journal.class.php');
	include_once('element.class.php');

	$loggedIn = false;
	$okay = TRUE;
	$message = NULL;
//krumo($_POST);
	session_start();
//var_dump($_POST);
	$loggedIn = isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : false;
	$dbconnect = pg_connect("host=localhost dbname=ratings user=postgres password=123456") or die('Could not connect: ' . pg_last_error());
	$group_id = pg_query("SELECT group_id FROM j_groups WHERE element_id=".$_POST['element_id']);
	$group_id = pg_fetch_assoc($group_id);
	$group_id = $group_id['group_id'];
//$b = new beholder();

	switch($_POST['action'])
	{
//----------------------------------------------------
//Действия с данными в элементе
		case 'addElementData':  
//$b->fixAction($_POST['element_type'], $_POST['action'], $_POST['page_id']);
		$j = new journal();
		$j->DBAddElementData($_POST);

//header('Location:'.$editPage.'?id='.$_POST['page_id'].'#element'.$_POST['element_id']);
		header('Location:editMarkForm.php?group_id='.$group_id.'&disc_id='.$_POST['page_id']);
		break;
		case 'editElementData':
//$b->fixAction($_POST['element_type'], $_POST['action'], $_POST['page_id']);
//$e = new $_POST['element_type']($_POST['page_id']);
		$j = new journal();

		$j->DBEditElementData($_POST);


//header('Location:'.$editPage.'?id='.$_POST['page_id'].'#element'.$_POST['element_id']);
		header('Location:editMarkForm.php?group_id='.$group_id.'&disc_id='.$_POST['page_id']);
		break;
		case 'deleteElementData';
//$b->fixAction($_POST['element_type'], $_POST['action'], $_POST['page_id']);
		$j = new journal();
		$j->DBDeleteElementData(@$_POST['id'], $_POST);
//header('Location:'.$editPage.'?id='.$_POST['page_id'].'#element'.$_POST['element_id']);
		header('Location:editMarkForm.php?group_id='.$group_id.'&disc_id='.$_POST['page_id']);
		break;
	}
?>
