<?php
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
 Файл: search.php
-----------------------------------------------------
 Назначение: Быстрый поиск
=====================================================
*/

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', substr( dirname(  __FILE__ ), 0, -12 ) );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );

include ENGINE_DIR . '/data/config.php';

date_default_timezone_set ( $config['date_adjust'] );

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/search.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "https://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

//################# Определение групп пользователей
$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}

dle_session();

require_once ENGINE_DIR . '/modules/sitelogin.php';
require_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';
if( ! $is_logged ) $member_id['user_group'] = 5;

@header( "Content-type: text/html; charset=" . $config['charset'] );

if( !$config['fast_search'] OR !$user_group[$member_id['user_group']]['allow_search'] ) die( "error" );

//####################################################################################################################
//                    Определение категорий и их параметры
//####################################################################################################################
$cat_info = get_vars( "category" );

if( ! is_array( $cat_info ) ) {
	$cat_info = array ();
	
	$db->query( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );
	while ( $row = $db->get_row() ) {
		
		$cat_info[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$cat_info[$row['id']][$key] = stripslashes( $value );
		}
	
	}
	set_vars( "category", $cat_info );
	$db->free();
}

if( $_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash ) {

	echo $lang['sess_error'];
	die();

}

$query = $db->safesql( htmlspecialchars ( trim(  strip_tags (convert_unicode( $_POST['query'], $config['charset'] ) ) ), ENT_QUOTES, $config['charset']) );

if( $query == "" ) die();

$buffer = "";

$_TIME = time ();
$this_date = date( "Y-m-d H:i:s", $_TIME );
if( $config['no_date'] AND !$config['news_future'] ) $this_date = " AND " . PREFIX . "_post.date < '" . $this_date . "'"; else $this_date = "";

$disable_search = array();

if( count( $cat_info ) ) {
	foreach ($cat_info as $cats) {
		if($cats['disable_search']) $disable_search[] = $cats['id'];
	}
}

if( $user_group[$member_id['user_group']]['not_allow_cats'] ) {
	$n_c = explode(',', $user_group[$member_id['user_group']]['not_allow_cats'] );
	
	foreach ($n_c as $cats) {
		if(!in_array($cats, $disable_search)) $disable_search[] = $cats;
	}

}

if( count( $disable_search ) ) {

	if( $config['allow_multi_category'] ) {
		
		$where_category = " AND category NOT REGEXP '[[:<:]](" . implode ("|", $disable_search ) . ")[[:>:]]'";
	
	} else {
		
		$where_category = " AND category NOT IN ('" . implode ("','", $disable_search ) . "')";
	}
	
} else $where_category = "";

$db->query("SELECT id, short_story, title, date, alt_name, category FROM " . PREFIX . "_post LEFT JOIN " . PREFIX . "_post_extras ON (" . PREFIX . "_post.id=" . PREFIX . "_post_extras.news_id)  WHERE " . PREFIX . "_post.approve=1 AND " . PREFIX . "_post_extras.disable_search=0".$this_date.$where_category." AND (short_story LIKE '%{$query}%' OR full_story LIKE '%{$query}%' OR xfields LIKE '%{$query}%' OR title LIKE '%{$query}%') ORDER by date DESC LIMIT 5");

while($row = $db->get_row()){

		$row['date'] = strtotime( $row['date'] );
		$row['category'] = intval( $row['category'] );

		if( $config['allow_alt_url'] ) {
			
			if( $config['seo_type'] == 1 OR $config['seo_type'] == 2 ) {
				
				if( $row['category'] and $config['seo_type'] == 2 ) {
					
					$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
				
				} else {
					
					$full_link = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
				
				}
			
			} else {
				
				$full_link = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
			}
		
		} else {
			
			$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
		
		}

		$row['title'] = stripslashes($row['title']);

		if( dle_strlen( $row['title'], $config['charset'] ) > 43 ) $title = dle_substr( $row['title'], 0, 43, $config['charset'] ) . " ...";
		else $title = $row['title'];

		$row['short_story'] = trim (htmlspecialchars( strip_tags( stripslashes( str_replace( array("<br>", "<br />", "&nbsp;"), " ", $row['short_story'] ) ) ), ENT_QUOTES, $config['charset'] ) );

		if (stripos ( $row['short_story'], "[hide" ) !== false ) {
			
			$row['short_story'] = preg_replace_callback ( "#\[hide(.*?)\](.+?)\[/hide\]#is", 
				function ($matches) use ($member_id, $user_group, $lang) {
					
					$matches[1] = str_replace(array("=", " "), "", $matches[1]);
					$matches[2] = $matches[2];
	
					if( $matches[1] ) {
						
						$groups = explode( ',', $matches[1] );
	
						if( in_array( $member_id['user_group'], $groups ) OR $member_id['user_group'] == "1") {
							return $matches[2];
						} else return "";
						
					} else {
						
						if( $user_group[$member_id['user_group']]['allow_hide'] ) return $matches[2]; else return "";
						
					}
	
			}, $row['short_story'] );
		}

		if( dle_strlen( $row['short_story'], $config['charset'] ) > 150 ) $description = dle_substr( $row['short_story'], 0, 150, $config['charset'] ) . " ...";
		else $description = $row['short_story'];

		$description = str_replace('&amp;', '&', $description);

		$description = preg_replace( "'\[attachment=(.*?)\]'si", "", $description );

	    $buffer .= "<a href=\"" . $full_link . "\"><span class=\"searchheading\">" . stripslashes( $title ) . "</span>";

		$buffer .= "<span>".$description."</span></a>";

}

if ( !$buffer ) $buffer .= "<span class=\"notfound\">{$lang['related_not_found']}</span>";

$buffer .= '<span class="seperator"><a href="'.$config['http_home_url'].'?do=search&amp;mode=advanced&amp;subaction=search&amp;story='.$query.'">'.$lang['s_ffullstart'].'</a></span><br class="break" />';

echo $buffer;

?>