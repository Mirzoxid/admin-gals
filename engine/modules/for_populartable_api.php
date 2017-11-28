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

	if (isset($post->token) && isset($post->cat)) {

		if (!empty($post->token)) {

			if ($dle_api->load_table(USERPREFIX . "_token", "*", "token = '".$post->token."'")) {

				$count_n = $db->query("SELECT * FROM " .USERPREFIX. "_post WHERE category = '{$post->cat}'");
				$cat = ($post->cat == "" || intval($post->cat) == 0) ? 0 : intval($post->cat);
				$type = ($post->type == 'movie') ? 'movie' : 'serials';
				$where = ($cat == 0) ? "WHERE 1" :  "WHERE dle_post.category REGEXP '(^|[^0-9]){$cat}([^0-9]|$)'";
				$skip = (empty($post->skip)) ? 0 : intval($post->skip);
				$limit = (empty($post->limit)) ? 10 : intval($post->limit);
				$cnt = ($post->skip >= $count_n->num_rows) ? 0 : $post->post;

				if ($arr = $dle_api->db->query("SELECT dle_post.title, dle_post.id, dle_post.category, dle_post.date,dle_post.short_story, (SELECT e.news_read n_r FROM dle_post_extras e WHERE e.news_id = dle_post.id) news_read FROM dle_post JOIN $type ON dle_post.id = {$type}.post_id {$where} order by news_read desc limit $skip,$limit")) {

					$arr1 = null;
					while ($var = $dle_api->db->get_row()) {
						$arr1[] = $var;
					}

					$json_arr = [

						"code" => 1,

						"message" => "Table loaded",

						"max_movies_count" => $arr->num_rows,

						"data" => $arr1

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
