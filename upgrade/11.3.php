<?php

$config['version_id'] = "12.0";
$config['allow_own_meta'] = '0';

$tableSchema = array();

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_post_pass";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_post_pass (
  `id` INT(11) NOT NULL auto_increment,
  `news_id` INT(11) NOT NULL default '0',
  `password` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`)
) ENGINE=" . $storage_engine . " DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci";

$tableSchema[] = "DROP TABLE IF EXISTS " . PREFIX . "_metatags";
$tableSchema[] = "CREATE TABLE " . PREFIX . "_metatags (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(250) NOT NULL default '',
  `title` varchar(200) NOT NULL default '',
  `description` varchar(200) NOT NULL default '',
  `keywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=" . $storage_engine . " DEFAULT CHARACTER SET " . COLLATE . " COLLATE " . COLLATE . "_general_ci";

$tableSchema[] = "ALTER TABLE `" . PREFIX . "_post_log` ADD `move_cat` VARCHAR(190) NOT NULL DEFAULT ''";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_usergroups` ADD `video_comments` TINYINT(1) NOT NULL DEFAULT '0' , ADD `media_comments` TINYINT(1) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_category` ADD `fulldescr` TEXT NOT NULL , ADD `disable_search` TINYINT(1) NOT NULL DEFAULT '0'";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_static` ADD `disable_search` TINYINT(1) NOT NULL DEFAULT '0' , ADD `password` TEXT NOT NULL , ADD INDEX `disable_search` (`disable_search`)";
$tableSchema[] = "ALTER TABLE `" . PREFIX . "_post_extras` ADD `disable_search` TINYINT(1) NOT NULL DEFAULT '0', ADD `need_pass` TINYINT(1) NOT NULL DEFAULT '0', ADD INDEX(`disable_search`)";
$tableSchema[] = "UPDATE " . PREFIX . "_usergroups SET `video_comments` = '1', `media_comments` = '1' WHERE id < '3'";


foreach($tableSchema as $table) {
	$db->query ($table);
}


$handler = fopen(ENGINE_DIR.'/data/config.php', "w") OR die("Извините, но невозможно записать информацию в файл <b>.engine/data/config.php</b>.<br />Проверьте правильность проставленного CHMOD!");
fwrite($handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n");
foreach($config as $name => $value)
{
	fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
}
fwrite($handler, ");\n\n?>");
fclose($handler);

	
$fdir = opendir( ENGINE_DIR . '/cache/system/' );
while ( $file = readdir( $fdir ) ) {
	if( $file != '.' and $file != '..' and $file != '.htaccess' ) {
		@unlink( ENGINE_DIR . '/cache/system/' . $file );
		
	}
}

@unlink(ENGINE_DIR.'/data/snap.db');

listdir( ENGINE_DIR . '/cache/system/CSS' );
listdir( ENGINE_DIR . '/cache/system/HTML' );
listdir( ENGINE_DIR . '/cache/system/URI' );

clear_cache();

if ($db->error_count) {

	$error_info = "Всего запланировано запросов: <b>".$db->query_num."</b> Неудалось выполнить запросов: <b>".$db->error_count."</b>. Возможно они уже выполнены ранее.<br /><br /><div class=\"quote\"><b>Список не выполненных запросов:</b><br /><br />"; 

	foreach ($db->query_list as $value) {

		$error_info .= $value['query']."<br /><br />";

	}

	$error_info .= "</div>";

} else $error_info = "";

msgbox("info","Информация", "Обновление базы данных с версии <b>11.3</b> до версии <b>12.0</b> успешно завершено.<br /><br />{$error_info}<br />Нажмите далее для продолжения процессa обновления скрипта.");

?>