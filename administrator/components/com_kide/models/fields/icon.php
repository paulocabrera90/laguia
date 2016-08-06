<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldIcon extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Icon';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		jimport( 'joomla.filesystem.folder');
		$return = '<select name="'.$this->name.'" id="img" onchange="kide_show_img(this.value)">';
		$path = JPATH_ROOT."/components/com_kide/templates/default/images/iconos";
		$files = JFolder::files($path, "\.(png|gif|jpg)");
		$first = '';
		foreach ($files as $file) {
			if (!$first) $first = $file;
			$return .= '<option value="'.$file.'"'.($this->value == $file ? ' selected' : '').'>'.$file.'</option>';
		}
		$return .= '</select>';
		$return .= ' <img id="kide_imagen" src="'.JURI::root().'components/com_kide/templates/default/images/iconos/'.($this->value ? $this->value : $first).'" />';
		return $return;
	}
}