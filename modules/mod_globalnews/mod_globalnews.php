<?php
/*------------------------------------------------------------------------
# mod_globalnews - Global News Module
# ------------------------------------------------------------------------
# author    Joomla!Vargas
# copyright Copyright (C) 2010 joomla.vargas.co.cr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.vargas.co.cr
# Technical Support:  Forum - http://joomla.vargas.co.cr/forum
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

require_once (dirname(__FILE__) . DS . 'helper.php');

global $globalnews_id;

if ( !$globalnews_id ) :
	$globalnews_id = 1;
endif;

$cat      = modGlobalNewsHelper::getGN_Cats($params);
$total    = count ( $cat );
$cols     = $params->get( 'cols', 1 );
$empty    = $params->get( 'empty', 0 );
$layout   = $params->get( 'layout', 'list' );
$show_cat = $params->get( 'show_cat', 1 );
$width    = $params->get( 'width', 'auto' );

if ( $width == 'auto' ) : $width = 100/$cols . '%'; endif;

modGlobalNewsHelper::addGN_CSS($params, $layout, $globalnews_id, $total);

require(JModuleHelper::getLayoutPath('mod_globalnews'));

$globalnews_id++;