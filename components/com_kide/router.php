<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

jimport('joomla.filter.output');

require_once(dirname(__FILE__).'/defines.php');

function KideBuildRoute(&$query) {
	$lang = JFactory::getLanguage();
	$lang->load("com_kide");
	$segments = array();
	if(isset($query['view'])) {
		switch ($query['view']) {
			case 'history':
				if (!isset($query['Itemid']) || $query['Itemid'] != KIDE_ITEMID_HISTORY)
					$segments[] = JFilterOutput::stringURLSafe(JText::_('COM_KIDE_ALIAS_HISTORY'));
				if(isset($query['page'])) {
					$segments[] = JFilterOutput::stringURLSafe(JText::_('COM_KIDE_ALIAS_PAGE')).'-'.$query['page'];
					unset( $query['page'] );
				}
				break;
		}
		unset( $query['view'] );
	}
	if(isset($query['tmpl']) && $query['tmpl'] == "component") {
		$segments[] = "popup";
		unset($query['tmpl']);
	}
	return $segments;
}

function KideParseRoute($segments) {
	$lang = JFactory::getLanguage();
	$lang->load("com_kide");
	$vars = array();
	$pags = isset($segments[1]) ? $segments[1] : (isset($segments[0]) ? $segments[0] : '');
	if (strpos($pags, ':')) {
		$vars['view'] = 'history';
		$pag = explode(':', $pags);
		$pag = (int)$pag[count($pag)-1];
		$vars['page'] = $pag > 0 ? $pag : 1;
	}
	elseif ($segments[0] == 'popup') {
		$vars['tmpl'] = 'component';
	}
	return $vars;
}