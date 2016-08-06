<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.view');
jimport('joomla.filesystem.folder' );

class KideViewKide extends KView {
	function display($tmpl = null) {		
		$kuser = kideUser::getInstance();
		$params = JComponentHelper::getParams('com_kide');
		KideHead::addScript("
		kide.show_hour = 1;
		kide.show_sessions = 1;
		kide.show_privados = ".($kuser->can_write && $params->get('enable_privates', 1) ? 1 : 0).";
		kide.autoiniciar = 1");
		$this->preparar();
		$tpl = KideTemplate::getInstance();
		$tpl->display();
	}
	function preparar() {
		DEFINE('KIDE_LOADED', true);
		kideHead::add_tags();
		
		$db = JFactory::getDBO();
		$kuser = kideUser::getInstance();
		$params = JComponentHelper::getParams('com_kide');
		$tpl = KideTemplate::getInstance();
		$tpl->include_html("css", "kide");
		
		if (!$kuser->captcha) {
			if (!function_exists('_recaptcha_qsencode'))
				require_once(KIDE_LIBS."recaptchalib.php");
			$tpl->assign('recaptcha_public', $params->get('recaptcha_public'));
		}
		$max_strlen = $params->get('msgs_max_strlen', 3000);
		$order = $params->get('order', 'bottom');
		$fecha = $params->get("formato_fecha", "j-n G:i:s");
		$formato_hora = $params->get("formato_hora", "G:i--");
		$copy = kideHelper::getCopy();
		
		$db->setQuery("SELECT * FROM #__kide ORDER BY id DESC LIMIT ".$params->get("msgs_limit", 36));
		$msgs = $db->loadObjectList();
		if ($order == 'bottom') krsort($msgs);
		
		$folders = JFolder::folders(KIDE_TPL_PHP);
		$s = array();
		foreach ($folders as $f) $s[] = (object)array('text'=>$f);
		$templates = JHTML::_('select.genericlist',  $s, 'KIDE_template', 'class="inputbox"', 'text', 'text', $kuser->template);
		
		$tpl->assign('com', 'com');
		$tpl->assign('show_hour', 1);
		$tpl->assign('show_sessions', 1);
		$tpl->assign('show_privados', 0);
		$tpl->assign('autoiniciar', 1);
		$tpl->assign('button_send', $params->get('button_send', 0));
		$tpl->assign('show_avatar', $params->get("show_avatar", 0));
		$tpl->assign('avatar_maxheight', $params->get("avatar_maxheight", '30px'));
		$tpl->assign('maxlength', $max_strlen > 0 ? 'maxlength="'.$max_strlen.'"' : '');
		$tpl->assign('popup', JRequest::getVar('tmpl') == 'component');
		$tpl->assignRef('order', $order);
		$tpl->assignRef('copy', $copy);
		$tpl->assignRef('msgs', $msgs);
		$tpl->assignRef('user', $kuser);
		$tpl->assignRef('fecha', $fecha);
		$tpl->assignRef('formato_hora', $formato_hora);
		$tpl->assignRef('templates', $templates);
	}
}
