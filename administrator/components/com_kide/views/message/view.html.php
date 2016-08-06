<?php
/*
 * @component Kide Shoutbox
 * @copyright Copyright (C) 2013 - JoniJnm.es
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class KideViewMessage extends KView
{
	protected $state;
	protected $item;
	protected $form;
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);

		JToolBarHelper::title(JText::_('COM_KIDE_MANAGER_MESSAGE'));

		JToolBarHelper::apply('message.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('message.save', 'JTOOLBAR_SAVE');
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('message.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('message.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
