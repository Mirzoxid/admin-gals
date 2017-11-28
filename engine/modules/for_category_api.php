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



function where_popular_table($value=null)

{

	if ($value == null) {

		return false;

	}



}



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

	// var_dump($post);die;

	if (isset($post->token)) {

		if (!empty($post->token)) {

			if ($dle_api->load_table(USERPREFIX . "_token", "*", "token = '".$post->token."'")) {

        $obj = null;
        $arr = [];
				if ($dle_api->db->query("SELECT id, name FROM dle_category")) {

          while ($var = $dle_api->db->get_row()) {
            $arr[] = $var;
          }

					$json_arr = [

						"code" => 1,

						"message" => "Categories loaded",

						"data" => $arr

					];

				} else {

					$json_arr = [

						"code" => 0,

						"error" => "Table not loaded! Category, skip or limit undifined!"

					];

				}



			} else {

				$json_arr = [

					"code" => 0,

					"error" => "Wrong token!"

				];

			}

		} else {

			$json_arr = [

				"code" => 0,

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

	echo json_encode();exit();

}



?>
