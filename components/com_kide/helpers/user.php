<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class kideUser {
	//ban user if post 4 messages in 5 seconds or less
	//TODO: move to kide admin config
	const BAN_TOTAL = 4;
	const BAN_TIME = 5;
	
	var $color;
	var $sesion;
	
	/*
		0: systema
		1: administrador
		2: registrado
		3: invitado
		4: baneado
	*/
	var $rango;
	var $id;
	var $captcha=1;
	var $gmt;
	var $retardo;
	var $name = "";
	var $sound;
	var $icons_hidden;
	var $token;
	var $img;
	var $ocultar_sesion;
	var $template;
	var $key;
	var $bantime;
	var $can_read;
	var $can_write;
	
	function __construct() {
		self::saveNewOptions();
		
		$session = JFactory::getSession();
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$params = JComponentHelper::getParams('com_kide');
		$this->sesion = $session->get('sesion', 0, 'kide');
		$this->key = $session->get('key', 0, 'kide');
		$oldid = $session->get('userid', 0, 'kide');
		
		if (!$this->sesion || !$this->key || ($user->id != $oldid)) {
			if ($this->sesion) {
				$or = '';
				if ($oldid > 0) $or = ' OR userid='.$oldid;
				if ($user->id > 0) $or = ' OR userid='.$user->id;
				$db->setQuery('DELETE FROM #__kide_sesion WHERE sesion="'.$this->sesion.'"'.$or);
				$db->query();
			}
			$this->sesion = md5(mt_rand());
			$session->set('sesion', $this->sesion, 'kide');
			$this->key = rand(1000000,9999999);
			$session->set('key', $this->key, 'kide');
		}
		$this->id = $user->id;
		$session->set('userid', $this->id, 'kide');

		if (!$this->id)
			$this->rango = 3;
		elseif (KideHelper::isAdmin())
			$this->rango = 1;
		else
			$this->rango = 2;
		if ($this->rango != 1) {
			$db->setQuery("SELECT * FROM #__kide_bans WHERE (sesion='".$this->sesion."' OR ip='".$_SERVER['REMOTE_ADDR']."') AND time > ".time());
			$ban = $db->loadObject();
			if ($ban) {
				if ($ban->ip != $_SERVER['REMOTE_ADDR']) {
					$db->setQuery("UPDATE #__kide_bans SET ip='".$_SERVER['REMOTE_ADDR']."' WHERE id=".$ban->id);
					$db->query();
				}
				$time = (int)$ban->time;
				if ($time > 0) {
					$this->rango = 4;
					$this->bantime = $time;
				}
			}
		}
		if ($params->get('recaptcha') && $params->get('recaptcha_public') && $params->get('recaptcha_private') && $this->rango >= 3) {
			$session = JFactory::getSession();
			$this->captcha = $session->get('kide_captcha', 0);
		}
		if ($user->id) {
			$this->name = $params->get("username", true) ? $user->username : $user->name;
		}
		else {
			if ($session->get("name", '', 'kide')) {
				$this->name = $session->get("name", '', 'kide');
			}
			else {
				$this->name = JText::_("COM_KIDE_INVITADO")."_".rand(1000,9999);
				$session->set("name", $this->name, 'kide');
			}
		}
		$this->name = substr($this->name, 0, 20); //20 max char nick length
		$this->name = addslashes(htmlspecialchars($this->name, ENT_COMPAT));
		$this->icons_hidden =  $session->get("icons_hidden", $params->get("icons_hidden", false), 'kide');
		$this->template =  $session->get("template", $params->get("template", 'default'), 'kide');
		$this->can_read = ($this->rango < 3 || $params->get("guest_can", 2) >= 1) ? 1 : 0;
		$this->can_write = ($this->rango < 3 || ($this->rango == 3 && $params->get("guest_can", 2) >= 2)) ? 1 : 0;
		$this->sound = $params->get("sound", 1) ?  $session->get("sound", 0, 'kide') : -1;
		$this->color =  $session->get("color", "", 'kide');
		$this->token = JRequest::getInt('token', rand(), "POST");
		$this->gmt =  $session->get("gmt", 0, 'kide');
		$this->retardo = $session->get("retardo", 0, 'kide');
		$this->ocultar_sesion = $session->get("ocultar_sesion", 0, 'kide');
		$this->img = kideLinks::getAvatar();
	}
	
	function checkBan() {
		if ($this->rango <= 1) return false;
		$banned = false;
		$limit = self::BAN_TOTAL + 2;
		$session = JFactory::getSession();
		$ban = $session->get('kide_ban', array());
		if (count($ban) != self::BAN_TOTAL+3 || $ban[self::BAN_TOTAL+1] != self::BAN_TOTAL || $ban[self::BAN_TOTAL+2] != self::BAN_TIME) {
			$ban = array();
		}
		if (!count($ban)) {
			for ($i=0; $i<=self::BAN_TOTAL; $i++)
				$ban[$i] = $i == 0 ? 1 : 0;
			$ban[self::BAN_TOTAL+1] = self::BAN_TOTAL;
			$ban[self::BAN_TOTAL+2] = self::BAN_TIME;
		}
		
		if ($ban[0] > self::BAN_TOTAL) {
			for ($i=1; $i<self::BAN_TOTAL; $i++)
				$ban[$i] = $ban[$i+1];
			$ban[self::BAN_TOTAL] = time();
			$aux = $ban[self::BAN_TOTAL] - $ban[1];
			if ($aux < self::BAN_TIME) {
				$this->banear();
				$banned = true;
			}
		}
		else {
			$ban[$ban[0]] = time();
			$ban[0]++;
		}
		$session->set('kide_ban', $ban);
		return $banned;
	}
	
	function banear() {
		$this->rango = 4;
		$params = JComponentHelper::getParams('com_kide');
		$db = JFactory::getDBO();
		$tiempo = time() + $params->get("banear_minutos", 5)*60;
		$db->setQuery("INSERT INTO #__kide_bans (ip, sesion, time) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$this->sesion."', ".$tiempo.")");
		$db->query();
	}

	function &getInstance(){
		static $instance;
		if (!is_object($instance))
			$instance = new kideUser();
		return $instance;
	}
	
	function saveNewOptions() {
		$config = self::getCookieConfigArray();
		if (!headers_sent()) setcookie('kide_config', '', 0, '/');
		$session = JFactory::getSession();
		foreach ($config as $k=>$v) {
			$session->set($k, $v, 'kide');
		}
	}
	
	function getCookieConfigArray() {
		$config = isset($_COOKIE['kide_config']) ? $_COOKIE['kide_config'] : '';
		$aux = array();
		if (strlen($config)) {
			$opciones = explode(";", $config);
			foreach ($opciones as $opcion) {
				$opcion = explode("=", $opcion);
				if ($opcion[0])
					$aux[$opcion[0]] = isset($opcion[1])?$opcion[1]:'';
			}
		}
		return $aux;
	}
}
