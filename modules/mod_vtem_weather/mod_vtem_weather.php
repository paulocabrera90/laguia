<?php
/*------------------------------------------------------------------------
# mod_vtem_weather - VTEM Weather Module
# ------------------------------------------------------------------------
# author Nguyen Van Tuyen
# copyright Copyright (C) 2011 VTEM.NET. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.vtem.net
# Technical Support: Forum - http://vtem.net/en/forum.html
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
$document =& JFactory::getDocument();	
$document->addStyleSheet(JURI::root().'modules/mod_vtem_weather/assets/style.css');
require(JModuleHelper::getLayoutPath('mod_vtem_weather'));
?>