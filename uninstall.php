<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

loadPluginLang('redirects', 'config', '', '', ':');

$db_update = array(
	array(
		'table'  => 'redirects',
		'action' => 'drop',
	),
);

if ($_REQUEST['action'] == 'commit') {
	if (fixdb_plugin_install('redirects', $db_update, 'deinstall')) {
		plugin_mark_deinstalled('redirects');
	}
} else {
	$text = $lang['redirects:desc_uninstall'];
	generate_install_page('redirects', $text, 'deinstall');
}