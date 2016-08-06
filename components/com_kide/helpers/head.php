<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class KideHead {
	function add_tags() {
		$kuser = kideUser::getInstance();
		$tpl = KideTemplate::getInstance();
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		$session = JFactory::getSession();
		$order = $params->get('order', 'bottom');
		$doc = JFactory::getDocument();
		$doc->addScript(KIDE_HTML."js/base.js");
		$tpl->include_html("js", "kide");
		
		$db->setQuery("SELECT id FROM #__kide ORDER BY id DESC LIMIT 1");
		$id = $db->loadResult();
		
		$refresh_time_sesion = intval($params->get("refresh_time_sesion", 30));
		if ($refresh_time_sesion < 5) $refresh_time_sesion = 5;
		$refresh_time_sesion *= 1000;

		KideHead::addScript('
	kide.img_encendido = ["'.$tpl->include_html("botones", "encendido_0.gif").'", "'.$tpl->include_html("botones", "encendido_1.gif").'", "'.$tpl->include_html("botones", "encendido_2.gif").'"];
	kide.sound_on = "'.$tpl->include_html("botones", "sound_on.png").'";
	kide.sound_off = "'.$tpl->include_html("botones", "sound_off.png").'";
	kide.sound_src = "'.$tpl->include_html("sound", "msg.swf").'";
	kide.img_blank = "'.$tpl->include_html("otras", "blank.png").'";
	kide.ajax_url = "'.KIDE_AJAX.'";
	kide.url = "'.kideLinks::getUserLink($kuser->id).'";
	kide.popup_url = "'.JRoute::_(KIDE_URL."&view=kide".(JRequest::getCmd('tmpl')=="component"?"":"&tmpl=component")).'";
	kide.order = "'.$order.'";
	kide.formato_hora = "'.$params->get("formato_hora", "G:i--").'";
	kide.formato_fecha = "'.$params->get("formato_fecha", "j-n G:i:s").'";
	
	kide.template = "'.$kuser->template.'";
	kide.gmt = "'.$kuser->gmt.'";
	kide.token = '.$kuser->token.';
	kide.sesion = "'.$kuser->sesion.'";
	kide.rango = '.$kuser->rango.';
	kide.rangos = ["'.implode('","', KideHelper::getRangos()).'"];
	kide.can_read = '.($kuser->can_read?'true':'false').';
	kide.can_write = '.($kuser->can_write?'true':'false').';
	kide.show_avatar = '.($params->get("show_avatar", 0) ? 'true' : 'false').';
	kide.avatar_maxheight = "'.$params->get('avatar_maxheight', '30px').'";
	kide.refresh_time_sesion = '.$refresh_time_sesion.';
	kide.boton_enviar = '.($params->get('button_send', 0)?'true':'false').';
	kide.refresh_time = '.$params->get('refresh_time', 6).'*1000;
	kide.refresh_time_privates = '.$params->get('refresh_time_privates', 6).'*1000;
	
	kide.n = '.(int)$id.';
	kide.name = "'.$kuser->name.'";
	kide.userid = '.$kuser->id.';
	kide.sound = '.$kuser->sound.';
	kide.color = "'.$kuser->color.'";
	kide.retardo = '.(int)$kuser->retardo.';
	kide.last_time = '.KideHelper::getLastTime().';

	kide.msg = {
		espera_por_favor: \''.addslashes(JText::_("COM_KIDE_ESPERA_POR_FAVOR")).'\',
		mensaje_borra: \''.addslashes(JText::_("COM_KIDE_MENSAJE_BORRAR")).'\',
		retardo_frase: \''.addslashes(JText::_("COM_KIDE_RETARDO_FRASE")).'\',
		lang: [\''.addslashes(JText::_("COM_KIDE_MONTH")).'\', \''.addslashes(JText::_("COM_KIDE_MONTHS")).'\', \''.addslashes(JText::_("COM_KIDE_DAY")).'\', \''.addslashes(JText::_("COM_KIDE_DAYS")).'\', \''.addslashes(JText::_("COM_KIDE_HOUR")).'\', \''.addslashes(JText::_("COM_KIDE_HOURS")).'\', \''.addslashes(JText::_("COM_KIDE_MINUTE")).'\', \''.addslashes(JText::_("COM_KIDE_MINUTES")).'\', \''.addslashes(JText::_("COM_KIDE_SECOND")).'\', \''.addslashes(JText::_("COM_KIDE_SECONDS")).'\'],
		privados_usuario_cerrado: \''.addslashes(JText::_("COM_KIDE_PRIVADOS_USUARIO_CERRADO")).'\',
		privados_nuevos: \''.addslashes(str_replace("%url", JRoute::_(KIDE_URL."&view=kide"), JText::_("COM_KIDE_PRIVADOS_NUEVOS"))).'\',
		privados_need_login: \''.addslashes(JText::_('COM_KIDE_PRIVADOS_NEED_LOGIN')).'\'
	};
	kide.smilies = [
		'.kideHelper::smilies_js().'
	];
	');
	  
		$doc->addStyleDeclaration('
	'.($kuser->color?'#KIDE_txt { color: #'.$kuser->color.'; }':'').'
	#KIDE_usuarios_td { vertical-align: '.$order.' }');
			
		if ($session->get('gmt', null, 'kide') === null)
			KideHead::addScript('
	var tiempo = new Date();
	kide.save_config("gmt", (tiempo.getTimezoneOffset()/60)*-1);');
			
		if($session->get('retardo', null, 'kide') === null)
			KideHead::addScript('kide.ajax("retardo");');
	}
	
	function addScript($str) {
		$doc = JFactory::getDocument();
		$doc->addCustomTag("<script type=\"text/javascript\">\n/*<![CDATA[*/\n".$str."\n/*]]>*/\n</script>");
	}
}
