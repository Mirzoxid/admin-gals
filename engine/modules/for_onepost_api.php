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

 LAST NEWS

 */



if (!defined('DATALIFEENGINE')) die("Ruxsatsiz xarakat!");



include('engine/api/api.class.php');

	header("Access-Control-Allow-Origin: *");

	header("Content-Type: application/json; charset=UTF-8");

	header("Access-Control-Allow-Methods: POST");

	header("Access-Control-Max-Age: 3600");

	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$auth_log = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$json_arr = array();

	$arr = array();

	$post = json_decode(file_get_contents("php://input"));

	if (isset($post->token) && isset($post->id)) {

		if (!empty($post->token)) {

			if ($dle_api->load_table(USERPREFIX . "_token", "*", "token = '".$post->token."'")) {


				if ($arr = $dle_api->load_table(USERPREFIX . "_post", "title, id, short_story, category, xfields, `date`", "id = '".$post->id."'", false)) {

					if ($obj = $dle_api->load_table("serials", "name, url, season_id, serial", "post_id = '".$post->id."'", true, 'id', "desc")) {
						$arr['urls'] = $obj;
					} elseif ($obj = $dle_api->load_table("movie", "name, url", "post_id = '".$post->id."'", true, 'id', "desc")) {
						$arr['urls'] = $obj;
					}
					if ($arr['urls'] != null) {
						$dle_api->db->query("UPDATE dle_post_extras SET news_read = news_read + 1 WHERE news_id = '{$post->id}'");
					}
					http_response_code(200);
					$json_arr = [
						"code" => 200,

						"message" => "Table loaded.",

						"data" => $arr
					];

				} else {
					http_response_code(204);
					$json_arr = [

						"code" => 204,

						"error" => "Table not loaded! Id undifined!"

					];

				}

			} else {
				http_response_code(401);
				$json_arr = [

					"code" => 401,

					"error" => "Wrong token!"

				];

			}

		} else {

			http_response_code(401);
			$json_arr = [
				"code" => 401,

				"error" => "Token empty )) !"

			];

		}

	} else {

		$json_arr = [

			"code" => 0,

			"error" => "Key error or other )) !"

		];

	}



	echo json_encode($json_arr);exit();

} else {

	echo json_encode(array());exit();

}



?>
