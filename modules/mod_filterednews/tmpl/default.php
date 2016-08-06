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
?>

<ul class="filterednews">
  <?php foreach ($list as $item) :  ?>
  <li class="filterednews"> <?php echo $item->title; ?> </li>
  <?php endforeach; ?>
</ul>