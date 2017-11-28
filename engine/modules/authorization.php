<?php

/**
 * Module for registering users with custom module
 * =======================================================
 * Автор:	Mirzohid Ulug'murodov
 * email:	mirzoxid92@mail.ru
 * =======================================================
 * Файл:  login.php
 * -------------------------------------------------------
 * Версия: 1.0.0 (08.10.2017)
 * =======================================================
 */

if (!defined('DATALIFEENGINE')) die("Ruxsatsiz xarakat!");

include('engine/api/api.class.php');

// var_dump($_POST);die;
$auth_log = "";


if (!empty($_POST) && isset($_POST['name']) && isset($_POST['password'])) {
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	echo file_get_contents('php://input');
	


}


if (!$myModule) {
	// Если в кеше ничего нет - запускаем работу модуля	
	if(file_exists( TEMPLATE_DIR.'/authorization.tpl' ) ) {

		// Проверяем определена ли переменная $tpl и класс dle_template
		if( !isset( $tpl ) ) {
			$tpl = new dle_template();
			$tpl->dir = TEMPLATE_DIR;
		} else {
			$tpl->result['myModule'] = '';
		}

		if (isset($_SESSION['dle_user_id']) && !empty($_SESSION['dle_user_id'])) {
			$tpl->load_template('author.tpl');
		} else {
		}
			$tpl->load_template('authorization.tpl');	

		if (isset($_GET['post'])) {
			$res = $dle_api->load_table(USERPREFIX.'_'.$_GET['post']);
			$tpl->set('{post}', $res['short_story']);
		}
		$tpl->set('{content}', $content);
		$tpl->compile('content');
		$tpl->clear();
		
		
	} else {
		// When TPL file not found
		$myModule = '<b style="color:red">Template file not found: '.$config['skin'].'/'.$myConfig['template'].'.tpl</b>';
	}
} 

// echo result of module
echo $myModule;
?>