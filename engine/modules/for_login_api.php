<?php

/**
 * Module for autorization users with custom module
 * =======================================================
 * Автор:	Mirzohid Ulug'murodov
 * email:	mirzoxid92@mail.ru
 * =======================================================
 * Файл:  for_api.php
 * -------------------------------------------------------
 * Версия: 1.0.0 (08.10.2017)
 * =======================================================
 */

if (!defined('DATALIFEENGINE')) die("Ruxsatsiz xarakat!");

include('engine/api/api.class.php');
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	$auth_log = "";
//POST so'rov mavjudligini tekshirish
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$json_arr = array();
	$arr = array();
	$post = json_decode(file_get_contents("php://input"));
	if ((isset($post->name) and isset($post->password)) and (!empty($post->password) and !empty($post->password))) {
		if ($dle_api->external_auth($post->name, $post->password)) {
			$login = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE name = '{$post->name}'" );
			$token = array();
			$where = "user_id = '" . $login['user_id'] . "'";
			$user_id = $login['user_id']; $token1 = md5($login['name'] . $login['password'] . time()); $date = date("Y-m-d H:i:s");
			if(!$dle_api->load_table(USERPREFIX . "_token", "*", $where, false))
			{
				$_SESSION['token'] = $arr['token'] = $token1;
				$_SESSION['token_date'] = $arr['token_date'] = $date;
				$db->query("INSERT INTO `".USERPREFIX."_token` (`user_id`, `token`, `token_date`) VALUES ('$user_id', '$token1', '$date')");
			} else {
				$_SESSION['token'] = $arr['token'] = $token1;
				$_SESSION['token_date'] = $arr['token_date'] = $date;
				$db->query("UPDATE " . USERPREFIX . "_token SET `token` = '$token1', `token_date` = '$date' WHERE `user_id` = '$user_id'");
			}
			$_SESSION['dle_user_id'] = $arr['dle_user_id'] = $login['user_id'];
			$_SESSION['dle_password'] = $login['password'];
			$_SESSION['dle_name'] = $arr['dle_name'] = $login['name'];
			$_SESSION['dle_email'] = $arr['dle_email'] = $login['email'];
			$json_arr = [
				'code' => 1,
				'message' => 'Login accept!',
				'data' => $arr
			];
		} else {
			$auth_log = "Login or password error!";
			$json_arr = [
				'code' => 0,
				'error' => 'Login or Password error!'
			];
		}
	} elseif ((isset($post->name) and isset($post->password)) and (empty($post->name) or empty($post->password))) {
		$json_arr = [
			'code' => 0,
			'error' => 'Password or Login is empty!'
		];
	} else {
		$json_arr = [
			'code' => 0,
			'error' => 'Key error or other )) !'
		];
	}
	echo json_encode($json_arr);exit();
} else {
	echo json_encode(array());exit();
}

?>
