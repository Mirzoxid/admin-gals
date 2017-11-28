<?php

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}


$skin_header = <<<HTML
<!doctype html>
<html>
<head>
	<meta charset="{$config['charset']}">
	<title>DataLife Engine - Мастер обновления</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="HandheldFriendly" content="true">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width"> 
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link href="../engine/skins/fonts/fontawesome/styles.min.css?v=new" media="screen" rel="stylesheet" type="text/css" />
    <link href="../engine/skins/stylesheets/application.css?v=new" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../engine/skins/javascripts/application.js?v=new"></script>
</head>
<body class="no-theme">
<script language="javascript" type="text/javascript">
<!--
var dle_act_lang   = [];
var cal_language   = {en:{months:[],dayOfWeek:[]}};
var filedefaulttext= '';
var filebtntext    = '';
//-->
</script>
<div class="navbar navbar-inverse bg-primary-700">
	<div class="navbar-header">
		<a class="navbar-brand" href="#">Мастер обновления DataLife Engine</a>
	</div>
</div>
<div class="page-container">
	<div class="page-content">
		<div class="col-md-8 col-md-offset-2" style="margin-top: 80px;">
<!--MAIN area-->
HTML;

// ********************************************************************************
// Skin FOOTER
// ********************************************************************************
$skin_footer = <<<HTML
	 <!--MAIN area-->
    </div>
  </div>
</div>
</body>
</html>
HTML;

function msgbox($type, $title, $text, $back=FALSE){
global $lang, $skin_header, $skin_footer, $config;

$_SESSION['dle_update']=intval($_SESSION['dle_update'])+1;
if( $back ) $post_action=$config['http_home_url']; else $post_action="index.php";

  echo $skin_header;

echo <<<HTML
<form action="{$post_action}" method="get">
<div class="panel panel-default">
  <div class="panel-heading">
    {$title}
  </div>
  <div class="panel-body">
		{$text}
  </div>
<div class="panel-footer">
	<button type="submit" class="btn bg-teal btn-sm btn-raised position-left"><i class="fa fa-arrow-circle-o-right position-left"></i>Продолжить</button>
</div>
</div>
<input type="hidden" name="next" value="{$_SESSION['dle_update']}">
</form>
HTML;

  echo $skin_footer;

  exit();
}

$login_panel = <<<HTML
<!doctype html>
<html>
<head>
	<meta charset="{$config['charset']}">
	<title>DataLife Engine - Мастер обновления</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="HandheldFriendly" content="true">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width"> 
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link href="../engine/skins/fonts/fontawesome/styles.min.css?v=new" media="screen" rel="stylesheet" type="text/css" />
    <link href="../engine/skins/stylesheets/application.css?v=new" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../engine/skins/javascripts/application.js?v=new"></script>
</head>
<body class="no-theme">
<script language="javascript" type="text/javascript">
<!--
var dle_act_lang   = [];
var cal_language   = {en:{months:[],dayOfWeek:[]}};
var filedefaulttext= '';
var filebtntext    = '';
//-->
</script>
<div class="navbar navbar-inverse bg-primary-700">
	<div class="navbar-header">
		<a class="navbar-brand" href="#">Мастер обновления DataLife Engine</a>
	</div>
</div>
<div class="page-container">
	<div class="page-content">
		<div class="col-md-4 col-md-offset-4">
<!--MAIN area-->

	<div class="panel panel-default" style="margin-top: 80px;">

      <div class="panel-heading">
       Требуется авторизация
      </div>
	  
      <div class="panel-body">
        <form  name="login" action="" method="post" class="separate-sections"><input type="hidden" name="action" value="dologin">
		{result}
			<div class="form-group has-feedback has-feedback-left">
				<input class="form-control" type="text" name="username" placeholder="Введите свой логин">
				<div class="form-control-feedback">
					<i class="fa fa-user text-muted"></i>
				</div>
			</div>
			<div class="form-group has-feedback has-feedback-left">
				<input class="form-control" type="password" name="password" placeholder="Введите свой пароль">
				<div class="form-control-feedback">
					<i class="fa fa-lock text-muted"></i>
				</div>
			</div>


		  <div class="input-group addon-left">
			Для обновления скрипта, вам необходимо ввести администраторский логин и пароль.
			<br /><br /><button type="submit" class="btn btn-primary btn-raised btn-block">Войти <i class="fa fa-sign-in"></i></button>
          </div>

        </form>

        <div>
          {result}
        </div>
      </div>

    </div>
	<div class="text-muted text-size-small text-center">DataLife Engine&reg;  Copyright 2004-2017<br>&copy; <a href="https://dle-news.ru/" target="_blank">SoftNews Media Group</a> All rights reserved.</div>


	 <!--MAIN area-->
    </div>
  </div>
