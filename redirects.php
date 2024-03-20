<?
/*
=====================================================
 301 Ридирект
-----------------------------------------------------
 Author: KachalkinGeorg
-----------------------------------------------------
 E-mail: KachalkinGeorg@yandex.ru
=====================================================
 © GK
-----------------------------------------------------
 Данный код защищен авторскими правами
=====================================================
*/

if (!defined('NGCMS')) die ('HAL');

add_act ( 'index_post', 'redirects' );

function redirects(){
	global $mysql, $config, $lang;

if (isset($_SERVER['REDIRECT_URL']) || isset($_SERVER['REQUEST_URI'])) {
	if (isset($_SERVER['REDIRECT_URL'])) {
		$old_url = $_SERVER['REDIRECT_URL'];
		if (strpos($_SERVER['REDIRECT_URL'], '?') === FALSE && $_SERVER['QUERY_STRING'] != '') {
			$old_url .= '?'.$_SERVER['QUERY_STRING'];
		}
		$old_url = $mysql->db_quote($old_url);
	} else {
		$old_url = $mysql->db_quote($_SERVER['REQUEST_URI']);
	}
	$rows = $mysql->record("SELECT * FROM " . prefix . "_redirects WHERE old_url = '{$old_url}' and new_url != '{$old_url}' and active = 1 LIMIT 1");
	if ($rows > 0) {
		header("Location: {$rows['new_url']}",TRUE,301);
		die();
	}
}

}
