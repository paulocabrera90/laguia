<?php

/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;
class KideController extends KController
{
	var $default_view = "messages";
	
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/kide.php';

		// Load the submenu.
		KideHelper::addSubmenu(JRequest::getCmd('view', 'messages'));

		parent::display();

		return $this;
	}
}