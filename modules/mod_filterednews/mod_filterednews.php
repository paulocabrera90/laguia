<?php
/*------------------------------------------------------------------------
# mod_filterednews - Filtered News Module
# ------------------------------------------------------------------------
# author    Joomla!Vargas
# copyright Copyright (C) 2010 joomla.vargas.co.cr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.vargas.co.cr
# Technical Support:  Forum - http://joomla.vargas.co.cr/forum
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

global $filterednews_id;

if ( !$filterednews_id ) : $filterednews_id = 1; endif;

require_once (dirname(__FILE__).DS.'helper.php');

$list = modFilteredNewsHelper::getFN_List($params);

if ( !count($list ) ) return;

if ( $alt_title = $params->get('alt_title', '') ) echo '<h3>' . $alt_title . '</h3>';

$layout = $params->get('layout', 'default');

if ( $layout != 'default' ) {
	modFilteredNewsHelper::addFN_CSS($params,$layout,$filterednews_id);
}

require(JModuleHelper::getLayoutPath('mod_filterednews', $layout));

$filterednews_id++;