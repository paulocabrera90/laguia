<?php
/**
* @Copyright Copyright (C) 2013 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/legacy.php');

DEFINE('KIDE_HTML', JURI::base(true).'/components/com_kide/');
DEFINE('KIDE_MOD_HTML', JURI::base(true).'/modules/mod_kide/');
DEFINE('KIDE_MOD_PHP', JPATH_BASE.'/modules/mod_kide/');
DEFINE('KIDE_AJAX', JURI::base(true).'/index.php?option=com_kide&no_html=1&tmpl=component');
DEFINE('KIDE_PHP', dirname(__file__).'/');
DEFINE('KIDE_TPL_HTML', KIDE_HTML.'templates/');
DEFINE('KIDE_TPL_PHP', KIDE_PHP.'templates/');
DEFINE('KIDE_HELPERS', KIDE_PHP.'helpers/');
DEFINE('KIDE_LIBS', KIDE_PHP.'libs/');

$db = JFactory::getDBO();
$db->setQuery("SELECT id, link FROM #__menu WHERE link LIKE 'index.php?option=com_kide%' AND published='1'");
$objs = $db->loadObjectList();

function kide_buscar_itemid($link, $objs) {
	foreach ($objs as $obj) {
		if ($obj->link == $link) return $obj->id;
	}
	return 0;
}

$itemid = kide_buscar_itemid('index.php?option=com_kide&view=kide', $objs);
if (!$itemid) $itemid = kide_buscar_itemid('index.php?option=com_kide', $objs);
$itemid_history = kide_buscar_itemid('index.php?option=com_kide&view=history', $objs);
if (!$itemid_history) $itemid_history = $itemid;

DEFINE('KIDE_ITEMID', $itemid);
DEFINE('KIDE_ITEMID_HISTORY', $itemid_history);

DEFINE('KIDE_URL', "index.php?option=com_kide".($itemid > 0 ? "&Itemid=".$itemid : ''));
DEFINE('KIDE_URL_HISTORY', "index.php?option=com_kide&view=history".($itemid_history > 0 ? "&Itemid=".$itemid_history : ''));
