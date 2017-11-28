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

	if (isset($post->token) && isset($post->cat) && isset($post->skip) && isset($post->limit)) {

	// var_dump($post);die;

		if (!empty($post->token)) {

			if ($dle_api->load_table(USERPREFIX . "_token", "*", "token = '".$post->token."'")) {

				$count_n = $db->query("SELECT * FROM " .USERPREFIX. "_post WHERE category = '{$post->cat}'");

				$cnt = ($post->skip >= $count_n->num_rows) ? 0 : intval($post->skip);
				$type = ($post->type == 'movie') ? 'movie' : 'serials';
				$cat = ( $post->cat == "" || intval($post->cat) == 0) ? 0 : intval($post->cat);
				$where = ($cat == 0) ? "WHERE 1" :  "WHERE category REGEXP '(^|[^0-9]){$cat}([^0-9]|$)'";
				// $where .=
				$skip = (empty($post->skip)) ? 0 : intval($post->skip);
				$limit = (empty($post->limit)) ? 10 : intval($post->limit);
				if ($dle_api->db->query("SELECT DISTINCT dle_post.title, dle_post.id, dle_post.category, dle_post.descr, dle_post.`date`, dle_post.short_story FROM dle_post RIGHT JOIN {$type} ON dle_post.id = {$type}.post_id {$where} and {$type}.post_id is not null ORDER BY date DESC LIMIT {$skip}, {$limit}")) {
				// var_dump($limit);die;
					// $arr['img'] = (strpos('/(src="\S+)/', $arr->))
					$arr = null;

					while($var = $dle_api->db->get_row()){
						$arr[] = $var;
					}

					$json_arr = [

						"code" => 1,

						"message" => "Table loaded",

						"max_movies_count" => count($arr),

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
