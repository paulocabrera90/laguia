<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */


defined('_JEXEC') or die();

jimport( 'joomla.filesystem.folder' );

class JFormFieldKideTemplates extends JFormField
{

	protected $type = 'kidetemplates';

	protected function getInput() {
		$folders = JFolder::folders(JPATH_ROOT.'/components/com_kide/templates');
		$s = array();
		foreach ($folders as $f) $s[] = (object)array('text'=>$f);
		return JHTML::_('select.genericlist', $s, $this->name, 'class="inputbox"', 'text', 'text', $this->value, $this->id );
	}
}