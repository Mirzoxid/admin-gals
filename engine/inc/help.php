<?PHP
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2017 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: help.php
-----------------------------------------------------
 Назначение: помощь
=====================================================
*/
if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

$help_sections = array();
$section = totranslit($_REQUEST['section']);

$selected_language = $config['langs'];

if (isset( $_COOKIE['selected_language'] )) { 

	$_COOKIE['selected_language'] = totranslit( $_COOKIE['selected_language'], false, false );

	if ($_COOKIE['selected_language'] != "" AND @is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
		$selected_language = $_COOKIE['selected_language'];
	}

}

if ( file_exists( ROOT_DIR . '/language/' . $selected_language . '/help.lng' ) ) {
	require_once (ROOT_DIR . '/language/' . $selected_language . '/help.lng');
} else die("Language file not found");

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];


if($section){

	if(!isset($help_sections['title'][$section])){ die("Help section <b>$section</b> not found"); }

	echo"<div id=\"panel-help-section\" title=\"".$help_sections['title'][$section]."\" class=\"text-size-small\">".$help_sections['body'][$section]."</div>";
}
else{

	msg( "error", $lang['index_denied'], $lang['index_denied'] );
	
}
?>