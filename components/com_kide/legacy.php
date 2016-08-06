<?php

/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined('_JEXEC') or die;

$v = substr(JVERSION, 0, 3);
$v = intval($v[0].$v[2]);

jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport('joomla.application.component.view');

if ($v >= 30) {
	class KController extends JControllerLegacy {}
	class KModel extends JModelLegacy {}
	class KView extends JViewLegacy {}
}
else {
	class KController extends JController {}
	class KModel extends JModel {}
	class KView extends JView {}
}