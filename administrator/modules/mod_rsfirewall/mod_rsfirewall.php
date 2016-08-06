<?php
/**
* @version 1.4.0
* @package RSFirewall! 1.4.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/modules/mod_rsfirewall/mod_rsfirewall.css');
$document->addStyleSheet(JURI::root().'administrator/components/com_rsfirewall/assets/css/rsfirewall.css');

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsfirewall'.DS.'helpers'.DS.'rsfirewall.php';
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsfirewall'.DS.'models'.DS.'rsfirewall.php';

RSFirewallHelper::readConfig();

$model = new RSFirewallModelRSFirewall();
$logs = $model->getLogs();

$lang =& JFactory::getLanguage();
$lang->load('com_rsfirewall');

$status = RSFirewallHelper::getConfig('active_scanner_status');
$lockdown = RSFirewallHelper::getConfig('lockdown');

$grade = RSFirewallHelper::getConfig('grade');
$color = '#C4C4C4'; // gray
if ($grade >= 75)
	$color = '#74B420'; // green
elseif ($grade > 0 && $grade < 75)
	$color = '#46AFCF'; // blue

require JModuleHelper::getLayoutPath('mod_rsfirewall');