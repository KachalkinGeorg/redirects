<?php
/*
=====================================================
 Добавление для новости
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

if(!defined('NGCMS'))
exit('HAL');

pluginsLoadConfig();
LoadPluginLang('redirects', 'config', '', '', '#');

switch ($_REQUEST['action']) {
	case 'list_redirects':		list_redirects();	break;
	case 'add_redirects':		add_redirects();	break;
	case 'edit_redirects':		edit_redirects();	break;
	case 'about':			about();		break;
	case 'delete':		delete();		 	break;
	default: main();
}

function about(){
	global $twig, $lang, $breadcrumb;
	$tpath = locatePluginTemplates(array('main', 'about'), 'redirects', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-retweet btn-position"></i><span class="text-semibold">301 Ридирект</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=redirects' => '<i class="fa fa-retweet btn-position"></i>301 Ридирект',  'О плагине' ) );

	$xt = $twig->loadTemplate($tpath['about'].'about.tpl');

	$tVars = array();
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	$tVars = array(
		'global' 	=> 'О плагине',
		'panel' 	=> 'О плагине',
		'active3' 	=> 'active',
		'entries' 	=> $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function list_redirects(){
	global $twig, $mysql, $lang, $breadcrumb;
	$tpath = locatePluginTemplates(array('main', 'list_redirects', 'list_redirects_entries'), 'redirects', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-retweet btn-position"></i><span class="text-semibold">301 Ридирект</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=redirects' => '<i class="fa fa-retweet btn-position"></i>301 Ридирект',  'Список редиректов с одной ссылки на другую' ) );
	
	$news_per_page = pluginGetVariable('redirects', 'num_news');
	
	if (($news_per_page < 2)||($news_per_page > 2000)) $news_per_page = 2;
	
	$pageNo = intval($_REQUEST['page'])?$_REQUEST['page']:0;
	if ($pageNo < 1) $pageNo = 1;
	if (!$start_from) $start_from = ($pageNo - 1)* $news_per_page;
	
	$count = $mysql->result('SELECT count(*) as count FROM '.prefix.'_redirects');
	$countPages = ceil($count / $news_per_page);

	foreach ($mysql->select('SELECT * FROM '.prefix.'_redirects ORDER BY id DESC LIMIT '.$start_from.', '.$news_per_page) as $row){
		$xe = $twig->loadTemplate($tpath['list_redirects_entries'].'list_redirects_entries.tpl');

		if ($row['active'] == 1) {
			$active = '<span class="text-success" title="Активен"><i class="fa fa-check-circle"></i></span>';
		} else {
			$active = '<span class="text-warning" title="Не активен"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>';
		}
			
		$tVars = array (
			'id' 		=> $row['id'],
			'old_url' 	=> $row['old_url'],
			'new_url' 	=> $row['new_url'],
			'active' 	=> $active,
			'modal' 	=> print_modal_dialog($row['id'], 'Удалить '.$row['name'].'', 'Вы уверены, что хотите удалить редирект?<br /><br /> <center>с <b>'.$row['old_url'].'</b> на <b>'.$row['new_url'].'</b></center>', '<a href="?mod=extra-config&plugin=redirects&action=delete&id='.$row['id'].'" class="btn btn-outline-success">да</a>'),
			'action' 	=> '<div class="btn-group btn-group-sm" role="group"><a href="?mod=extra-config&plugin=redirects&action=edit_redirects&id='.$row['id'].'" class="btn btn-outline-primary" /><i title="Редактировать" class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a href="#" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-'.$row['id'].'"  /><i title="удалить" style="color:#ed143d" class="fa fa-trash" aria-hidden="true"></i></a></div>',
		);

		$entries .= $xe->render($tVars);
	}
	
	$xt = $twig->loadTemplate($tpath['list_redirects'].'list_redirects.tpl');
	
	$tVars = array(
		'pagesss' => generateAdminPagelist( array(	'current' => $pageNo,
													'count' => $countPages,
													'url' => admin_url.'/admin.php?mod=extra-config&plugin=redirects&action=list_redirects&page=%page%'
													)
		),
		'entries' => $entries,
	);
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' 	=> 'Редиректы',
		'panel' 	=> 'Список',
		'active2' 	=> 'active',
		'entries' 	=> $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function edit_redirects(){
	global $twig, $mysql, $config, $parse, $lang, $breadcrumb;

	$breadcrumb = breadcrumb('<i class="fa fa-retweet btn-position"></i><span class="text-semibold">301 Ридирект</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=redirects' => '<i class="fa fa-retweet btn-position"></i>301 Ридирект',  ''.$addedit.'' ) );

	$tpath = locatePluginTemplates(array('main', 'edit_redirects'), 'redirects', 1);
	
	$id = intval($_REQUEST['id']);

	if ($id <= 0) {
		msg(array("type" => "error", "text" => "Неверный идентификатор редиректа", "info" => "Ошибка! Указан неверный идентификатор редиректа для редактирования!"));
		return print_msg( 'error', '301 Ридирект', 'Ошибка! Указан неверный идентификатор редиректа для редактирования!', 'javascript:history.go(-1)' );
	} else {
		$row = $mysql->record('SELECT SQL_CALC_FOUND_ROWS * FROM ' . prefix . '_redirects WHERE id = '.$id.' LIMIT 1');
		if ($row > 0) {
			if (isset($_REQUEST['submit'])){
				$old_url = $_REQUEST['old_url'];
				$new_url = $_REQUEST['new_url'];
				
				if (!$old_url OR !$new_url ) {
					msg(array("type" => "error", "info" => "Не заданы ссылки для редиректов!"));
					return print_msg( 'error', '301 Ридирект', 'Не заданы ссылки для редиректов!', 'javascript:history.go(-1)' );
				}
				
				if ($old_url == $new_url) {
					msg(array("type" => "error", "text" => "Ошибка редиректа", "info" => "Новый адрес не может быть равен старому адресу!"));
					return print_msg( 'error', '301 Ридирект', 'Новый адрес не может быть равен старому адресу!', 'javascript:history.go(-1)' );
				}
				
				$old = $mysql->record('SELECT * FROM ' . prefix . '_redirects WHERE id != '.$id.' and old_url = '.db_squote($row['old_url']).' LIMIT 1');
				if ($old['old_url']) {
					msg(array("type" => "error", "text" => "Ошибка редиректа", "info" => "Редирект с указанным адресом уже существует!"));
					return print_msg( 'error', '301 Ридирект', 'Редирект с указанным адресом уже существует!', 'javascript:history.go(-1)' );
				}
				
/* 				$new = $mysql->record('SELECT * FROM ' . prefix . '_redirects WHERE id != '.$id.' and new_url = '.db_squote($row['new_url']).'');
				if ($new['new_url']) {
					msg(array("type" => "error", "text" => "Ошибка редиректа", "info" => "Новый адрес редиректа конфликтует с уже существующим редиректом!"));
					return print_msg( 'error', '301 Ридирект', 'Новый адрес редиректа конфликтует с уже существующим редиректом!', 'javascript:history.go(-1)' );
				}
				 */
				$active = (isset($_REQUEST['active'])) ? 1 : 0;
				$mysql->query('UPDATE '.prefix.'_redirects SET old_url = '.db_squote($old_url).', new_url = '.db_squote($new_url).', active = '.db_squote($active).' WHERE id = \''.intval($_REQUEST['id']).'\' ');
				
				$link = home.$_REQUEST['old_url'];

				msg(array("type" => "info", "info" => "Редирект успешно изменен!"));
				return print_msg( 'update', '301 Ридирект', 'Редирект успешно изменен!', array('?mod=extra-config&plugin=redirects&action=add_redirects' => 'Добавить еще', '?mod=extra-config&plugin=redirects&action=edit_redirects&id='.$id => 'Отредактировать', $link => 'Посмотреть на сайте' ) );
			}
			
			if ($row['active'] == 1) $active = 'checked';
			
			$tVars = array(
				'old_url' => $row['old_url'],
				'new_url' => $row['new_url'],
				'active' => $active,
			);
		
		} else {
			msg(array("type" => "error", "text" => "Редирект не существует", "info" => "Ошибка! Указанного редиректа не существует!"));
			return print_msg( 'error', '301 Ридирект', 'Ошибка! Указанного редиректа не существует!', 'javascript:history.go(-1)' );
		}
	}
	
	$template = $tpath['edit_redirects'].'edit_redirects.tpl';

	$xt = $twig->loadTemplate($template);
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' => 'Редактировать редирект',
		'panel' => 'Ввод данных',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function add_redirects(){
	global $twig, $mysql, $config, $parse, $lang, $breadcrumb;
	
	$breadcrumb = breadcrumb('<i class="fa fa-retweet btn-position"></i><span class="text-semibold">301 Ридирект</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=redirects' => '<i class="fa fa-retweet btn-position"></i>301 Ридирект',  ''.$addedit.'' ) );

	$tpath = locatePluginTemplates(array('main', 'add_redirects'), 'redirects', 1);

	if (isset($_REQUEST['submit'])){
		$old_url = $_REQUEST['old_url'];
		$new_url = $_REQUEST['new_url'];

		if ($old_url == $new_url) {
			msg(array("type" => "error", "text" => "Ошибка добавления редиректа", "info" => "Новый адрес не может быть равен старому адресу!"));
			return print_msg( 'error', '301 Ридирект', 'Новый адрес не может быть равен старому адресу!', 'javascript:history.go(-1)' );
		}
		
		$old = $mysql->record('SELECT * FROM ' . prefix . '_redirects WHERE old_url = '.db_squote($_REQUEST['old_url']).'');
		if ($old['old_url']) {
			msg(array("type" => "error", "text" => "Ошибка добавления редиректа", "info" => "Редирект с указанным адресом уже существует!"));
			return print_msg( 'error', '301 Ридирект', 'Редирект с указанным адресом уже существует!', 'javascript:history.go(-1)' );
		}
		
/* 		$new = $mysql->select('SELECT * FROM ' . prefix . '_redirects WHERE old_url = '.db_squote($_REQUEST['new_url']).' LIMIT 1');
		if ($new > 0) {
			msg(array("type" => "error", "text" => "Ошибка добавления редиректа", "info" => "Новый адрес редиректа конфликтует с уже существующим редиректом!"));
			return print_msg( 'error', '301 Ридирект', 'Новый адрес редиректа конфликтует с уже существующим редиректом!', 'javascript:history.go(-1)' );
		} */
		
		$active = (isset($_REQUEST['active'])) ? 1 : 0;
		$mysql->query('INSERT INTO ' . prefix . '_redirects (`old_url`, `new_url`, `active`) VALUES ('.db_squote($_REQUEST['old_url']).', '.db_squote($_REQUEST['new_url']).', '.$active.' )');

		$id = $mysql->lastid('redirects')+1;
		$link = home.$_REQUEST['old_url'];
		
		msg(array("type" => "info", "info" => "Редирект успешно добавлен!"));
		return print_msg( 'success', '301 Ридирект', 'Редирект успешно добавлен!', array('?mod=extra-config&plugin=redirects&action=add_redirects' => 'Добавить еще', '?mod=extra-config&plugin=redirects&action=edit_redirects&id='.$id => 'Отредактировать', $link => 'Посмотреть на сайте' ) );
	}
	
	$template = $tpath['add_redirects'].'add_redirects.tpl';
	
	$tVars = array(
	);
	
	$xt = $twig->loadTemplate($template);
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' => 'Добавить редирект',
		'panel' => 'Ввод данных',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);

}