</div>
</body>
</html>
</html>
HTML;

$is_logged = false;
$result="";

if (isset( $_SESSION['dle_user_id'] ) AND  intval( $_SESSION['dle_user_id'] ) > 0 AND $_SESSION['dle_password'] ) {

	if (!defined('USERPREFIX')) {
		define('USERPREFIX', PREFIX);
	}

	$member_id = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE user_id='" . intval( $_SESSION['dle_user_id'] ) . "'" );

	if( $member_id['user_id'] AND $member_id['password'] AND $member_id['user_group'] == 1 AND md5($member_id['password']) == $_SESSION['dle_password'] ) {
			
		$is_logged = true;
		
	} else {
		$member_id = array ();
		$is_logged = false;
	}

	$db->free();
}

if ($_POST['action'] == "dologin") {

	$login_name = $db->safesql($_POST['username']);
	
	$login_password = md5($_POST['password']);

	if (version_compare($version_id, '4.2', ">")) $pass = md5($login_password); else $pass = $login_password;

	if (!defined('USERPREFIX')) {
		define('USERPREFIX', PREFIX);
	}

	$member_id = $db->super_query("SELECT * FROM " . USERPREFIX . "_users where name='{$login_name}' AND user_group = '1'");

	if( $member_id['user_id'] AND $member_id['password'] ) {
			
		if( is_md5hash( $member_id['password'] ) ) {
				
			if($member_id['password'] == $pass ) {
				$is_logged = true;
			}
				
		} else {
				
			if(password_verify($_POST['password'], $member_id['password'] ) ) {
				$is_logged = true;
			}

		}

	} else {
		$member_id = array ();
		$is_logged = false;
	}
	
	if ( $is_logged ){

		$_SESSION['dle_user_id'] = $member_id['user_id'];
	    $_SESSION['dle_password'] = md5($member_id['password']);

	} else $result="<span class=\"text-danger\">Неверно введен логин или пароль!</span>";

	$db->free();
}

if(!$is_logged) {
	$login_panel = str_replace("{result}", $result, $login_panel);
	echo $login_panel;
	exit();
}

if(!is_writable(ENGINE_DIR.'/data/')){
	msgbox("info","Информация", "Установите права для записи на папку 'engine/data/' CHMOD 777");
}

if(!is_writable(ENGINE_DIR.'/data/config.php')){
	msgbox("info","Информация", "Установите права для записи на файл 'engine/data/config.php' CHMOD 666");
}

if(!is_writable(ENGINE_DIR.'/data/videoconfig.php')){
  msgbox("info","Информация", "Установите права для записи на файл 'engine/data/videoconfig.php' CHMOD 666");
}

if(!is_writable(ENGINE_DIR.'/data/dbconfig.php')){
	msgbox("info","Информация", "Установите права для записи на файл 'engine/data/dbconfig.php' CHMOD 666");
}

if(!is_writable(ENGINE_DIR.'/data/xfields.txt')){
	msgbox("info","Информация", "Установите права для записи на файл 'engine/data/xfields.txt' CHMOD 666");
}

if( !$_SESSION['dle_update'] ) {

  echo $skin_header;
  
echo <<<HTML
<form action="index.php" method="get">
<input type="hidden" name="next" value="start">
<div class="panel panel-default">
  <div class="panel-heading">
    Информация
  </div>
	<div class="panel-body">
		<span class="text-danger"><b>Внимание:</b></span><br /><br />Прежде чем приступить к процедуре обновления скрипта и базы данных, убедитесь что вы создали и сохранили у себя полные бекапы файлов скрипта и базы данных. Процедура обновления вносит необратимые изменения в структуру базы данных, отмена которых в будущем будет невозможна, вернуть в предыдущее состояние базу данных, можно будет только путем восстановления бекапов базы данных. Также во время процедуры обновления скрипт выполняет тяжелые запросы к базе данных, выполнение которых может потребовать продолжительное время, поэтому обновление рекомендуется проводить во время минимальной нагрузки на сервер. Для больших сайтов, имеющие большое количество публикаций, рекомендуется предварительно проводить обновление на локальном компьютере.
	</div>
	<div class="panel-body">
		Текущая версия скрипта: <b>{$version_id}</b>, обновление будет пошагово произведено до версии: <b>{$dle_version}</b>
	</div>
	<div class="panel-footer">
		<button type="submit" class="btn bg-teal btn-sm btn-raised position-left"><i class="fa fa-arrow-circle-o-right position-left"></i>Продолжить</button>
	</div>
</div>
</form>
HTML;

	echo $skin_footer;
	
	$_SESSION['dle_update'] =1;
	exit();
}
?>