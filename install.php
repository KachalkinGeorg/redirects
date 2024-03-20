<?php
if (!defined('NGCMS'))
{
	die ('HAL');
}

function plugin_redirects_install($action) {
	global $lang;
	
	if ($action != 'autoapply')
		loadPluginLang('redirects', 'config', '', '', ':');
	
	$db_update = array(
		array(
			'table' => 'redirects',
			'action' => 'cmodify',
			'key' => 'primary key (`id`)',
			'fields' => array(
				array('action' => 'cmodify', 'name' => 'id', 'type' => 'int(11)', 'params' => 'unsigned not null auto_increment'),
				array('action' => 'cmodify', 'name' => 'old_url', 'type' => 'varchar(255)', 'params' => 'not null'),
				array('action' => 'cmodify', 'name' => 'new_url', 'type' => 'tinytext', 'params' => 'not null'),
				array('action' => 'cmodify', 'name' => 'active', 'type' => 'tinyint(1)', 'params' => 'not null default 1'),
			)
		)
	);
	
	switch ($action) {
		case 'confirm':
			generate_install_page('redirects', $lang['redirects:desc_install']);
			break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('redirects', $db_update, 'install', ($action == 'autoapply') ? true : false)) {
				plugin_mark_installed('redirects');
			} else {
				return false;
			}
			break;
	}

	return true;
}