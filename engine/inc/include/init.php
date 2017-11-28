<?php ?><?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2017 SoftNews Media Group
=====================================================
     
=====================================================
 : init.php
-----------------------------------------------------
 : 
=====================================================
*/
if (!defined('DATALIFEENGINE')) {
    die("Hacking attempt!");
}
require_once (ENGINE_DIR . '/data/config.php');
require_once (ENGINE_DIR . '/classes/mysql.php');
require_once (ENGINE_DIR . '/data/dbconfig.php');
require_once (ENGINE_DIR . '/inc/include/functions.inc.php');
date_default_timezone_set($config['date_adjust']);
dle_session();
check_xss();
if ($config['only_ssl'] AND !isSSL() AND !isset($_SESSION['is_redirect'])) {
    $_SESSION['is_redirect'] = true;
    header("HTTP/1.0 301 Moved Permanently");
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    die("Redirect");
} elseif (isset($_SESSION['is_redirect'])) {
    unset($_SESSION['is_redirect']);
}
$auto_detect_config = false;
if ($config['http_home_url'] == "") {
    $config['http_home_url'] = explode($config['admin_path'], $_SERVER['PHP_SELF']);
    $config['http_home_url'] = reset($config['http_home_url']);
    $config['http_home_url'] = "https://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];
    $auto_detect_config = true;
}
$selected_language = $config['langs'];
if (isset($_POST['selected_language'])) {
    $_POST['selected_language'] = totranslit($_POST['selected_language'], false, false);
    if ($_POST['selected_language'] != "" AND @is_dir(ROOT_DIR . '/language/' . $_POST['selected_language'])) {
        $selected_language = $_POST['selected_language'];
        set_cookie("selected_language", $selected_language, 365);
    }
} elseif (isset($_COOKIE['selected_language'])) {
    $_COOKIE['selected_language'] = totranslit($_COOKIE['selected_language'], false, false);
    if ($_COOKIE['selected_language'] != "" AND @is_dir(ROOT_DIR . '/language/' . $_COOKIE['selected_language'])) {
        $selected_language = $_COOKIE['selected_language'];
    }
}
if (file_exists(ROOT_DIR . '/language/' . $selected_language . '/adminpanel.lng')) {
    require_once (ROOT_DIR . '/language/' . $selected_language . '/adminpanel.lng');
} else die("Language file not found");
$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];
@header("Content-type: text/html; charset=" . $config['charset']);
$is_loged_in = false;
$member_id = array();
$result = "";
$username = "";
$cmd5_password = "";
$allow_login = false;
$check_log = false;
$attempt_login = false;
$js_array = array();
$css_array = array();
$PHP_SELF = $_SERVER['PHP_SELF'];
$_IP = get_ip();
$_TIME = time();
$skin_header = "";
$skin_footer = "";
$allow_extra_login = false;
$login_params = array('ip_control' => $config['ip_control'], 'log_hash' => $config['log_hash']);
if ($config['extra_login'] AND stripos(PHP_SAPI, "apache") !== false AND !$_SESSION['dle_xtra']) {
    $allow_extra_login = true;
}
if (isset($_POST['action'])) $action = $_POST['action'];
else $action = $_GET['action'];
if (isset($_POST['mod'])) $mod = $_POST['mod'];
else $mod = $_GET['mod'];
$mod = totranslit($mod, true, false);
$action = totranslit($action, false, false);
//#################   
$user_group = get_vars("usergroup");
if (!$user_group) {
    $user_group = array();
    $db->query("SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC");
    while ($row = $db->get_row()) {
        $user_group[$row['id']] = array();
        foreach ($row as $key => $value) {
            $user_group[$row['id']][$key] = stripslashes($value);
        }
    }
    set_vars("usergroup", $user_group);
    $db->free();
}
//#################
//#################  
$cat_info = get_vars("category");
if (!is_array($cat_info)) {
    $cat_info = array();
    $db->query("SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC");
    while ($row = $db->get_row()) {
        $cat_info[$row['id']] = array();
        foreach ($row as $key => $value) {
            $cat_info[$row['id']][$key] = stripslashes($value);
        }
    }
    set_vars("category", $cat_info);
    $db->free();
}
if (count($cat_info)) {
    foreach ($cat_info as $key) {
        $cat[$key['id']] = $key['name'];
        $cat_parentid[$key['id']] = $key['parentid'];
    }
}
if ($_REQUEST['action'] == "logout") {
    set_cookie("dle_user_id", "", 0);
    set_cookie("dle_password", "", 0);
    set_cookie("dle_skin", "", 0);
    set_cookie("dle_newpm", "", 0);
    set_cookie("dle_hash", "", 0);
    set_cookie("dle_compl", "", 0);
    set_cookie(session_name(), "", 0);
    @session_unset();
    @session_destroy();
    if ($config['extra_login'] AND stripos(PHP_SAPI, "apache") !== false) auth();
    header("Location: ?mod=main");
    msg("info", $lang['index_msge'], $lang['index_exit']);
}
$allow_login = true;
if ($config['login_log']) $allow_login = check_allow_login($_IP, $config['login_log']);
if (!$allow_login) {
    $lang['login_err_2'] = str_replace("{time}", $config['login_ban_timeout'], $lang['login_err_2']);
    msg("info", $lang['index_msge'], $lang['login_err_2']);
}
if ($allow_login) {
    if ($allow_extra_login) {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) auth();
        $username = $_SERVER['PHP_AUTH_USER'];
        $cmd5_password = (string)$_SERVER['PHP_AUTH_PW'];
        $post = true;
        $check_log = true;
        $attempt_login = true;
    } elseif (intval($_SESSION['dle_user_id']) > 0 AND $_SESSION['dle_password']) {
        $username = intval($_SESSION['dle_user_id']);
        $cmd5_password = $_SESSION['dle_password'];
        $post = false;
        $attempt_login = true;
        if (!$_SESSION['check_log']) $check_log = true;
    } elseif (intval($_COOKIE['dle_user_id']) > 0 AND $_COOKIE['dle_password']) {
        $username = intval($_COOKIE['dle_user_id']);
        $cmd5_password = (string)$_COOKIE['dle_password'];
        $post = false;
        $check_log = true;
        $attempt_login = true;
    }
    if ($_REQUEST['subaction'] == 'dologin') {
        $username = $_POST['username'];
        $cmd5_password = (string)$_POST['password'];
        $post = true;
        $check_log = true;
        $attempt_login = true;
    }
}
if (check_login($username, $cmd5_password, $post, $check_log)) {
    $is_loged_in = true;
    if ($post AND password_needs_rehash($member_id['password'], PASSWORD_DEFAULT)) {
        if (version_compare($config['version_id'], '11.2', '>=')) {
            if (strlen($cmd5_password) > 72) $cmd5_password = substr($md5_password, 0, 72);
            $member_id['password'] = password_hash($cmd5_password, PASSWORD_DEFAULT);
            $new_pass_hash = "password='" . $db->safesql($member_id['password']) . "', ";
        } else $new_pass_hash = "";
    } else $new_pass_hash = "";
    if ($config['twofactor_auth'] AND $member_id['twofactor_auth']) {
        $config['ip_control'] = 2;
        $config['log_hash'] = 1;
    }
    if (!$_SESSION['dle_user_id'] AND $_COOKIE['dle_user_id']) {
        session_regenerate_id();
        $_SESSION['dle_user_id'] = $_COOKIE['dle_user_id'];
        $_SESSION['dle_password'] = $_COOKIE['dle_password'];
    }
} else {
    if ($_REQUEST['subaction'] == 'dologin') {
        $result = "<span class=\"text-danger\">" . $lang['index_errpass'] . "</span>";
    } else $result = "";
    if ($allow_extra_login) auth();
    $is_loged_in = false;
}
if ($is_loged_in AND !$_SESSION['dle_xtra'] AND $allow_extra_login) {
    $_SESSION['dle_xtra'] = true;
    $_REQUEST['subaction'] = 'dologin';
    if ($config['twofactor_auth'] AND $member_id['twofactor_auth']) {
        $_SESSION['dle_user_id'] = 0;
        $_SESSION['dle_password'] = "";
        set_cookie("dle_user_id", "", 0);
        set_cookie("dle_password", "", 0);
    }
}
###########################
if ($is_loged_in AND $_REQUEST['subaction'] == 'dologin') {
    session_regenerate_id();
    if (!$config['twofactor_auth'] OR !$member_id['twofactor_auth']) {
        $_SESSION['dle_user_id'] = $member_id['user_id'];
        $_SESSION['dle_password'] = md5($member_id['password']);
        if (intval($_POST['login_not_save'])) {
            set_cookie("dle_user_id", "", 0);
            set_cookie("dle_password", "", 0);
        } else {
            set_cookie("dle_user_id", $member_id['user_id'], 365);
            set_cookie("dle_password", md5($member_id['password']), 365);
        }
    }
    $time_now = time();
    if ($config['login_log']) $db->query("DELETE FROM " . PREFIX . "_login_log WHERE ip = '{$_IP}'");
    if (function_exists('openssl_random_pseudo_bytes')) {
        $stronghash = md5(openssl_random_pseudo_bytes(15));
    } else $stronghash = md5(uniqid(mt_rand(), TRUE));
    $salt = sha1(str_shuffle("abcdefghjkmnpqrstuvwxyz0123456789") . $stronghash);
    $hash = '';
    for ($i = 0;$i < 9;$i++) {
        $hash.= $salt{mt_rand(0, 39) };
    }
    $hash = md5($hash);
    $member_id['hash'] = $hash;
    if ($config['log_hash']) {
        set_cookie("dle_hash", $hash, 365);
        $_COOKIE['dle_hash'] = $hash;
    }
    $db->query("UPDATE " . USERPREFIX . "_users SET {$new_pass_hash}lastdate='{$time_now}', hash='{$hash}',  logged_ip='{$_IP}' WHERE user_id='{$member_id['user_id']}'");
    if ($config['twofactor_auth'] AND $member_id['twofactor_auth']) {
        $is_loged_in = false;
        $attempt_login = false;
        $_SESSION['twofactor_auth'] = md5($member_id['password']);
        $_SESSION['twofactor_id'] = $member_id['user_id'];
        if (isset($_POST['login_not_save']) AND intval($_POST['login_not_save'])) {
            $_SESSION['no_save_cookie'] = 1;
        }
        include_once ENGINE_DIR . '/classes/mail.class.php';
        $pin = generate_pin();
        $db->query("DELETE FROM " . USERPREFIX . "_twofactor WHERE user_id='{$member_id['user_id']}'");
        $db->query("INSERT INTO " . USERPREFIX . "_twofactor (user_id, pin, date) values ('{$member_id['user_id']}', '{$pin}', '{$_TIME}')");
        $row = $db->super_query("SELECT * FROM " . PREFIX . "_email WHERE name='twofactor' LIMIT 0,1");
        $mail = new dle_mail($config, $row['use_html']);
        $row['template'] = stripslashes($row['template']);
        $row['template'] = str_replace("{%username%}", $member_id['name'], $row['template']);
        $row['template'] = str_replace("{%pin%}", $pin, $row['template']);
        $row['template'] = str_replace("{%ip%}", $_IP, $row['template']);
        $mail->send($member_id['email'], $lang['twofactor_subj'], $row['template']);
        unset($pin);
        unset($row);
        unset($mail);
        $member_id = array();
    }
}
if ($is_loged_in AND $config['log_hash'] AND (($_COOKIE['dle_hash'] != $member_id['hash']) OR ($member_id['hash'] == ""))) {
    $is_loged_in = false;
}
if ($is_loged_in AND $config['ip_control'] == '1' AND !check_netz($member_id['logged_ip'], $_IP) AND $_REQUEST['subaction'] != 'dologin') $is_loged_in = false;
if (!$is_loged_in AND $attempt_login) {
    $member_id = array();
    set_cookie("dle_user_id", "", 0);
    set_cookie("dle_password", "", 0);
    set_cookie("dle_hash", "", 0);
    set_cookie("dle_compl", "", 0);
    $_SESSION['dle_user_id'] = 0;
    $_SESSION['dle_password'] = "";
    $_SESSION['check_log'] = 0;
    if ($allow_extra_login) auth();
}
if ($is_loged_in) {
    define('LOGGED_IN', $is_loged_in);
    $dle_login_hash = sha1(SECURE_AUTH_KEY . $member_id['user_id'] . sha1($member_id['password']) . $member_id['hash']);
} else {
    $dle_login_hash = "";
}

