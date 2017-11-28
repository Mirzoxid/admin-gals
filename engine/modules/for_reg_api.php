<?php

/**
 * Module for registering users with custom module
 * =======================================================
 * Автор:	Mirzohid Ulug'murodov
 * email:	mirzoxid92@mail.ru
 * =======================================================
 * Файл:  reg.php
 * -------------------------------------------------------
 * Версия: 1.0.0 (08.10.2017)
 * =======================================================
 */

if (!defined('DATALIFEENGINE')) die("Ruxsatsiz xarakat!");

include('engine/api/api.class.php');
// var_dump($post);die;
$reg_log = "";
$json_arr = null;
$code = 0;
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
	$post = json_decode(file_get_contents("php://input"));
	if (!empty($post) && isset($post->name) && isset($post->email) && isset($post->password1)&& isset($post->password2)) {
		if ($post->password1 === $post->password2 && $post->password1 !== "" && $post->name !== "" && $post->email !== "") {
			switch ($dle_api->external_register($post->name, $post->password1, $post->email, 4)) {
				case 1:
					$code = 1;
					$reg_log = "Accept register";
					break;
				case 2:
					$reg_log = "Login exist";
					break;
				case 3:
					$reg_log = "Mail exist";
					break;
				case 4:
					$reg_log = "Group error";
					break;
				default:
					$reg_log = "Register error";
					break;
			}
		} else {
			$reg_log = "Password confirm error or null data"; 
		}
		$json_arr = [
			'code' => $code,
			'message' => $reg_log
		];
	} else {
		$json_arr = [
			'code' => 0,
			'error' => 'Key error or other )) !' 
		];
	}
			echo(json_encode($json_arr)); exit();
} else {
	echo json_encode(array());exit();
}
