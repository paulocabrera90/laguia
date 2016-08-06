<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

DEFINE('KIDE_VERSION', 21);

class KideUpdater {
	function updateBefore($v) {
		$db = JFactory::getDBO();
		if ($v == 0) {
			$db->setQuery("INSERT INTO #__kide_info (name, value) VALUES ('version', ".KIDE_VERSION.")");
			$db->query();
		}
		else {
			if ($v <= 10) {
				$db->setQuery("DROP TABLE IF EXISTS #__kide_iconos");
				$db->query();
			}
			if ($v <= 13) {
				$db->setQuery("DROP TABLE IF EXISTS #__kide_bans");
				$db->query();
			}
			if ($v <= 14) {
				$db->setQuery("DROP TABLE IF EXISTS #__kide");
				$db->query();
			}
			if ($v <= 15) {
				$db->setQuery("DROP TABLE IF EXISTS #__kide_privados");
				$db->query();
			}
			if ($v <= 17) {
				$db->setQuery("DROP TABLE IF EXISTS #__kide_privados_offline");
				$db->query();
			}
			if ($v <= 18) {
				$db->setQuery("DROP TABLE IF EXISTS #__kide_sesion");
				$db->query();
			}
		}
	}
	
	function updateAfter($v) {
		$db = JFactory::getDBO();
		if ($v <= 10) {
			$db->setQuery("INSERT INTO `#__kide_iconos` (`code`, `img`, `ordering`) VALUES
				(':_(', 'crying.png', 11), ('8)', 'glasses.png', 10), (':S', 'confused.png', 8), (':O', 'surprise.png', 7),
				(':|', 'plain.png', 6), (':D', 'grin.png', 5), (':P', 'razz.png', 4), (';)', 'wink.png', 3), (':(', 'sad.png', 2),
				(':)', 'smile.png', 1), (':-*', 'kiss.png', 12), ('(!)', 'important.png', 13), ('(?)', 'help.png', 14),
				(':-|', 'plain.png', 21), (':-)', 'smile.png', 15), (':-(', 'sad.png', 16), (';-)', 'wink.png', 17),
				(':-P', 'razz.png', 18), (':-D', 'grin.png', 20), (':-O', 'surprise.png', 19), ('O.O', 'eek.png', 9),
				('xD', 'grin.png', 22)");
			$db->query();
		}
		if ($v <= 19) {
			$db->setQuery('ALTER TABLE `#__kide` ADD INDEX (`token`)');
			$db->query();
			$db->setQuery('ALTER TABLE `#__kide_sesion` ADD INDEX (`name`)');
			$db->query();
			$db->setQuery('ALTER TABLE `#__kide_sesion` ADD INDEX (`userid`)');
			$db->query();
			$db->setQuery('ALTER TABLE `#__kide_sesion` ADD INDEX (`time`)');
			$db->query();
			$db->setQuery('ALTER TABLE `#__kide_sesion` ADD INDEX (`ocultar`)');
			$db->query();
			$db->setQuery('ALTER TABLE `#__kide_privados` ADD INDEX (`to`)');
			$db->query();
			$db->setQuery('ALTER TABLE `#__kide_privados` ADD INDEX (`key`)');
			$db->query();
			$db->setQuery('ALTER TABLE `#__kide_privados_offline` ADD INDEX (`tid`)');
			$db->query();
		}
		if ($v <= 20) {
			$db->setQuery("ALTER TABLE `#__kide` ADD `ip` VARCHAR( 15 ) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT ''");
			$db->query();
		}
		if ($v > 0) {
			if ($v != KIDE_VERSION) {
				$db->setQuery("UPDATE #__kide_info SET value=".KIDE_VERSION." WHERE name='version'");
				$db->query();
			}
		}
	}
	
	function install() {
		$db = JFactory::getDBO();
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__kide_info` (
			`name` varchar(255) NOT NULL,
			`value` text NOT NULL,
			UNIQUE KEY `name` (`name`)
		) DEFAULT CHARSET=utf8");
		$db->query();

		$db->setQuery("SELECT value FROM #__kide_info WHERE name='version'");
		$v = (int)$db->loadResult();
		self::updateBefore($v);

		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__kide` (
		  `id` int(12) NOT NULL AUTO_INCREMENT,
		  `text` text NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `userid` int(12) NOT NULL,
		  `rango` int(1) NOT NULL,
		  `color` varchar(6) NOT NULL,
		  `img` text NOT NULL,
		  `url` text NOT NULL,
		  `time` int(12) NOT NULL,
		  `token` int(12) NOT NULL,
		  `sesion` varchar(200) NOT NULL,
		  PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8");
		$db->query();

		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__kide_privados` (
		  `id` int(12) NOT NULL AUTO_INCREMENT,
		  `text` text NOT NULL,
		  `fid` int(11) NOT NULL,
		  `from` varchar(255) NOT NULL,
		  `to` varchar(32) NOT NULL,
		  `rango` int(1) NOT NULL,
		  `color` varchar(6) NOT NULL,
		  `img` text NOT NULL,
		  `time` int(12) NOT NULL,
		  `sesion` varchar(32) NOT NULL,
		  `key` int(7) NOT NULL,
		  PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8");
		$db->query();

		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__kide_privados_offline` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `fid` int(11) NOT NULL,
		  `tid` int(11) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `color` varchar(6) NOT NULL,
		  `rango` int(1) NOT NULL,
		  `msg` text NOT NULL,
		  `img` text NOT NULL,
		  `time` int(12) NOT NULL,
		  PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8");
		$db->query();	
		
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__kide_bans` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`sesion` varchar(32) NOT NULL,
			`ip` varchar(15) DEFAULT NULL,
			`time` int(12) NOT NULL,
			PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8");
		$db->query();

		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__kide_sesion` (
		  `name` varchar(255) NOT NULL,
		  `userid` int(12) NOT NULL,
		  `rango` int(1) NOT NULL,
		  `img` text NOT NULL,
		  `time` int(12) NOT NULL,
		  `sesion` varchar(32) NOT NULL,
		  `privado` int(1) NOT NULL,
		  `ocultar` int(1) NOT NULL,
		  `key` int(7) NOT NULL,
		  UNIQUE KEY `sesion` (`sesion`)
		) DEFAULT CHARSET=utf8");
		$db->query();

		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__kide_iconos` (
			`id` int(12) NOT NULL AUTO_INCREMENT,
			`code` varchar(15) NOT NULL,
			`img` varchar(31) NOT NULL,
			`ordering` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8");
		$db->query();	

		self::updateAfter($v);

		$db->setQuery("INSERT INTO #__kide (name,text,time,rango,sesion,token,userid,img,url) VALUES ('System', 'Welcome!', ".time().", 0, 0, 0, 0, '', '')");
		$db->query();
	}
	
	function uninstall() {
		$db = JFactory::getDBO();
		$db->setQuery('DROP TABLE IF EXISTS `#__kide`, `#__kide_bans`, `#__kide_iconos`, `#__kide_info`, `#__kide_privados`, `#__kide_privados_offline`, `#__kide_sesion`');
		$db->query();
	}
}


if (version_compare(JVERSION, '1.6.0', '<')) {
	function com_install() {
		KideUpdater::install();
	}
	function com_uninstall() {
		KideUpdater::uninstall();
	}
}
else {
	class Com_KideInstallerScript {
		var $extension_name = 'com_kide';
		
		function install($parent) {
			KideUpdater::install();
		}
		function update($parent) {
			KideUpdater::install();
		}
		function uninstall($parent) {
			KideUpdater::uninstall();
		}
		
		function preflight($type, $parent) {
			if(in_array($type, array('install','discover_install'))) {
				$this->_bugfixDBFunctionReturnedNoError();
			} else {
				$this->_bugfixCantBuildAdminMenus();
			}
			return true;
		}
		
		function postflight($type, $parent) {
			$installer = $parent->getParent();
			$manifest = $installer->getManifest();
			$adminpath = $installer->getPath('extension_administrator');

			if (JFile::exists("{$adminpath}/kide.16.xml")) {
				if ( JFile::exists("{$adminpath}/kide.xml")) JFile::delete("{$adminpath}/kide.xml");
				JFile::move("{$adminpath}/kide.16.xml", "{$adminpath}/kide.xml");
			}
			
			return true;
		}
		
		function _bugfixDBFunctionReturnedNoError() {
			$db = JFactory::getDbo();
				
			// Fix broken #__assets records
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__assets')
				->where($db->qn('name').' = '.$db->q($this->extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__assets')
					->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}

			// Fix broken #__extensions records
			$query = $db->getQuery(true);
			$query->select('extension_id')
				->from('#__extensions')
				->where($db->qn('element').' = '.$db->q($this->extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__extensions')
					->where($db->qn('extension_id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}

			// Fix broken #__menu records
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__menu')
				->where($db->qn('type').' = '.$db->q('component'))
				->where($db->qn('menutype').' = '.$db->q('main'))
				->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__menu')
					->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}
		}
		
		/**
		 * Joomla! 1.6+ bugfix for "Can not build admin menus"
		 */
		function _bugfixCantBuildAdminMenus()
		{
			$db = JFactory::getDbo();
			
			// If there are multiple #__extensions record, keep one of them
			$query = $db->getQuery(true);
			$query->select('extension_id')
				->from('#__extensions')
				->where($db->qn('element').' = '.$db->q($this->extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(count($ids) > 1) {
				asort($ids);
				$extension_id = array_shift($ids); // Keep the oldest id
				
				foreach($ids as $id) {
					$query = $db->getQuery(true);
					$query->delete('#__extensions')
						->where($db->qn('extension_id').' = '.$db->q($id));
					$db->setQuery($query);
					$db->query();
				}
			}
			
			// @todo
			
			// If there are multiple assets records, delete all except the oldest one
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__assets')
				->where($db->qn('name').' = '.$db->q($this->extension_name));
			$db->setQuery($query);
			$ids = $db->loadObjectList();
			if(count($ids) > 1) {
				asort($ids);
				$asset_id = array_shift($ids); // Keep the oldest id
				
				foreach($ids as $id) {
					$query = $db->getQuery(true);
					$query->delete('#__assets')
						->where($db->qn('id').' = '.$db->q($id));
					$db->setQuery($query);
					$db->query();
				}
			}

			// Remove #__menu records for good measure!
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__menu')
				->where($db->qn('type').' = '.$db->q('component'))
				->where($db->qn('menutype').' = '.$db->q('main'))
				->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->extension_name));
			$db->setQuery($query);
			$ids1 = $db->loadColumn();
			if(empty($ids1)) $ids1 = array();
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__menu')
				->where($db->qn('type').' = '.$db->q('component'))
				->where($db->qn('menutype').' = '.$db->q('main'))
				->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->extension_name.'&%'));
			$db->setQuery($query);
			$ids2 = $db->loadColumn();
			if(empty($ids2)) $ids2 = array();
			$ids = array_merge($ids1, $ids2);
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__menu')
					->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}
		}
	}
}