if ($member_id['user_group'] == 1 AND $lic_tr) {
    $activation_field = <<<HTML
<script language="javascript" type="text/javascript">
<!--
function dle_activation ( code ){

	document.getElementById( 'result_info' ).innerHTML = '{$lang['nl_sinfo']}';

	if (code == 'key') {

		var dle_key = document.getElementById('sitekey').value ;
		var varsString = "dle_key=" + dle_key;

	} else {

		var site_code = document.getElementById('sitecode').value;
		var varsString = "site_code=" + site_code;
	}
	$.post('?' + varsString, { activation: "yes" }, function(data){
	
		$('#dle-activation').html(data);
	
	});

	return false;
}
//-->
</script>
HTML;
    if (!is_writable(ENGINE_DIR . '/data/config.php')) {
        $lang['stat_system'] = str_replace("{file}", "engine/data/config.php", $lang['stat_system']);
        $fail = "<div class=\"alert alert-warning alert-styled-left alert-arrow-left alert-component alert alert-info alert-styled-left alert-arrow-left alert-component text-size-small text-size-small\">{$lang['stat_system']}</div>";
    } else $fail = "";
    $activation_field.= "<div id=\"dle-activation\" class=\"alert alert-info alert-styled-left alert-arrow-left alert-component text-left text-size-small\">{$lang['trial_info']}<br /><br /><input type=\"text\" name=\"sitekey\" id=\"sitekey\" placeholder=\"{$lang['trial_key']}\" class=\"classic width-400 mr-10\"><button onclick=\"dle_activation( 'key' ); return false;\" class=\"btn bg-teal btn-raised btn-sm\">{$lang['trial_act']}</button><div id=\"result_info\"><br />{$lang['key_format']} <b>XXXXX-XXXXX-XXXXX-XXXXX-XXXXX</b></div></div>
	{$fail}";
} else $activation_field = "";
$config['ip_control'] = $login_params['ip_control'];
$config['log_hash'] = $login_params['log_hash'];
?>