//Главная страница
function main(){
	global $twig, $lang, $breadcrumb;
	
	$tpath = locatePluginTemplates(array('main', 'general.from'), 'redirects', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-retweet btn-position"></i><span class="text-semibold">301 Ридирект</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=redirects' => '<i class="fa fa-retweet btn-position"></i>301 Ридирект' ) );
	
	if (isset($_REQUEST['submit'])){
		pluginSetVariable('redirects', 'num_news', secure_html(trim($_REQUEST['num_news'])));
		pluginsSaveConfig();
		msg(array("type" => "info", "info" => "Настройки успешно сохранены!"));
		return print_msg( 'info', '301 Ридирект', 'Настройки успешно сохранены!', 'javascript:history.go(-1)' );
	}
	
	$num_news = pluginGetVariable('redirects', 'num_news');
	
	$xt = $twig->loadTemplate($tpath['general.from'].'general.from.tpl');
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'num_news' => array(
						'print' => $num_news,
						'error' => empty($num_news)?'<img src="'.skins_url.'/images/error.gif" hspace="5" alt="" />Поле не заполнено!<br /><b>Рекомендовано:</b> 20':''
		),

	);
	
	$tVars = array(
		'global' => 'Общие',
		'panel' => 'Главная',
		'active1' 	=> 'active',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function delete() {
	global $mysql, $lang;
	$id = intval($_REQUEST['id']);
	
	if( empty($id) )
		return msg(array("type" => "error", "text" => "Ошибка! Указанного редиректа не существует"));
	$mysql->query('delete from ' . prefix . '_redirects where `id`=' . db_squote($id));
	msg(array('type' => 'info', 'info' => sprintf("Запись была успешна удалена", $id)));
	return print_msg( 'delete', '301 Ридирект', 'Выбранный 301 Ридирект был успешно удален!', 'javascript:history.go(-1)' );
}