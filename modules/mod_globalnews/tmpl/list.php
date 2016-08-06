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
?>
<div class="gn_list gn_list_<?php echo $globalnews_id; ?>">
  <ul>
    <?php foreach ($list as $item) :  ?>
    <li> <?php echo $item->title; ?> </li>
    <?php endforeach; ?>
  </ul>
<?php
if ( $more == 1 && $group->link ) : ?>
  <div> <?php echo JHTML::_('link', $group->link, JText::_('More Articles...'), array('class'=>'readon') ); ?> </div>
<?php
endif; ?>
</div>
