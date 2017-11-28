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
	$json_arr = array();
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	if ($dle_api->external_auth($_POST['name'], $_POST['password'])) {
		session_regenerate_id();
		$login = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE name = '{$_POST['name']}'" );

		$_SESSION['dle_user_id'] = $login['user_id'];
		$_SESSION['dle_password'] = md5($login['password']);
		$json_arr = $login;

		$auth_log = "Accept autorization! Salom {$login['name']}";
		// header("Location: index.php?do=login");

	} else {
		$auth_log = "Login or password error";
		$json_arr = ['error' => 'autorizatsiyada xato', 'code' => 0];
	}
	echo json_encode($json_arr);exit();
	if ($auth_log !== "") {
		msgbox($lang['all_info'], $auth_log);
	}
}



if (!$myModule) {
	// Если в кеше ничего нет - запускаем работу модуля	
	if(file_exists( TEMPLATE_DIR.'/loginm.tpl' ) ) {

		// Проверяем определена ли переменная $tpl и класс dle_template
		if( !isset( $tpl ) ) {
			$tpl = new dle_template();
			$tpl->dir = TEMPLATE_DIR;
		} else {
			$tpl->result['myModule'] = '';
		}

		if (isset($_SESSION['dle_user_id']) && !empty($_SESSION['dle_user_id'])) {
			$tpl->load_template('logino.tpl');
		} else {
			$tpl->load_template('loginm.tpl');		
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