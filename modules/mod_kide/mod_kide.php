<?php

/**
* @Copyright Copyright (C) 2013 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

if (DEFINED("KIDE_LOADED")) return;

$defines = JPATH_BASE."/components/com_kide/defines.php";
if (!file_exists($defines)) {
	echo "You need install com_kide.zip";
	return;
}

require_once ($defines);
require_once (KIDE_HELPERS."head.php");
require_once (KIDE_HELPERS."kide.php");
require_once (KIDE_HELPERS."template.php");
require_once (KIDE_HELPERS."user.php");
require_once (KIDE_HELPERS."links.php");
require_once (KIDE_PHP.'router.php');

$lang = JFactory::getLanguage();
$lang->load("com_kide");

require_once (KIDE_PHP.'views/kide/view.html.php');
$tpl = KideTemplate::getInstance();
$session = JFactory::getSession();
if (!$session->get("template", '', 'kide'))
	$tpl->tuser = $params->get('template');
KideViewKide::preparar();
$tpl->view = 'kide';
$tpl->check_language();

$doc = JFactory::getDocument();
$kuser = kideUser::getInstance();
$privs = $params->get('show_sessions', 0) && $params->get('show_privados', 0) && $kuser->can_write ? 1 : 0;
KideHead::addScript("
kide.show_hour = ".$params->get('show_hour', 0).";
kide.show_sessions = ".$params->get('show_sessions', 0).";
kide.autoiniciar = ".$params->get('autoiniciar', 0).";
kide.show_privados = ".($privs && $params->get('enable_privates', 1) ? 1 : 0).";");
$tpl->assign('com', 'mod');
$tpl->assign('show_hour', $params->get('show_hour', 0));
$tpl->assign('autoiniciar', $params->get('autoiniciar', 0));
$tpl->assign('show_sessions', $params->get('show_sessions', 0));
$tpl->assign('show_privados', $privs);

$tpl->display();